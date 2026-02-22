<?php
namespace HybridRox\WorkoutFilters;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Assets {
	private $should_enqueue = false;

	public function register() {
		$asset_file = HYBRIDROX_WF_PLUGIN_DIR . 'dist/index.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			$asset_file = HYBRIDROX_WF_PLUGIN_DIR . 'assets/index.asset.php';
		}
		$asset = file_exists( $asset_file ) ? include $asset_file : array(
			'dependencies' => array( 'wp-element' ),
			'version'      => HYBRIDROX_WF_VERSION,
		);

		$script_rel = file_exists( HYBRIDROX_WF_PLUGIN_DIR . 'dist/index.js' ) ? 'dist/index.js' : 'assets/index.js';
		$style_rel  = file_exists( HYBRIDROX_WF_PLUGIN_DIR . 'dist/index.css' ) ? 'dist/index.css' : 'assets/index.css';

		wp_register_script(
			'hybridrox-workout-filters-app',
			HYBRIDROX_WF_PLUGIN_URL . $script_rel,
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_register_style(
			'hybridrox-workout-filters-app',
			HYBRIDROX_WF_PLUGIN_URL . $style_rel,
			array(),
			$asset['version']
		);
	}

	public function mark_for_enqueue() {
		$this->should_enqueue = true;
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_if_needed' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_if_needed' ) );
	}

	public function enqueue_if_needed() {
		if ( ! $this->should_enqueue ) {
			return;
		}
		wp_enqueue_script( 'hybridrox-workout-filters-app' );
		wp_enqueue_style( 'hybridrox-workout-filters-app' );
		wp_localize_script(
			'hybridrox-workout-filters-app',
			'HybridRoxWorkoutFiltersConfig',
			array(
				'restBase' => esc_url_raw( rest_url( 'hybridrox/v1' ) ),
				'nonce'    => wp_create_nonce( 'wp_rest' ),
			)
		);
	}
}
