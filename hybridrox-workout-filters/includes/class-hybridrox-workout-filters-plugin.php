<?php
namespace HybridRox\WorkoutFilters;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/class-hybridrox-workout-filters-cpt.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/class-hybridrox-workout-filters-assets.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/class-hybridrox-workout-filters-shortcode.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/class-hybridrox-workout-filters-presets.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/REST/class-hybridrox-workout-filters-rest-controller.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/Elementor/class-hybridrox-workout-filters-elementor-widget.php';
require_once HYBRIDROX_WF_PLUGIN_DIR . 'includes/CLI/class-hybridrox-workout-filters-seeder.php';

class Plugin {
	private static $instance;
	private $assets;

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->assets = new Assets();

		new CPT();
		new Shortcode( $this->assets );
		new REST\Controller();
		new Elementor\Widget_Registration( $this->assets );
		new CLI\Seeder();

		add_action( 'init', array( $this, 'register_assets' ) );
	}

	public function register_assets() {
		$this->assets->register();
	}
}
