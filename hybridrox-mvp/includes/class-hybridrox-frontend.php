<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_Frontend
{
    public static function init(): void
    {
        add_filter('template_include', [__CLASS__, 'single_template']);
        add_filter('theme_page_templates', [__CLASS__, 'register_page_template']);
        add_filter('template_include', [__CLASS__, 'load_page_template']);

        add_shortcode('hybridrox_workout_grid', [__CLASS__, 'workout_grid_shortcode']);
        add_shortcode('hybridrox_workout_filters', [__CLASS__, 'workout_filters_shortcode']);

        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_styles']);
        add_action('pre_get_posts', [__CLASS__, 'apply_default_sorting']);

        add_filter('facetwp_query_args', [__CLASS__, 'facetwp_sorting'], 10, 2);
        add_filter('facetwp_facets', [__CLASS__, 'register_facets']);
    }

    public static function enqueue_styles(): void
    {
        wp_register_style('hybridrox-mvp', HYBRIDROX_MVP_URL . 'templates/hybridrox-mvp.css', [], HYBRIDROX_MVP_VERSION);
        wp_enqueue_style('hybridrox-mvp');
    }

    public static function register_page_template(array $templates): array
    {
        $templates['page-workouts-listing.php'] = 'Hybridrox - Liste des workouts';
        return $templates;
    }

    public static function load_page_template(string $template): string
    {
        if (is_page()) {
            $selected = get_page_template_slug(get_queried_object_id());
            if ('page-workouts-listing.php' === $selected) {
                return HYBRIDROX_MVP_PATH . 'templates/page-workouts-listing.php';
            }
        }

        return $template;
    }

    public static function single_template(string $template): string
    {
        if (is_singular('workout')) {
            return HYBRIDROX_MVP_PATH . 'templates/single-workout.php';
        }

        return $template;
    }

    public static function workout_filters_shortcode(): string
    {
        if (! shortcode_exists('facetwp')) {
            return '<p>FacetWP est requis pour utiliser les filtres.</p>';
        }

        $facet_labels = [
            'duration' => 'Durée',
            'level' => 'Niveau',
            'goals' => 'Objectifs',
            'format' => 'Format',
            'equipment' => 'Équipement',
            'stations' => 'Stations',
            'environment' => 'Environnement',
            'intensity' => 'Intensité',
        ];

        $output = '<div class="hybridrox-filters">';
        foreach ($facet_labels as $facet => $label) {
            $output .= '<div class="hybridrox-filter-item"><label>' . esc_html($label) . '</label>';
            $output .= do_shortcode('[facetwp facet="' . esc_attr($facet) . '"]');
            $output .= '</div>';
        }
        $output .= '</div>';

        return $output;
    }

    public static function workout_grid_shortcode(): string
    {
        $query = new WP_Query([
            'post_type' => 'workout',
            'posts_per_page' => 12,
            'meta_key' => 'featured',
            'orderby' => ['meta_value_num' => 'DESC', 'date' => 'DESC'],
            'facetwp' => true,
        ]);

        ob_start();
        echo '<div class="facetwp-template hybridrox-workout-grid">';

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                self::render_workout_card(get_the_ID());
            }
        } else {
            echo '<p class="hybridrox-empty">Aucun workout trouvé avec ces filtres.</p>';
        }

        echo '</div>';
        wp_reset_postdata();

        return (string) ob_get_clean();
    }

    private static function render_workout_card(int $post_id): void
    {
        $duration = get_field('duration', $post_id);
        $short_description = get_field('short_description', $post_id);
        $level = wp_get_post_terms($post_id, 'level', ['fields' => 'names']);
        $goals = wp_get_post_terms($post_id, 'goal', ['fields' => 'names']);
        $format = wp_get_post_terms($post_id, 'format', ['fields' => 'names']);
        $equipment = wp_get_post_terms($post_id, 'equipment', ['fields' => 'names']);

        echo '<article class="hybridrox-workout-card">';
        if (has_post_thumbnail($post_id)) {
            echo '<a class="hybridrox-workout-thumb" href="' . esc_url(get_permalink($post_id)) . '">' . get_the_post_thumbnail($post_id, 'medium_large') . '</a>';
        }

        echo '<div class="hybridrox-workout-body">';
        echo '<h3><a href="' . esc_url(get_permalink($post_id)) . '">' . esc_html(get_the_title($post_id)) . '</a></h3>';
        echo '<p class="hybridrox-duration">⏱️ ' . esc_html((string) $duration) . ' min</p>';
        if (! empty($short_description)) {
            echo '<p class="hybridrox-card-description">' . esc_html($short_description) . '</p>';
        }
        echo '<ul class="hybridrox-meta">';
        echo '<li><strong>Niveau :</strong> ' . esc_html(implode(', ', $level)) . '</li>';
        echo '<li><strong>Objectifs :</strong> ' . esc_html(implode(', ', $goals)) . '</li>';
        echo '<li><strong>Format :</strong> ' . esc_html(implode(', ', $format)) . '</li>';
        echo '<li><strong>Équipement :</strong> ' . esc_html(implode(', ', array_slice($equipment, 0, 3))) . '</li>';
        echo '</ul>';
        echo '<a class="hybridrox-btn" href="' . esc_url(get_permalink($post_id)) . '">Ouvrir la séance</a>';
        echo '</div>';
        echo '</article>';
    }

    public static function apply_default_sorting(WP_Query $query): void
    {
        if (is_admin() || ! $query->is_main_query()) {
            return;
        }

        if ($query->is_post_type_archive('workout')) {
            $query->set('meta_key', 'featured');
            $query->set('orderby', ['meta_value_num' => 'DESC', 'date' => 'DESC']);
        }
    }

    public static function facetwp_sorting(array $args, $class): array
    {
        if (! empty($args['post_type']) && 'workout' === $args['post_type']) {
            $args['meta_key'] = 'featured';
            $args['orderby'] = [
                'meta_value_num' => 'DESC',
                'date' => 'DESC',
            ];
        }

        return $args;
    }

    public static function register_facets(array $facets): array
    {
        $defaults = [
            ['name' => 'duration', 'label' => 'Durée', 'type' => 'number_range', 'source' => 'cf/duration'],
            ['name' => 'level', 'label' => 'Niveau', 'type' => 'checkboxes', 'source' => 'tax/level'],
            ['name' => 'goals', 'label' => 'Objectifs', 'type' => 'checkboxes', 'source' => 'tax/goal'],
            ['name' => 'format', 'label' => 'Format', 'type' => 'checkboxes', 'source' => 'tax/format'],
            ['name' => 'equipment', 'label' => 'Équipement', 'type' => 'checkboxes', 'source' => 'tax/equipment'],
            ['name' => 'stations', 'label' => 'Stations', 'type' => 'checkboxes', 'source' => 'tax/stations'],
            ['name' => 'environment', 'label' => 'Environnement', 'type' => 'checkboxes', 'source' => 'tax/environment'],
            ['name' => 'intensity', 'label' => 'Intensité', 'type' => 'checkboxes', 'source' => 'tax/intensity'],
        ];

        foreach ($defaults as $facet) {
            $exists = false;
            foreach ($facets as $existing) {
                if (($existing['name'] ?? '') === $facet['name']) {
                    $exists = true;
                    break;
                }
            }
            if (! $exists) {
                $facets[] = $facet;
            }
        }

        return $facets;
    }
}
