<?php

declare(strict_types=1);

/**
 * Single: Project — case study detail.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-project');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $hero_image_id = (int) get_post_thumbnail_id();
    $eyebrow = function_exists('get_field') ? (get_field('eyebrow', $post_id) ?: '') : '';
    $client  = function_exists('get_field') ? (get_field('client',  $post_id) ?: '') : '';
    $challenge  = function_exists('get_field') ? (get_field('challenge',  $post_id) ?: '') : '';
    $approach   = function_exists('get_field') ? (get_field('approach',   $post_id) ?: '') : '';
    $outcome    = function_exists('get_field') ? (get_field('outcome',    $post_id) ?: '') : '';
    $related    = function_exists('get_field') ? (get_field('related_research', $post_id) ?: []) : [];
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--project">

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
                <?php if ($client): ?>
                    <span class="bca-hero-sub bca-hero-sub--on-dark"><?php echo esc_html($client); ?></span>
                <?php endif; ?>
            </div>
        </section>

        <section class="bca-section">
            <div class="bca-section-inner">
                <div class="bca-project-detail-body">
                    <?php if ($challenge): ?>
                        <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Challenge', 'bca'); ?></h2>
                        <p class="bca-project-detail-text"><?php echo esc_html($challenge); ?></p>
                    <?php endif; ?>

                    <?php if ($approach): ?>
                        <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Approach', 'bca'); ?></h2>
                        <p class="bca-project-detail-text"><?php echo esc_html($approach); ?></p>
                    <?php endif; ?>

                    <?php if ($outcome): ?>
                        <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Outcome', 'bca'); ?></h2>
                        <p class="bca-project-detail-text"><?php echo esc_html($outcome); ?></p>
                    <?php endif; ?>

                    <?php if (trim((string) get_the_content()) !== ''): ?>
                        <div class="bca-project-detail-content"><?php the_content(); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
        // Related research (admin picks via ACF relationship `related_research`).
        if (!empty($related) && is_array($related)) :
            $rel_q = new WP_Query([
                'post_type'      => 'research',
                'post__in'       => array_map('intval', $related),
                'orderby'        => 'post__in',
                'posts_per_page' => 3,
                'no_found_rows'  => true,
            ]);
            if ($rel_q->have_posts()) : ?>
            <section class="bca-section bca-project-related">
                <div class="bca-section-inner">
                    <h2 class="bca-section-heading"><?php esc_html_e('Related research', 'bca'); ?></h2>
                    <div class="bca-research-list">
                        <?php $i = 0; while ($rel_q->have_posts()) : $rel_q->the_post();
                            $i++;
                            $rid = get_the_ID();
                            $rthumb = (int) get_post_thumbnail_id($rid);
                            $reyebrow = function_exists('get_field') ? (get_field('eyebrow', $rid) ?: '') : '';
                            $rexcerpt = has_excerpt($rid) ? get_the_excerpt($rid) : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, $rid)), 30);
                            get_template_part('partials/components/card-research', null, [
                                'post_id'   => $rid,
                                'title'     => get_the_title(),
                                'permalink' => get_permalink(),
                                'image_id'  => $rthumb,
                                'eyebrow'   => $reyebrow,
                                'excerpt'   => $rexcerpt,
                                'flip'      => ($i % 2) === 0,
                            ]);
                        endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            </section>
            <?php endif; endif; ?>

    </article>

    <?php
    // Shared CTA — use the home page's contact band so admin edits in one place.
    $shared_contact = function_exists('get_field') ? (get_field('contact_band_settings', get_option('page_on_front')) ?: []) : [];
    bca_render_section($shared_contact, 'partials/sections/contact-band', [
        'section_id' => 'project-contact',
    ]);
    ?>
</main>

<?php
endwhile;
get_footer();
