<?php

declare(strict_types=1);

/**
 * Single: Research — article detail.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-research');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $hero_image_id = function_exists('get_field') ? (int) get_field('hero_image', $post_id) : 0;
    if (!$hero_image_id) {
        $hero_image_id = (int) get_post_thumbnail_id();
    }
    $eyebrow = function_exists('get_field') ? (get_field('eyebrow', $post_id) ?: '') : '';
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--research">

        <section class="bca-hero bca-hero--page" data-has-image="<?php echo $hero_image_id ? 'true' : 'false'; ?>">
            <?php if ($hero_image_id): ?>
                <div class="bca-hero-bg" aria-hidden="true">
                    <?php echo wp_get_attachment_image($hero_image_id, 'full', false, ['class' => 'bca-hero-bg-img', 'loading' => 'eager', 'fetchpriority' => 'high']); ?>
                    <div class="bca-hero-scrim"></div>
                </div>
            <?php endif; ?>
            <div class="bca-hero-inner">
                <?php if ($eyebrow): ?>
                    <span class="bca-eyebrow bca-eyebrow--on-dark"><?php echo esc_html($eyebrow); ?></span>
                <?php endif; ?>
                <h1 class="bca-hero-heading"><?php the_title(); ?></h1>
            </div>
        </section>

        <section class="bca-section">
            <div class="bca-section-inner bca-research-body">
                <?php the_content(); ?>
            </div>
        </section>
    </article>
</main>

<?php
endwhile;
get_footer();
