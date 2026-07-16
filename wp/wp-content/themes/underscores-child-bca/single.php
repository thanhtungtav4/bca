<?php

declare(strict_types=1);

/**
 * Single post (post core = news).
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-post');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $hero_image_id = (int) get_post_thumbnail_id();
    $cats = get_the_category();
    $cat_name = !empty($cats) ? $cats[0]->name : '';
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--post">

        <section class="bca-hero bca-hero--page" data-has-image="<?php echo $hero_image_id ? 'true' : 'false'; ?>">
            <?php if ($hero_image_id): ?>
                <div class="bca-hero-bg" aria-hidden="true">
                    <?php echo wp_get_attachment_image($hero_image_id, 'full', false, ['class' => 'bca-hero-bg-img', 'loading' => 'eager', 'fetchpriority' => 'high']); ?>
                    <div class="bca-hero-scrim"></div>
                </div>
            <?php endif; ?>
            <div class="bca-hero-inner">
                <?php if ($cat_name): ?>
                    <span class="bca-eyebrow bca-eyebrow--on-dark"><?php echo esc_html($cat_name); ?></span>
                <?php endif; ?>
                <h1 class="bca-hero-heading"><?php the_title(); ?></h1>
                <p class="bca-hero-sub"><?php echo esc_html(get_the_date('j M Y')); ?></p>
            </div>
        </section>

        <section class="bca-section">
            <div class="bca-section-inner bca-news-body">
                <?php the_content(); ?>
            </div>
        </section>
    </article>
</main>

<?php
endwhile;
get_footer();
