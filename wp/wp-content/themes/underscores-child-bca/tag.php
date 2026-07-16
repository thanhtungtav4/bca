<?php

declare(strict_types=1);

/**
 * Tag archive — news filtered by tag.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-tag');

get_header();
?>

<main id="main" <?php bca_main_class(); ?>>
    <section class="bca-section bca-archive-header">
        <div class="bca-section-inner">
            <h1 class="bca-section-heading bca-section-heading--center">
                <?php printf(esc_html__('Tag: %s', 'bca'), single_tag_title('', false)); ?>
            </h1>
        </div>
    </section>

    <section class="bca-section">
        <div class="bca-section-inner">
            <?php if (have_posts()): ?>
            <div class="bca-news-grid">
                <?php while (have_posts()): the_post();
                    $cats = get_the_category();
                    $cat_name = !empty($cats) ? $cats[0]->name : '';
                    $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 30);
                    get_template_part('partials/components/card-news', null, [
                        'post_id'   => get_the_ID(),
                        'title'     => get_the_title(),
                        'permalink' => get_permalink(),
                        'image_id'  => (int) get_post_thumbnail_id(),
                        'date'      => get_the_date('j M Y'),
                        'category'  => $cat_name,
                        'excerpt'   => $excerpt,
                    ]);
                endwhile; ?>
            </div>

            <div class="bca-pagination">
                <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '←', 'next_text' => '→']); ?>
            </div>
            <?php else: ?>
                <p class="bca-empty"><?php esc_html_e('No posts with this tag yet.', 'bca'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
