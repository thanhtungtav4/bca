<?php

declare(strict_types=1);

/**
 * Archive: Research — list of all research articles.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-research');

get_header();
?>

<main id="main" <?php bca_main_class(); ?>>
    <section class="bca-section bca-archive-header">
        <div class="bca-section-inner">
            <h1 class="bca-section-heading bca-section-heading--center"><?php post_type_archive_title(); ?></h1>
        </div>
    </section>

    <section class="bca-section">
        <div class="bca-section-inner">
            <?php if (have_posts()): ?>
            <div class="bca-research-list">
                <?php $i = 0; while (have_posts()): the_post();
                    $i++;
                    $thumb_id = (int) get_post_thumbnail_id();
                    $eyebrow = function_exists('get_field') ? (get_field('eyebrow') ?: '') : '';
                    $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 30);
                    get_template_part('partials/components/card-research', null, [
                        'post_id'   => get_the_ID(),
                        'title'     => get_the_title(),
                        'permalink' => get_permalink(),
                        'image_id'  => $thumb_id,
                        'eyebrow'   => $eyebrow,
                        'excerpt'   => $excerpt,
                        'flip'      => ($i % 2) === 0,
                    ]);
                endwhile; ?>
            </div>

            <div class="bca-pagination">
                <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '←', 'next_text' => '→']); ?>
            </div>
            <?php else: ?>
                <p class="bca-empty"><?php esc_html_e('No research yet.', 'bca'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    // Shared CTA — use the home page's contact band so admin edits in one place.
    $shared_contact = function_exists('get_field') ? (get_field('contact_band_settings', get_option('page_on_front')) ?: []) : [];
    bca_render_section($shared_contact, 'partials/sections/contact-band', [
        'section_id' => 'research-contact',
    ]);
    ?>
</main>

<?php get_footer(); ?>
