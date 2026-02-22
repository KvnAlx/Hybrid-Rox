<?php
namespace HybridRox\WorkoutFilters;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CPT {
	private const TAXONOMIES = array(
		'workout_type'  => 'Workout Type',
		'equipment'     => 'Equipment',
		'modality'      => 'Modality',
		'goal'          => 'Goal',
		'difficulty'    => 'Difficulty',
		'location'      => 'Location',
		'hyrox_station' => 'Hyrox Station',
	);

	public function __construct() {
		add_action( 'init', array( $this, 'register_workout_cpt' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
	}

	public function register_workout_cpt() {
		register_post_type(
			'workout',
			array(
				'label'        => __( 'Workouts', 'hybridrox-workout-filters' ),
				'public'       => true,
				'show_in_rest' => true,
				'has_archive'  => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
				'menu_icon'    => 'dashicons-heart',
			)
		);
	}

	public function register_taxonomies() {
		foreach ( self::TAXONOMIES as $taxonomy => $label ) {
			register_taxonomy(
				$taxonomy,
				'workout',
				array(
					'label'        => __( $label, 'hybridrox-workout-filters' ),
					'hierarchical' => true,
					'show_in_rest' => true,
					'public'       => true,
				)
			);
		}
	}

	public static function taxonomies() {
		return array_keys( self::TAXONOMIES );
	}
}
