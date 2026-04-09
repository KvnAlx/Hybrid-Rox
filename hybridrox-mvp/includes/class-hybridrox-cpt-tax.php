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
                'name' => __('Entraînements', 'hybridrox-mvp'),
                'singular_name' => __('Entraînement', 'hybridrox-mvp'),
                'add_new' => __('Ajouter', 'hybridrox-mvp'),
                'add_new_item' => __('Ajouter un entraînement', 'hybridrox-mvp'),
                'edit_item' => __('Modifier l\'entraînement', 'hybridrox-mvp'),
                'new_item' => __('Nouvel entraînement', 'hybridrox-mvp'),
                'view_item' => __('Voir l\'entraînement', 'hybridrox-mvp'),
                'search_items' => __('Rechercher des entraînements', 'hybridrox-mvp'),
                'not_found' => __('Aucun entraînement trouvé', 'hybridrox-mvp'),
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
            'level' => ['beginner' => 'Débutant', 'intermediate' => 'Intermédiaire', 'advanced' => 'Avancé'],
            'goal' => ['endurance' => 'Endurance', 'strength' => 'Force', 'power' => 'Puissance', 'engine' => 'Engine', 'technique' => 'Technique', 'recovery' => 'Récupération'],
            'format' => ['for-time' => 'Au chrono', 'amrap' => 'AMRAP', 'emom' => 'EMOM', 'intervals' => 'Intervalles', 'chipper' => 'Chipper', 'ygig' => 'YGIG', 'ladder' => 'Échelle', 'benchmark' => 'Benchmark'],
            'equipment' => ['sled' => 'Traîneau', 'wall-ball' => 'Ballon mural', 'sandbag' => 'Sac de sable', 'kettlebell' => 'Kettlebell', 'rower' => 'Rameur', 'ski-erg-machine' => 'Ski Erg', 'no-equipment' => 'Sans équipement'],
            'stations' => ['run' => 'Course', 'ski-erg' => 'Ski Erg', 'sled-push' => 'Poussée traîneau', 'sled-pull' => 'Tirage traîneau', 'burpee-broad-jump' => 'Burpee broad jump', 'row' => 'Rameur', 'farmers-carry' => 'Farmer carry', 'sandbag-lunges' => 'Fentes sac de sable', 'wall-balls' => 'Ballons muraux'],
            'environment' => ['gym' => 'Salle', 'outdoor' => 'Extérieur', 'small-space' => 'Petit espace'],
            'intensity' => ['low' => 'Faible', 'moderate' => 'Modérée', 'high' => 'Élevée'],
        ];

        foreach ($term_map as $taxonomy => $terms) {
            foreach ($terms as $slug => $label) {
                $existing = term_exists($slug, $taxonomy);
                if (! $existing) {
                    wp_insert_term($label, $taxonomy, ['slug' => $slug]);
                    continue;
                }

                $term_id = is_array($existing) ? (int) $existing['term_id'] : (int) $existing;
                if ($term_id > 0) {
                    wp_update_term($term_id, $taxonomy, ['name' => $label]);
                }
            }
        }
    }
}
