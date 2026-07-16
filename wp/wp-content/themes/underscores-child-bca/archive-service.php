<?php

declare(strict_types=1);

/**
 * Archive: Service — list of all services.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-service');

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
</main>

<?php get_footer(); ?>
