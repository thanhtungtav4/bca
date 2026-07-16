<?php

declare(strict_types=1);

/**
 * Single: Service.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-service');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $hero_image_id = function_exists('get_field') ? (int) get_field('hero_image', $post_id) : 0;
    $items = function_exists('get_field') ? (get_field('items', $post_id) ?: []) : [];
    $related = function_exists('get_field') ? (get_field('related_projects', $post_id) ?: []) : [];
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--service">

        <section class="bca-hero bca-hero--page" data-has-image="<?php echo $hero_image_id ? 'true' : 'false'; ?>">
            <?php if ($hero_image_id): ?>
                <div class="bca-hero-bg" aria-hidden="true">
                    <?php echo wp_get_attachment_image($hero_image_id, 'full', false, ['class' => 'bca-hero-bg-img', 'loading' => 'eager', 'fetchpriority' => 'high']); ?>
                    <div class="bca-hero-scrim"></div>
                </div>
            <?php endif; ?>
            <div class="bca-hero-inner">
                <h1 class="bca-hero-heading"><?php the_title(); ?></h1>
            </div>
        </section>

        <section class="bca-section">
            <div class="bca-section-inner">
                <div class="bca-service-detail-grid">
                    <div class="bca-service-detail-body">
                        <?php if (has_excerpt()): ?>
                            <p class="bca-service-detail-excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <?php endif; ?>

                        <?php the_content(); ?>

                        <?php if (!empty($items)): ?>
                            <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('What we do', 'bca'); ?></h2>
                            <ul class="bca-service-card-items">
                                <?php foreach ($items as $item):
                                    $label = is_array($item) ? ($item['label'] ?? '') : (string) $item;
                                    if ($label === '') { continue; }
                                ?>
                                    <li class="bca-service-card-item">
                                        <span class="bca-service-card-bullet" aria-hidden="true">&mdash;</span>
                                        <span class="bca-service-card-item-label"><?php echo esc_html($label); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
        // Related services (admin picks via ACF relationship `related_projects` — now points to service CPT).
        if (!empty($related) && is_array($related)) :
            $related_q = new WP_Query([
                'post_type'      => 'service',
                'post__in'       => array_map('intval', $related),
                'orderby'        => 'post__in',
                'posts_per_page' => 3,
                'no_found_rows'  => true,
            ]);
            if ($related_q->have_posts()) : ?>
            <section class="bca-section bca-section--section">
                <div class="bca-section-inner">
                    <h2 class="bca-section-heading"><?php esc_html_e('Related services', 'bca'); ?></h2>
                    <div class="bca-services-grid">
                        <?php while ($related_q->have_posts()) : $related_q->the_post();
                            $related_id = get_the_ID();
                            $related_thumb = (int) get_post_thumbnail_id($related_id);
                            $related_items = function_exists('get_field') ? (get_field('items', $related_id) ?: []) : [];
                            get_template_part('partials/components/card-service', null, [
                                'post_id'   => $related_id,
                                'title'     => get_the_title(),
                                'permalink' => get_permalink(),
                                'image_id'  => $related_thumb,
                                'items'     => $related_items,
                            ]);
                        endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>
            <?php endif; endif; ?>

        <?php
        // Shared CTA — use the home page's contact band so admin edits in one place.
        $shared_contact = function_exists('get_field') ? (get_field('contact_band_settings', get_option('page_on_front')) ?: []) : [];
        bca_render_section($shared_contact, 'partials/sections/contact-band', [
            'section_id' => 'service-contact',
        ]);
        ?>

    </article>
</main>

<?php
endwhile;
get_footer();
