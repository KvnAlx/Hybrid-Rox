<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_CPT_Tax
{
    public static function init(): void
    {
        add_action('init', [__CLASS__, 'register_post_type']);
        add_action('init', [__CLASS__, 'register_taxonomies']);
        add_action('init', [__CLASS__, 'ensure_default_terms'], 20);
    }

    public static function register_post_type(): void
    {
        register_post_type('workout', [
            'labels' => [
                'name' => __('Workouts', 'hybridrox-mvp'),
                'singular_name' => __('Workout', 'hybridrox-mvp'),
                'add_new' => __('Ajouter', 'hybridrox-mvp'),
                'add_new_item' => __('Ajouter un workout', 'hybridrox-mvp'),
                'edit_item' => __('Modifier le workout', 'hybridrox-mvp'),
                'new_item' => __('Nouveau workout', 'hybridrox-mvp'),
                'view_item' => __('Voir le workout', 'hybridrox-mvp'),
                'search_items' => __('Rechercher des workouts', 'hybridrox-mvp'),
                'not_found' => __('Aucun workout trouvé', 'hybridrox-mvp'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => [
                'slug' => 'workouts',
                'with_front' => false,
            ],
            'menu_icon' => 'dashicons-universal-access-alt',
            'supports' => ['title', 'editor', 'thumbnail'],
            'show_in_rest' => true,
        ]);
    }

    public static function register_taxonomies(): void
    {
        $taxonomy_labels = [
            'level' => 'Niveau',
            'goal' => 'Objectif',
            'format' => 'Format',
            'equipment' => 'Équipement',
            'stations' => 'Stations',
            'environment' => 'Environnement',
            'intensity' => 'Intensité',
        ];

        foreach ($taxonomy_labels as $taxonomy => $label) {
            register_taxonomy($taxonomy, ['workout'], [
                'labels' => [
                    'name' => __($label, 'hybridrox-mvp'),
                    'singular_name' => __($label, 'hybridrox-mvp'),
                    'search_items' => sprintf(__('Rechercher : %s', 'hybridrox-mvp'), $label),
                    'all_items' => sprintf(__('Tous les %s', 'hybridrox-mvp'), $label),
                    'edit_item' => sprintf(__('Modifier : %s', 'hybridrox-mvp'), $label),
                    'add_new_item' => sprintf(__('Ajouter : %s', 'hybridrox-mvp'), $label),
                ],
                'public' => true,
                'show_admin_column' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'hierarchical' => false,
                'rewrite' => ['slug' => $taxonomy],
            ]);
        }
    }

    public static function ensure_default_terms(): void
    {
        $term_map = [
            'level' => ['beginner', 'intermediate', 'advanced'],
            'goal' => ['endurance', 'strength', 'power', 'engine', 'technique', 'recovery'],
            'format' => ['for-time', 'amrap', 'emom', 'intervals', 'chipper', 'ygig', 'ladder', 'benchmark'],
            'equipment' => ['sled', 'wall-ball', 'sandbag', 'kettlebell', 'rower', 'ski-erg-machine', 'no-equipment'],
            'stations' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry', 'sandbag-lunges', 'wall-balls'],
            'environment' => ['gym', 'outdoor', 'small-space'],
            'intensity' => ['low', 'moderate', 'high'],
        ];

        foreach ($term_map as $taxonomy => $slugs) {
            foreach ($slugs as $slug) {
                if (! term_exists($slug, $taxonomy)) {
                    wp_insert_term(ucwords(str_replace('-', ' ', $slug)), $taxonomy, ['slug' => $slug]);
                }
            }
        }
    }
}
