<?php
/**
 * Plugin Name: Hybridrox MVP System
 * Description: Système MVP Hybridrox avec CPT d'entraînements, taxonomies, champs ACF, filtres FacetWP, templates et données d'exemple.
 * Version: 1.0.0
 * Author: Hybridrox
 * Text Domain: hybridrox-mvp
 */

if (! defined('ABSPATH')) {
    exit;
}

define('HYBRIDROX_MVP_VERSION', '1.0.0');
define('HYBRIDROX_MVP_PATH', plugin_dir_path(__FILE__));
define('HYBRIDROX_MVP_URL', plugin_dir_url(__FILE__));

require_once HYBRIDROX_MVP_PATH . 'includes/class-hybridrox-cpt-tax.php';
require_once HYBRIDROX_MVP_PATH . 'includes/class-hybridrox-acf.php';
require_once HYBRIDROX_MVP_PATH . 'includes/class-hybridrox-admin.php';
require_once HYBRIDROX_MVP_PATH . 'includes/class-hybridrox-frontend.php';
require_once HYBRIDROX_MVP_PATH . 'includes/class-hybridrox-seeder.php';

add_action('plugins_loaded', static function (): void {
    Hybridrox_CPT_Tax::init();
    Hybridrox_ACF::init();
    Hybridrox_Admin::init();
    Hybridrox_Frontend::init();
});

register_activation_hook(__FILE__, static function (): void {
    Hybridrox_CPT_Tax::register_post_type();
    Hybridrox_CPT_Tax::register_taxonomies();
    flush_rewrite_rules();
    Hybridrox_Seeder::seed_workouts();
});

register_deactivation_hook(__FILE__, static function (): void {
    flush_rewrite_rules();
});
