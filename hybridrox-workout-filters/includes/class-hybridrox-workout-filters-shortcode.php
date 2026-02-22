<?php
namespace HybridRox\WorkoutFilters;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shortcode {
	private $assets;

	public function __construct( Assets $assets ) {
		$this->assets = $assets;
		add_shortcode( 'hybridrox_workouts', array( $this, 'render' ) );
	}

	public function render( $atts = array() ) {
		$atts = shortcode_atts(
			array(
				'preset'          => 'hyrox_workouts',
				'instance'        => 'default-instance',
				'per_page'        => 12,
				'default_sort'    => 'newest',
				'default_filters' => '{}',
				'show_filters'    => '',
				'custom_schema'   => '',
			),
			$atts,
			'hybridrox_workouts'
		);

		$this->assets->mark_for_enqueue();
		$preset = Presets::get( $atts['preset'] );

		$config = array(
			'preset'         => $atts['preset'],
			'instance'       => $atts['instance'],
			'perPage'        => (int) $atts['per_page'],
			'defaultSort'    => sanitize_text_field( $atts['default_sort'] ),
			'defaultFilters' => json_decode( $atts['default_filters'], true ) ?: array(),
			'schema'         => ! empty( $atts['custom_schema'] ) ? json_decode( $atts['custom_schema'], true ) : $preset,
			'showFilters'    => array_filter( array_map( 'trim', explode( ',', $atts['show_filters'] ) ) ),
			'isEditor'       => class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->editor->is_edit_mode(),
		);

		return sprintf(
			'<div class="hybridrox-filters-app" data-preset="%s" data-instance="%s" data-per-page="%d" data-default-sort="%s" data-config="%s"></div>',
			esc_attr( $atts['preset'] ),
			esc_attr( $atts['instance'] ),
			(int) $atts['per_page'],
			esc_attr( $atts['default_sort'] ),
			esc_attr( wp_json_encode( $config ) )
		);
	}
}
