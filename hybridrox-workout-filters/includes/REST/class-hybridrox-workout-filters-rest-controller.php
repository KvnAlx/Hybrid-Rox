<?php
namespace HybridRox\WorkoutFilters\REST;

use HybridRox\WorkoutFilters\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Controller {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route(
			'hybridrox/v1',
			'/workouts',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_workouts' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'hybridrox/v1',
			'/workouts/facets',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_facets' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public static function build_query_args( $params ) {
		$page     = max( 1, absint( $params['page'] ?? 1 ) );
		$per_page = min( 50, max( 1, absint( $params['per_page'] ?? 12 ) ) );

		$args = array(
			'post_type'      => 'workout',
			'post_status'    => 'publish',
			'paged'          => $page,
			'posts_per_page' => $per_page,
			's'              => sanitize_text_field( $params['search'] ?? '' ),
			'tax_query'      => array(),
			'meta_query'     => array(),
		);

		foreach ( CPT::taxonomies() as $taxonomy ) {
			if ( empty( $params[ $taxonomy ] ) ) {
				continue;
			}
			$terms = is_array( $params[ $taxonomy ] ) ? $params[ $taxonomy ] : explode( ',', (string) $params[ $taxonomy ] );
			$terms = array_filter( array_map( 'sanitize_text_field', $terms ) );
			if ( ! empty( $terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $terms,
				);
			}
		}

		if ( isset( $params['duration_min'] ) || isset( $params['duration_max'] ) ) {
			$args['meta_query'][] = array(
				'key'     => 'duration_minutes',
				'value'   => array( absint( $params['duration_min'] ?? 0 ), absint( $params['duration_max'] ?? 999 ) ),
				'compare' => 'BETWEEN',
				'type'    => 'NUMERIC',
			);
		}

		if ( ! empty( $params['only_with_my_equipment'] ) && ! empty( $params['user_equipment'] ) ) {
			$equipment = is_array( $params['user_equipment'] ) ? $params['user_equipment'] : explode( ',', (string) $params['user_equipment'] );
			$args['tax_query'][] = array(
				'taxonomy' => 'equipment',
				'field'    => 'slug',
				'terms'    => array_map( 'sanitize_text_field', $equipment ),
			);
		}

		$sort = sanitize_text_field( $params['sort'] ?? 'newest' );
		switch ( $sort ) {
			case 'duration_asc':
				$args['meta_key'] = 'duration_minutes';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'ASC';
				break;
			case 'duration_desc':
				$args['meta_key'] = 'duration_minutes';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'difficulty':
				$args['orderby'] = 'title';
				$args['order']   = 'ASC';
				break;
			case 'popularity':
				$args['meta_key'] = 'popularity_score';
				$args['orderby']  = 'meta_value_num';
				$args['order']    = 'DESC';
				break;
			case 'newest':
			default:
				$args['orderby'] = 'date';
				$args['order']   = 'DESC';
		}

		if ( count( $args['tax_query'] ) > 1 ) {
			$args['tax_query']['relation'] = 'AND';
		}
		if ( empty( $args['tax_query'] ) ) {
			unset( $args['tax_query'] );
		}
		if ( empty( $args['meta_query'] ) ) {
			unset( $args['meta_query'] );
		}

		return $args;
	}

	public function get_workouts( $request ) {
		$params = $request->get_params();
		$args   = self::build_query_args( $params );
		$query  = new \WP_Query( $args );

		$items = array_map(
			function ( $post ) {
				$taxonomies = array();
				foreach ( CPT::taxonomies() as $taxonomy ) {
					$terms               = get_the_terms( $post->ID, $taxonomy );
					$taxonomies[ $taxonomy ] = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'name' ) : array();
				}
				return array(
					'id'               => $post->ID,
					'title'            => get_the_title( $post->ID ),
					'excerpt'          => get_the_excerpt( $post->ID ),
					'duration_minutes' => (int) get_post_meta( $post->ID, 'duration_minutes', true ),
					'time_cap_minutes' => (int) get_post_meta( $post->ID, 'time_cap_minutes', true ),
					'popularity_score' => (int) get_post_meta( $post->ID, 'popularity_score', true ),
					'taxonomies'       => $taxonomies,
					'permalink'        => get_permalink( $post->ID ),
				);
			},
			$query->posts
		);

		return rest_ensure_response(
			array(
				'items'       => $items,
				'total'       => (int) $query->found_posts,
				'total_pages' => (int) $query->max_num_pages,
				'facets'      => $this->build_facets(),
			)
		);
	}

	public function get_facets() {
		return rest_ensure_response( $this->build_facets() );
	}

	private function build_facets() {
		$facets = array();
		foreach ( CPT::taxonomies() as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => false,
				)
			);
			$facets[ $taxonomy ] = array_map(
				function ( $term ) {
					return array(
						'slug'  => $term->slug,
						'name'  => $term->name,
						'count' => (int) $term->count,
					);
				},
				is_array( $terms ) ? $terms : array()
			);
		}
		return $facets;
	}
}
