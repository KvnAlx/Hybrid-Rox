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
                'add_new_item' => __('Add New Workout', 'hybridrox-mvp'),
                'edit_item' => __('Edit Workout', 'hybridrox-mvp'),
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
        $taxonomies = [
            'level' => ['Beginner', 'Intermediate', 'Advanced'],
            'goal' => ['Endurance', 'Strength', 'Power', 'Engine', 'Technique', 'Recovery'],
            'format' => ['For Time', 'AMRAP', 'EMOM', 'Intervals', 'Chipper', 'YGIG', 'Ladder', 'Benchmark'],
            'equipment' => ['Sled', 'Wall Ball', 'Sandbag', 'Kettlebell', 'Rower', 'Ski Erg Machine', 'No Equipment'],
            'stations' => ['Run', 'Ski Erg', 'Sled Push', 'Sled Pull', 'Burpee Broad Jump', 'Row', 'Farmers Carry', 'Sandbag Lunges', 'Wall Balls'],
            'environment' => ['Gym', 'Outdoor', 'Small Space'],
            'intensity' => ['Low', 'Moderate', 'High'],
        ];

        foreach (array_keys($taxonomies) as $taxonomy) {
            register_taxonomy($taxonomy, ['workout'], [
                'labels' => [
                    'name' => ucfirst($taxonomy),
                    'singular_name' => ucfirst($taxonomy),
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
