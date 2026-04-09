<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_Admin
{
    private static array $taxonomies = ['level', 'goal', 'format', 'equipment', 'stations', 'environment', 'intensity'];

    public static function init(): void
    {
        add_action('save_post_workout', [__CLASS__, 'validate_equipment_terms'], 20, 3);
        add_action('admin_notices', [__CLASS__, 'display_admin_notice']);

        add_action('add_meta_boxes_workout', [__CLASS__, 'register_taxonomy_metabox']);
        add_action('admin_head-post.php', [__CLASS__, 'remove_default_taxonomy_metaboxes']);
        add_action('admin_head-post-new.php', [__CLASS__, 'remove_default_taxonomy_metaboxes']);
        add_action('save_post_workout', [__CLASS__, 'save_taxonomy_metabox'], 15, 2);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_styles']);
    }

    public static function enqueue_admin_styles(string $hook): void
    {
        if (! in_array($hook, ['post-new.php', 'post.php'], true)) {
            return;
        }

        $screen = get_current_screen();
        if (! $screen || 'workout' !== $screen->post_type) {
            return;
        }

        $css = '.hybridrox-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(240px,1fr));gap:14px}.hybridrox-admin-item label{display:block;font-weight:600;margin-bottom:6px}.hybridrox-admin-item select{width:100%;min-height:120px}.hybridrox-admin-help{margin:0 0 10px;color:#646970}';
        wp_add_inline_style('wp-admin', $css);
    }

    public static function remove_default_taxonomy_metaboxes(): void
    {
        $screen = get_current_screen();
        if (! $screen || 'workout' !== $screen->post_type) {
            return;
        }

        foreach (self::$taxonomies as $taxonomy) {
            remove_meta_box($taxonomy . 'div', 'workout', 'side');
            remove_meta_box('tagsdiv-' . $taxonomy, 'workout', 'side');
        }
    }

    public static function register_taxonomy_metabox(): void
    {
        add_meta_box(
            'hybridrox_taxonomy_select',
            __('Catégorisation de l\'entraînement', 'hybridrox-mvp'),
            [__CLASS__, 'render_taxonomy_metabox'],
            'workout',
            'side',
            'high'
        );
    }

    public static function render_taxonomy_metabox(WP_Post $post): void
    {
        wp_nonce_field('hybridrox_save_taxonomies', 'hybridrox_tax_nonce');

        echo '<p class="hybridrox-admin-help">Sélectionnez une ou plusieurs valeurs par catégorie (Ctrl/Cmd + clic pour multi-sélection).</p>';
        echo '<div class="hybridrox-admin-grid">';

        foreach (self::$taxonomies as $taxonomy) {
            $taxonomy_object = get_taxonomy($taxonomy);
            if (! $taxonomy_object) {
                continue;
            }

            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ]);
            $selected = wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'ids']);

            echo '<div class="hybridrox-admin-item">';
            echo '<label for="hybridrox_tax_' . esc_attr($taxonomy) . '">' . esc_html($taxonomy_object->labels->name) . '</label>';
            echo '<select id="hybridrox_tax_' . esc_attr($taxonomy) . '" name="hybridrox_tax[' . esc_attr($taxonomy) . '][]" multiple>';

            if (! is_wp_error($terms)) {
                foreach ($terms as $term) {
                    $is_selected = in_array((int) $term->term_id, $selected, true) ? 'selected' : '';
                    echo '<option value="' . esc_attr((string) $term->term_id) . '" ' . $is_selected . '>' . esc_html($term->name) . '</option>';
                }
            }

            echo '</select>';
            echo '</div>';
        }

        echo '</div>';
    }

    public static function save_taxonomy_metabox(int $post_id, WP_Post $post): void
    {
        if (! isset($_POST['hybridrox_tax_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['hybridrox_tax_nonce'])), 'hybridrox_save_taxonomies')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        $posted_tax = isset($_POST['hybridrox_tax']) && is_array($_POST['hybridrox_tax']) ? wp_unslash($_POST['hybridrox_tax']) : [];

        foreach (self::$taxonomies as $taxonomy) {
            $term_ids = [];
            if (isset($posted_tax[$taxonomy]) && is_array($posted_tax[$taxonomy])) {
                $term_ids = array_map('intval', $posted_tax[$taxonomy]);
                $term_ids = array_filter($term_ids);
            }
            wp_set_object_terms($post_id, $term_ids, $taxonomy, false);
        }
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
        echo esc_html__('Alerte de validation : "Sans équipement" est incompatible avec les machines (Rameur / Ski Erg). Merci d\'ajuster la sélection.', 'hybridrox-mvp');
        echo '</p></div>';
    }
}
