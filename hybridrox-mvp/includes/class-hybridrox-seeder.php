<?php

if (! defined('ABSPATH')) {
    exit;
}

class Hybridrox_Seeder
{
    public static function seed_workouts(): void
    {
        if (get_option('hybridrox_seeded_workouts')) {
            return;
        }

        $workouts = [
            [
                'title' => 'Hybrid Engine Builder 45',
                'content' => 'A race-pace aerobic builder focused on smooth transitions and controlled effort.',
                'tax' => [
                    'level' => ['intermediate'],
                    'goal' => ['engine', 'endurance'],
                    'format' => ['intervals'],
                    'equipment' => ['ski-erg-machine', 'rower', 'wall-ball'],
                    'stations' => ['run', 'ski-erg', 'row', 'wall-balls'],
                    'environment' => ['gym'],
                    'intensity' => ['moderate'],
                ],
                'acf' => [
                    'short_description' => 'Aerobic intervals with machine transitions and wall-ball pacing.',
                    'featured' => 1,
                    'duration' => 45,
                    'warmup' => "8 min easy jog\n2 rounds: 10 air squats, 10 lunges, 20s plank",
                    'workout_blocks' => [
                        ['block_name' => 'Main Set', 'block_type' => 'Intervals', 'block_content' => '4 rounds: 800m run, 1000m ski erg, 20 wall balls. Rest 2:00 between rounds.'],
                    ],
                    'cooldown' => '5-8 min easy row and hip mobility.',
                    'run_max_distance' => 800,
                    'scaling_beginner' => 'Reduce to 3 rounds and 600m runs.',
                    'scaling_intermediate' => 'Keep rounds but reduce ski to 800m.',
                    'scaling_advanced' => 'Shorten rest to 60 seconds and hold race pace.',
                    'tips' => 'Focus on breathing rhythm and fast but composed transitions.',
                ],
            ],
            [
                'title' => 'Sled Grind Sprint',
                'content' => 'Power-focused intervals mixing heavy sled work and short fast runs.',
                'tax' => [
                    'level' => ['advanced'],
                    'goal' => ['power', 'strength'],
                    'format' => ['for-time'],
                    'equipment' => ['sled'],
                    'stations' => ['sled-push', 'sled-pull', 'run'],
                    'environment' => ['gym'],
                    'intensity' => ['high'],
                ],
                'acf' => [
                    'short_description' => 'Heavy sled rounds with run buy-ins.',
                    'featured' => 1,
                    'duration' => 30,
                    'warmup' => '10 min dynamic warmup including banded glute work and prowler prep.',
                    'workout_blocks' => [
                        ['block_name' => 'Race Effort', 'block_type' => 'For Time', 'block_content' => '3 rounds: 400m run, 50m sled push heavy, 50m sled pull heavy, 20 burpee broad jumps.'],
                    ],
                    'cooldown' => '5 min walk and lower-body stretching.',
                    'run_max_distance' => 400,
                    'scaling_beginner' => 'Use moderate sled load and 200m run.',
                    'scaling_intermediate' => 'Drop burpees to 12 reps each round.',
                    'scaling_advanced' => 'Add weight and keep all runs under 2:00.',
                    'tips' => 'Drive through mid-foot, keep chest tall on push and pull.',
                ],
            ],
            [
                'title' => 'Minimal Space EMOM 24',
                'content' => 'No-machine conditioning perfect for home or tight training spaces.',
                'tax' => [
                    'level' => ['beginner'],
                    'goal' => ['recovery', 'technique'],
                    'format' => ['emom'],
                    'equipment' => ['no-equipment', 'sandbag'],
                    'stations' => ['sandbag-lunges', 'burpee-broad-jump'],
                    'environment' => ['small-space', 'outdoor'],
                    'intensity' => ['low'],
                ],
                'acf' => [
                    'short_description' => 'Technique-driven EMOM using bodyweight and optional sandbag.',
                    'featured' => 0,
                    'duration' => 24,
                    'warmup' => '5 min brisk walk + mobility flow.',
                    'workout_blocks' => [
                        ['block_name' => 'EMOM x 24', 'block_type' => 'EMOM', 'block_content' => 'Min 1: 12 air squats, Min 2: 8 burpees, Min 3: 20m sandbag lunges, Min 4: rest.'],
                    ],
                    'cooldown' => 'Breathing reset and hamstring stretches.',
                    'run_max_distance' => 0,
                    'scaling_beginner' => 'Use incline burpees and bodyweight lunges.',
                    'scaling_intermediate' => 'Increase squat reps to 15.',
                    'scaling_advanced' => 'Replace rest minute with 100m shuttle.',
                    'tips' => 'Keep movement quality over speed.',
                ],
            ],
            [
                'title' => 'Row + Carry Chipper',
                'content' => 'Long-form chipper combining rowing stamina and grip-intensive carries.',
                'tax' => [
                    'level' => ['intermediate'],
                    'goal' => ['endurance', 'strength'],
                    'format' => ['chipper'],
                    'equipment' => ['rower', 'kettlebell'],
                    'stations' => ['row', 'farmers-carry', 'wall-balls'],
                    'environment' => ['gym'],
                    'intensity' => ['moderate'],
                ],
                'acf' => [
                    'short_description' => 'Single descending effort with carry fatigue management.',
                    'featured' => 0,
                    'duration' => 38,
                    'warmup' => '8 min row build + shoulder prep.',
                    'workout_blocks' => [
                        ['block_name' => 'Chipper', 'block_type' => 'For Time', 'block_content' => '2000m row, 200m farmers carry, 80 wall balls, 1000m row, 100m carry, 40 wall balls.'],
                    ],
                    'cooldown' => 'Forearm release and thoracic mobility.',
                    'run_max_distance' => 0,
                    'scaling_beginner' => 'Reduce row distances by 25% and wall balls to 50/25.',
                    'scaling_intermediate' => 'Use lighter kettlebells and partition wall balls.',
                    'scaling_advanced' => 'Unbroken wall balls and heavier carry load.',
                    'tips' => 'Shake grip briefly before each carry segment.',
                ],
            ],
            [
                'title' => 'Benchmark HYBRID 60',
                'content' => 'Full benchmark simulation for race-specific progression tracking.',
                'tax' => [
                    'level' => ['advanced'],
                    'goal' => ['engine', 'power', 'technique'],
                    'format' => ['benchmark'],
                    'equipment' => ['ski-erg-machine', 'sled', 'rower', 'wall-ball', 'sandbag'],
                    'stations' => ['run', 'ski-erg', 'sled-push', 'sled-pull', 'burpee-broad-jump', 'row', 'farmers-carry', 'sandbag-lunges', 'wall-balls'],
                    'environment' => ['gym'],
                    'intensity' => ['high'],
                ],
                'acf' => [
                    'short_description' => 'Race simulation benchmark to repeat every 6-8 weeks.',
                    'featured' => 1,
                    'duration' => 60,
                    'warmup' => '12 min progressive warmup with 3 x 100m strides.',
                    'workout_blocks' => [
                        ['block_name' => 'Simulation', 'block_type' => 'For Time', 'block_content' => '8 x 1km run with standard HYROX station order between runs.'],
                    ],
                    'cooldown' => '10 min easy bike and full lower-body cooldown.',
                    'run_max_distance' => 1000,
                    'scaling_beginner' => 'Reduce each run to 600m and station loads to 60%.',
                    'scaling_intermediate' => 'Reduce runs to 800m and keep station standards.',
                    'scaling_advanced' => 'Maintain race standards and cap transitions at 20s.',
                    'tips' => 'Keep first half controlled; build across second half.',
                ],
            ],
        ];

        foreach ($workouts as $item) {
            $existing = get_page_by_title($item['title'], OBJECT, 'workout');
            if ($existing) {
                continue;
            }

            $post_id = wp_insert_post([
                'post_type' => 'workout',
                'post_status' => 'publish',
                'post_title' => $item['title'],
                'post_content' => $item['content'],
            ]);

            if (is_wp_error($post_id) || ! $post_id) {
                continue;
            }

            foreach ($item['tax'] as $taxonomy => $terms) {
                wp_set_object_terms($post_id, $terms, $taxonomy, false);
            }

            foreach ($item['acf'] as $field_name => $value) {
                update_field($field_name, $value, $post_id);
            }
        }

        update_option('hybridrox_seeded_workouts', 1);
    }
}
