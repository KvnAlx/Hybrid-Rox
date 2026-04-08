<?php

if (! defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();
    $post_id = get_the_ID();
    $short_description = get_field('short_description', $post_id);
    $duration = get_field('duration', $post_id);
    $warmup = get_field('warmup', $post_id);
    $workout_blocks = get_field('workout_blocks', $post_id);
    $cooldown = get_field('cooldown', $post_id);
    $tips = get_field('tips', $post_id);
    $badges = [
        'level' => wp_get_post_terms($post_id, 'level', ['fields' => 'names']),
        'goal' => wp_get_post_terms($post_id, 'goal', ['fields' => 'names']),
        'format' => wp_get_post_terms($post_id, 'format', ['fields' => 'names']),
        'intensity' => wp_get_post_terms($post_id, 'intensity', ['fields' => 'names']),
    ];
    ?>
    <article class="hybridrox-single">
        <header>
            <h1><?php the_title(); ?></h1>
            <div class="hybridrox-badges">
                <span class="hybridrox-badge">Duration: <?php echo esc_html((string) $duration); ?> min</span>
                <?php foreach ($badges as $label => $values) : ?>
                    <?php foreach ($values as $value) : ?>
                        <span class="hybridrox-badge"><?php echo esc_html(ucfirst($label) . ': ' . $value); ?></span>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php if ($short_description) : ?><p class="hybridrox-lead"><?php echo esc_html($short_description); ?></p><?php endif; ?>
        </header>

        <section>
            <h2>Workout Structure</h2>
            <?php if ($warmup) : ?><h3>Warmup</h3><p><?php echo nl2br(esc_html($warmup)); ?></p><?php endif; ?>
            <?php if (! empty($workout_blocks)) : ?>
                <h3>Blocks</h3>
                <?php foreach ($workout_blocks as $block) : ?>
                    <div class="hybridrox-block">
                        <h4><?php echo esc_html($block['block_name'] ?? 'Block'); ?><?php echo ! empty($block['block_type']) ? ' (' . esc_html($block['block_type']) . ')' : ''; ?></h4>
                        <p><?php echo nl2br(esc_html($block['block_content'] ?? '')); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if ($cooldown) : ?><h3>Cooldown</h3><p><?php echo nl2br(esc_html($cooldown)); ?></p><?php endif; ?>
        </section>

        <section>
            <h2>Scaling</h2>
            <p><strong>Beginner:</strong> <?php echo nl2br(esc_html((string) get_field('scaling_beginner', $post_id))); ?></p>
            <p><strong>Intermediate:</strong> <?php echo nl2br(esc_html((string) get_field('scaling_intermediate', $post_id))); ?></p>
            <p><strong>Advanced:</strong> <?php echo nl2br(esc_html((string) get_field('scaling_advanced', $post_id))); ?></p>
        </section>

        <?php if ($tips) : ?>
            <section>
                <h2>Coaching Tips</h2>
                <p><?php echo nl2br(esc_html($tips)); ?></p>
            </section>
        <?php endif; ?>
    </article>
    <?php
endwhile;

get_footer();
