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
                'title' => 'Constructeur d\'Engine 45',
                'content' => 'Séance aérobie orientée rythme de course avec transitions contrôlées.',
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
                    'short_description' => 'Intervalles aérobie avec transitions machine et gestion du rythme.',
                    'featured' => 1,
                    'duration' => 45,
                    'warmup' => "8 min footing léger\n2 tours : 10 squats, 10 fentes, 20s gainage",
                    'workout_blocks' => [
                        ['block_name' => 'Bloc principal', 'block_type' => 'Intervalles', 'block_content' => '4 tours : 800m run, 1000m ski erg, 20 wall balls. Repos 2:00.'],
                    ],
                    'cooldown' => '5-8 min rameur léger + mobilité hanches.',
                    'run_max_distance' => 800,
                    'scaling_beginner' => 'Passer à 3 tours et 600m de course.',
                    'scaling_intermediate' => 'Conserver 4 tours et réduire le ski à 800m.',
                    'scaling_advanced' => 'Réduire le repos à 60 secondes.',
                    'tips' => 'Rester relâché sur la respiration et accélérer les transitions.',
                ],
            ],
            [
                'title' => 'Sprint Traîneau Puissance',
                'content' => 'Intervalles puissance avec traîneau lourd et runs courts rapides.',
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
                    'short_description' => 'Travail traîneau lourd avec buy-in course.',
                    'featured' => 1,
                    'duration' => 30,
                    'warmup' => '10 min dynamique + préparation poussée traîneau.',
                    'workout_blocks' => [
                        ['block_name' => 'Effort course', 'block_type' => 'For Time', 'block_content' => '3 tours : 400m run, 50m sled push lourd, 50m sled pull lourd, 20 burpee broad jumps.'],
                    ],
                    'cooldown' => '5 min marche + étirements bas du corps.',
                    'run_max_distance' => 400,
                    'scaling_beginner' => 'Réduire la charge du traîneau et passer à 200m run.',
                    'scaling_intermediate' => 'Réduire les burpees à 12 reps.',
                    'scaling_advanced' => 'Augmenter la charge et garder les runs < 2:00.',
                    'tips' => 'Pousser avec un buste gainé et une foulée courte puissante.',
                ],
            ],
            [
                'title' => 'EMOM Petit Espace 24',
                'content' => 'Conditioning sans machine pour la maison ou espaces réduits.',
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
                    'short_description' => 'EMOM technique au poids de corps avec option sandbag.',
                    'featured' => 0,
                    'duration' => 24,
                    'warmup' => '5 min marche active + mobilité globale.',
                    'workout_blocks' => [
                        ['block_name' => 'EMOM x 24', 'block_type' => 'EMOM', 'block_content' => 'Min 1: 12 squats, Min 2: 8 burpees, Min 3: 20m sandbag lunges, Min 4: repos.'],
                    ],
                    'cooldown' => 'Respiration + mobilité ischios.',
                    'run_max_distance' => 0,
                    'scaling_beginner' => 'Burpees sur support élevé + fentes sans charge.',
                    'scaling_intermediate' => 'Passer à 15 squats par minute 1.',
                    'scaling_advanced' => 'Remplacer le repos par 100m shuttle.',
                    'tips' => 'Prioriser la qualité des mouvements.',
                ],
            ],
            [
                'title' => 'Chipper Rameur + Carry',
                'content' => 'Format long combinant endurance rameur et grip sur carries.',
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
                    'short_description' => 'Chipper continu avec gestion de la fatigue de préhension.',
                    'featured' => 0,
                    'duration' => 38,
                    'warmup' => '8 min rameur progressif + activation épaules.',
                    'workout_blocks' => [
                        ['block_name' => 'Chipper', 'block_type' => 'For Time', 'block_content' => '2000m row, 200m farmers carry, 80 wall balls, 1000m row, 100m carry, 40 wall balls.'],
                    ],
                    'cooldown' => 'Relâchement avant-bras + mobilité thoracique.',
                    'run_max_distance' => 0,
                    'scaling_beginner' => 'Réduire les distances de rameur de 25% et wall balls 50/25.',
                    'scaling_intermediate' => 'Alléger les kettlebells.',
                    'scaling_advanced' => 'Wall balls unbroken + charge plus lourde.',
                    'tips' => 'Secouer les mains 3-4 secondes avant chaque carry.',
                ],
            ],
            [
                'title' => 'Benchmark HYBRID 60',
                'content' => 'Simulation complète pour suivre la progression spécifique HYROX.',
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
                    'short_description' => 'Simulation de course à refaire toutes les 6 à 8 semaines.',
                    'featured' => 1,
                    'duration' => 60,
                    'warmup' => '12 min progressif avec 3 x 100m accélérations.',
                    'workout_blocks' => [
                        ['block_name' => 'Simulation', 'block_type' => 'For Time', 'block_content' => '8 x 1km run avec l\'ordre standard des stations HYROX entre les runs.'],
                    ],
                    'cooldown' => '10 min vélo léger + mobilité complète bas du corps.',
                    'run_max_distance' => 1000,
                    'scaling_beginner' => 'Réduire les runs à 600m et les charges à 60%.',
                    'scaling_intermediate' => 'Runs à 800m avec standards maintenus.',
                    'scaling_advanced' => 'Standards complets et transitions < 20 sec.',
                    'tips' => 'Rester contrôlé sur la première moitié puis accélérer.',
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
