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
            'title' => 'Workout Data',
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
            'fields' => [
                ['key' => 'field_workout_tab_general', 'label' => 'General', 'type' => 'tab'],
                ['key' => 'field_short_description', 'label' => 'Short Description', 'name' => 'short_description', 'type' => 'text', 'required' => 1],
                ['key' => 'field_featured', 'label' => 'Featured', 'name' => 'featured', 'type' => 'true_false', 'ui' => 1],
                ['key' => 'field_duration', 'label' => 'Duration (minutes)', 'name' => 'duration', 'type' => 'number', 'required' => 1, 'min' => 1, 'step' => 1],

                ['key' => 'field_workout_tab_structure', 'label' => 'Structure', 'type' => 'tab'],
                ['key' => 'field_warmup', 'label' => 'Warmup', 'name' => 'warmup', 'type' => 'textarea', 'rows' => 4],
                [
                    'key' => 'field_workout_blocks',
                    'label' => 'Workout Blocks',
                    'name' => 'workout_blocks',
                    'type' => 'repeater',
                    'layout' => 'block',
                    'button_label' => 'Add Block',
                    'sub_fields' => [
                        ['key' => 'field_block_name', 'label' => 'Block Name', 'name' => 'block_name', 'type' => 'text', 'required' => 1],
                        ['key' => 'field_block_type', 'label' => 'Block Type', 'name' => 'block_type', 'type' => 'text'],
                        ['key' => 'field_block_content', 'label' => 'Block Content', 'name' => 'block_content', 'type' => 'textarea', 'rows' => 4, 'required' => 1],
                    ],
                ],
                ['key' => 'field_cooldown', 'label' => 'Cooldown', 'name' => 'cooldown', 'type' => 'textarea', 'rows' => 4],

                ['key' => 'field_workout_tab_conditions', 'label' => 'Conditions', 'type' => 'tab'],
                ['key' => 'field_run_max_distance', 'label' => 'Run Max Distance (meters)', 'name' => 'run_max_distance', 'type' => 'number', 'min' => 0, 'step' => 50],

                ['key' => 'field_workout_tab_coaching', 'label' => 'Coaching', 'type' => 'tab'],
                ['key' => 'field_scaling_beginner', 'label' => 'Scaling Beginner', 'name' => 'scaling_beginner', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_scaling_intermediate', 'label' => 'Scaling Intermediate', 'name' => 'scaling_intermediate', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_scaling_advanced', 'label' => 'Scaling Advanced', 'name' => 'scaling_advanced', 'type' => 'textarea', 'rows' => 4],
                ['key' => 'field_tips', 'label' => 'Tips', 'name' => 'tips', 'type' => 'textarea', 'rows' => 5],
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
