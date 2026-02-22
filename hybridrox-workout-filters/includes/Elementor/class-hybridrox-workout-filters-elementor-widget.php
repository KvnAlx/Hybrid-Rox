<?php
namespace HybridRox\WorkoutFilters\Elementor;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use HybridRox\WorkoutFilters\Assets;
use HybridRox\WorkoutFilters\Presets;
use HybridRox\WorkoutFilters\Shortcode;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class HybridRox_Workout_Widget extends Widget_Base {
	public function get_name() {
		return 'hybridrox_workout_filters';
	}

	public function get_title() {
		return __( 'HybridRox Workout Filters', 'hybridrox-workout-filters' );
	}

	public function get_icon() {
		return 'eicon-filter';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function register_controls() {
		$options = array();
		foreach ( Presets::all() as $key => $preset ) {
			$options[ $key ] = $preset['label'];
		}

		$this->start_controls_section( 'content_section', array( 'label' => __( 'Workout Filters', 'hybridrox-workout-filters' ) ) );
		$this->add_control( 'preset', array( 'label' => __( 'Preset', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::SELECT, 'default' => 'hyrox_workouts', 'options' => $options ) );
		$this->add_control( 'instance', array( 'label' => __( 'Instance Key', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::TEXT, 'default' => 'elementor-instance' ) );
		$this->add_control( 'per_page', array( 'label' => __( 'Per Page', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::NUMBER, 'default' => 12 ) );
		$this->add_control( 'default_sort', array( 'label' => __( 'Default Sort', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::SELECT, 'default' => 'newest', 'options' => array('newest'=>'Newest','duration_asc'=>'Duration ↑','duration_desc'=>'Duration ↓','difficulty'=>'Difficulty','popularity'=>'Popularity') ) );
		$this->add_control( 'show_filters', array( 'label' => __( 'Show Filters (comma-separated keys)', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::TEXT, 'default' => '' ) );
		$this->add_control( 'default_filters', array( 'label' => __( 'Default Filters JSON', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::TEXTAREA, 'default' => '{}' ) );
		$this->add_control( 'custom_schema', array( 'label' => __( 'Custom Schema JSON', 'hybridrox-workout-filters' ), 'type' => Controls_Manager::TEXTAREA, 'default' => '' ) );
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		echo do_shortcode(
			sprintf(
				'[hybridrox_workouts preset="%s" instance="%s" per_page="%d" default_sort="%s" show_filters="%s" default_filters="%s" custom_schema="%s"]',
				esc_attr( $settings['preset'] ),
				esc_attr( $settings['instance'] ),
				(int) $settings['per_page'],
				esc_attr( $settings['default_sort'] ),
				esc_attr( $settings['show_filters'] ),
				esc_attr( wp_json_encode( json_decode( $settings['default_filters'], true ) ?: array() ) ),
				esc_attr( wp_json_encode( json_decode( $settings['custom_schema'], true ) ?: '' ) )
			)
		);
	}
}

class Widget_Registration {
	private $assets;

	public function __construct( Assets $assets ) {
		$this->assets = $assets;
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
	}

	public function register_widget( $widgets_manager ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		$this->assets->mark_for_enqueue();
		$widgets_manager->register( new HybridRox_Workout_Widget() );
	}
}
