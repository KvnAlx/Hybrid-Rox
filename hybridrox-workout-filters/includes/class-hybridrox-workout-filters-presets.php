<?php
namespace HybridRox\WorkoutFilters;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Presets {
	public static function all() {
		return array(
			'hyrox_workouts'    => array(
				'label'   => 'Hyrox Workouts',
				'filters' => array(
					array( 'key' => 'search', 'type' => 'search', 'label' => 'Search' ),
					array( 'key' => 'difficulty', 'type' => 'single-select', 'taxonomy' => 'difficulty', 'label' => 'Difficulty' ),
					array( 'key' => 'hyrox_station', 'type' => 'multi-select', 'taxonomy' => 'hyrox_station', 'label' => 'Station' ),
					array( 'key' => 'equipment', 'type' => 'multi-select', 'taxonomy' => 'equipment', 'label' => 'Equipment' ),
					array( 'key' => 'duration', 'type' => 'range', 'meta_min' => 'duration_min', 'meta_max' => 'duration_max', 'label' => 'Duration' ),
				),
			),
			'strength_sessions' => array(
				'label'   => 'Strength Sessions',
				'filters' => array(
					array( 'key' => 'search', 'type' => 'search', 'label' => 'Search' ),
					array( 'key' => 'goal', 'type' => 'multi-select', 'taxonomy' => 'goal', 'label' => 'Goal' ),
					array( 'key' => 'modality', 'type' => 'single-select', 'taxonomy' => 'modality', 'label' => 'Modality' ),
					array( 'key' => 'location', 'type' => 'single-select', 'taxonomy' => 'location', 'label' => 'Location' ),
					array( 'key' => 'only_with_my_equipment', 'type' => 'boolean', 'label' => 'Only My Equipment' ),
				),
			),
		);
	}

	public static function get( $key ) {
		$presets = self::all();
		return isset( $presets[ $key ] ) ? $presets[ $key ] : $presets['hyrox_workouts'];
	}
}
