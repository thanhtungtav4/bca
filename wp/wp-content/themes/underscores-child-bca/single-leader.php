<?php

declare(strict_types=1);

/**
 * Single: Leader — bio detail.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-leader');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $photo_id = (int) get_post_thumbnail_id();
    $role = function_exists('get_field') ? (get_field('role', $post_id) ?: '') : '';
    $credentials = function_exists('get_field') ? (get_field('credentials', $post_id) ?: '') : '';
    $related = function_exists('get_field') ? (get_field('related_leaders', $post_id) ?: []) : [];
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--leader">

        <section class="bca-section bca-single-leader-hero">
            <div class="bca-section-inner">
                <a class="bca-back-link" href="<?php echo esc_url(get_post_type_archive_link('leader')); ?>">&larr; <?php esc_html_e('Our Leadership', 'bca'); ?></a>

                <div class="bca-leader-hero-grid">
                    <div class="bca-leader-hero-text">
                        <h1 class="bca-leader-hero-name"><?php the_title(); ?></h1>
                        <?php if ($role): ?>
                            <span class="bca-leader-hero-role"><?php echo esc_html($role); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($photo_id): ?>
                        <div class="bca-leader-hero-img">
                            <?php echo wp_get_attachment_image($photo_id, 'medium_large', false, ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="bca-section">
            <div class="bca-section-inner bca-leader-body">
                <?php if ($credentials): ?>
                    <p class="bca-leader-credentials"><?php echo esc_html($credentials); ?></p>
                <?php endif; ?>
                <?php the_content(); ?>
            </div>
        </section>

        <?php
        // Other leaders — admin picks via ACF relationship, fallback to same-group leaders.
        $other_q_args = [
            'post_type'      => 'leader',
            'posts_per_page' => 4,
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'display_order',
            'order'          => 'ASC',
            'post__not_in'   => [$post_id],
            'no_found_rows'  => true,
        ];
        if (!empty($related) && is_array($related)) {
            $other_q_args['post__in']    = array_map('intval', $related);
            $other_q_args['orderby']     = 'post__in';
            $other_q_args['posts_per_page'] = 4;
            unset($other_q_args['meta_key'], $other_q_args['order']);
        } else {
            // Fallback: same leader_group taxonomy term
            $terms = get_the_terms($post_id, 'leader_group');
            if (!empty($terms) && !is_wp_error($terms)) {
                $other_q_args['tax_query'] = [[
                    'taxonomy' => 'leader_group',
                    'field'    => 'term_id',
                    'terms'    => $terms[0]->term_id,
                ]];
            }
        }
        $other_q = new WP_Query($other_q_args);
        if ($other_q->have_posts()) : ?>
        <section class="bca-section bca-leader-related">
            <div class="bca-section-inner">
                <h2 class="bca-section-heading"><?php esc_html_e('Other leaders', 'bca'); ?></h2>
                <div class="bca-leaders-grid">
                    <?php while ($other_q->have_posts()) : $other_q->the_post();
                        $oid = get_the_ID();
                        $ophoto = (int) get_post_thumbnail_id($oid);
                        $orole = function_exists('get_field') ? (get_field('role', $oid) ?: '') : '';
                        get_template_part('partials/components/card-leader', null, [
                            'post_id'   => $oid,
                            'name'      => get_the_title(),
                            'permalink' => get_permalink(),
                            'image_id'  => $ophoto,
                            'role'      => $orole,
                        ]);
                    endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

    </article>

    <?php
    // Shared CTA — use the home page's contact band so admin edits in one place.
    $shared_contact = function_exists('get_field') ? (get_field('contact_band_settings', get_option('page_on_front')) ?: []) : [];
    bca_render_section($shared_contact, 'partials/sections/contact-band', [
        'section_id' => 'leader-contact',
    ]);
    ?>
</main>

<?php
endwhile;
get_footer();
