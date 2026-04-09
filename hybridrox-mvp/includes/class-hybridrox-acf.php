<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_ACF
{
    public static function init(): void
    {
        add_action('acf/init', [__CLASS__, 'register_fields']);
    }

    public static function register_fields(): void
    {
        if (! function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key' => 'group_hybridrox_workout_data',
            'title' => 'Données de l\'entraînement',
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'seamless',
            'label_placement' => 'top',
            'instruction_placement' => 'field',
            'active' => true,
            'fields' => [
                ['key' => 'field_workout_tab_general', 'label' => 'Général', 'type' => 'tab'],
                ['key' => 'field_short_description', 'label' => 'Description courte', 'name' => 'short_description', 'type' => 'text', 'required' => 1],
                ['key' => 'field_featured', 'label' => 'Mis en avant', 'name' => 'featured', 'type' => 'true_false', 'ui' => 1],
                ['key' => 'field_duration', 'label' => 'Durée (minutes)', 'name' => 'duration', 'type' => 'number', 'required' => 1, 'min' => 1, 'step' => 1],

                ['key' => 'field_workout_tab_structure', 'label' => 'Structure', 'type' => 'tab'],
                ['key' => 'field_warmup', 'label' => 'Échauffement', 'name' => 'warmup', 'type' => 'textarea', 'rows' => 4],
                [
                    'key' => 'field_workout_blocks',
                    'label' => 'Blocs de travail',
                    'name' => 'workout_blocks',
                    'type' => 'repeater',
                    'layout' => 'row',
                    'button_label' => 'Ajouter un bloc',
                    'sub_fields' => [
                        ['key' => 'field_block_name', 'label' => 'Nom du bloc', 'name' => 'block_name', 'type' => 'text', 'required' => 1],
                        ['key' => 'field_block_type', 'label' => 'Type de bloc', 'name' => 'block_type', 'type' => 'text'],
                        ['key' => 'field_block_content', 'label' => 'Contenu du bloc', 'name' => 'block_content', 'type' => 'textarea', 'rows' => 4, 'required' => 1],
                    ],
                ],
                ['key' => 'field_cooldown', 'label' => 'Retour au calme', 'name' => 'cooldown', 'type' => 'textarea', 'rows' => 4],

                ['key' => 'field_workout_tab_conditions', 'label' => 'Contraintes', 'type' => 'tab'],
                ['key' => 'field_run_max_distance', 'label' => 'Distance max de course (mètres)', 'name' => 'run_max_distance', 'type' => 'number', 'min' => 0, 'step' => 50],

                ['key' => 'field_workout_tab_coaching', 'label' => 'Coaching', 'type' => 'tab'],
                ['key' => 'field_scaling_beginner', 'label' => 'Adaptation Débutant', 'name' => 'scaling_beginner', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_scaling_intermediate', 'label' => 'Adaptation Intermédiaire', 'name' => 'scaling_intermediate', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_scaling_advanced', 'label' => 'Adaptation Avancé', 'name' => 'scaling_advanced', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_tips', 'label' => 'Conseils coach', 'name' => 'tips', 'type' => 'textarea', 'rows' => 5],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'workout',
                    ],
                ],
            ],
        ]);
    }
}
