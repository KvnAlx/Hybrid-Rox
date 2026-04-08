<?php
/**
 * Template Name: Hybridrox Workouts Listing
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<div class="hybridrox-container">
    <header class="hybridrox-page-header">
        <h1><?php echo esc_html(get_the_title()); ?></h1>
        <p>Filter Hyrox-style workouts by duration, format, goal, equipment, and training context.</p>
    </header>

    <?php echo do_shortcode('[hybridrox_workout_filters]'); ?>
    <?php echo do_shortcode('[hybridrox_workout_grid]'); ?>

    <?php if (shortcode_exists('facetwp')) : ?>
        <div class="hybridrox-pager"><?php echo do_shortcode('[facetwp facet="pager"]'); ?></div>
    <?php endif; ?>
</div>
<?php
get_footer();
