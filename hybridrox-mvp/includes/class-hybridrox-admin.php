<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_Admin
{
    public static function init(): void
    {
        add_action('save_post_workout', [__CLASS__, 'validate_equipment_terms'], 20, 3);
        add_action('admin_notices', [__CLASS__, 'display_admin_notice']);
    }

    public static function validate_equipment_terms(int $post_id, WP_Post $post, bool $update): void
    {
        if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
            return;
        }

        $terms = wp_get_post_terms($post_id, 'equipment', ['fields' => 'slugs']);
        if (is_wp_error($terms) || empty($terms)) {
            return;
        }

        $has_no_equipment = in_array('no-equipment', $terms, true);
        $has_machine = in_array('rower', $terms, true) || in_array('ski-erg-machine', $terms, true);

        if ($has_no_equipment && $has_machine) {
            set_transient('hybridrox_equipment_warning_' . get_current_user_id(), $post_id, 60);
        }
    }

    public static function display_admin_notice(): void
    {
        if (! is_admin()) {
            return;
        }

        $key = 'hybridrox_equipment_warning_' . get_current_user_id();
        $post_id = (int) get_transient($key);
        if (! $post_id) {
            return;
        }

        delete_transient($key);
        $screen = get_current_screen();
        if (! $screen || 'workout' !== $screen->post_type) {
            return;
        }

        echo '<div class="notice notice-warning is-dismissible"><p>';
        echo esc_html__('Validation warning: "No Equipment" conflicts with machine equipment (Rower / Ski Erg Machine). Please adjust equipment terms.', 'hybridrox-mvp');
        echo '</p></div>';
    }
}
