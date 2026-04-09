<?php
/**
 * Template Name: Hybridrox Liste Entraînements
 */

if (! defined('ABSPATH')) {
    exit;
}

get_header();
?>
<div class="hybridrox-container">
    <header class="hybridrox-page-header">
        <p class="hybridrox-kicker">Base d'entraînements Hybridrox</p>
        <h1><?php echo esc_html(get_the_title()); ?></h1>
        <p>Trouvez rapidement la séance adaptée à votre niveau, votre objectif, votre matériel et votre temps disponible.</p>
    </header>

    <section class="hybridrox-layout">
        <aside class="hybridrox-sidebar">
            <h2>Filtres</h2>
            <?php echo do_shortcode('[hybridrox_workout_filters]'); ?>
        </aside>

        <div class="hybridrox-results">
            <?php echo do_shortcode('[hybridrox_workout_grid]'); ?>
            <?php if (shortcode_exists('facetwp')) : ?>
                <div class="hybridrox-pager"><?php echo do_shortcode('[facetwp facet="pager"]'); ?></div>
            <?php endif; ?>
        </div>
    </section>
</div>
<?php
get_footer();
