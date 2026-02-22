<?php
/**
 * Plugin Name: HybridRox Workout Filters
 * Description: Reusable workout filtering system with shortcode and Elementor widget support.
 * Version: 1.0.0
 * Author: HybridRox
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: hybridrox-workout-filters
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HYBRIDROX_WF_VERSION', '1.0.0' );
define( 'HYBRIDROX_WF_PLUGIN_FILE', __FILE__ );
define( 'HYBRIDROX_WF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'HYBRIDROX_WF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/class-hybridrox-workout-filters-plugin.php';

\HybridRox\WorkoutFilters\Plugin::instance();
