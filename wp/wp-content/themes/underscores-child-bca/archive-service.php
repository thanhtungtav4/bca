<?php

declare(strict_types=1);

/**
 * Archive: Service — list of all services.
 *
 * Pulls hero from page id 53 (Services overview page) since the CPT
 * archive URL shadows the page at /services/.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-service');

get_header();

// Pull hero data from the Services overview page (id 53) so the same
// content the user authored appears here too.
$hero_settings         = function_exists('get_field') ? (get_field('hero_settings', 53) ?: []) : [];
$contact_band_settings = function_exists('get_field') ? (get_field('contact_band_settings', 53) ?: []) : [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'services-hero',
        'variant'    => 'page',
    ]);
    ?>

    <section class="bca-section" id="services-list">
        <div class="bca-section-inner">
            <?php if (have_posts()): ?>
            <div class="bca-services-grid">
                <?php while (have_posts()): the_post();
                    $post_id = get_the_ID();
                    $thumb_id = (int) get_post_thumbnail_id($post_id);
                    $items = function_exists('get_field') ? (get_field('items', $post_id) ?: []) : [];
                    get_template_part('partials/components/card-service', null, [
                        'post_id'   => $post_id,
                        'title'     => get_the_title(),
                        'permalink' => get_permalink(),
                        'image_id'  => $thumb_id,
                        'items'     => $items,
                    ]);
                endwhile; ?>
            </div>

            <div class="bca-pagination">
                <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '←', 'next_text' => '→']); ?>
            </div>
            <?php else: ?>
                <p class="bca-empty"><?php esc_html_e('No services yet.', 'bca'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    bca_render_section($contact_band_settings, 'partials/sections/contact-band', [
        'section_id' => 'services-contact',
    ]);
    ?>

</main>

<?php get_footer(); ?>
