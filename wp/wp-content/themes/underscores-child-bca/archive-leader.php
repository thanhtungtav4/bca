<?php

declare(strict_types=1);

/**
 * Archive: Leader — list of leadership team.
 * Grouped by `leader_group` taxonomy (Management Team / Advisors).
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-leader');

get_header();

// Pre-fetch leaders grouped by leader_group.
$groups = get_terms([
    'taxonomy'   => 'leader_group',
    'hide_empty' => false,
    'orderby'    => 'term_order',
    'order'      => 'ASC',
]);
?>

<main id="main" <?php bca_main_class(); ?>>
    <section class="bca-section bca-archive-header">
        <div class="bca-section-inner">
            <h1 class="bca-section-heading bca-section-heading--center"><?php post_type_archive_title(); ?></h1>
        </div>
    </section>

    <section class="bca-section">
        <div class="bca-section-inner">
            <?php
            if (!empty($groups) && !is_wp_error($groups)) :
                foreach ($groups as $group) :
                    $leaders = new WP_Query([
                        'post_type'      => 'leader',
                        'posts_per_page' => -1,
                        'orderby'        => 'meta_value_num',
                        'meta_key'       => 'display_order',
                        'order'          => 'ASC',
                        'tax_query'      => [[
                            'taxonomy' => 'leader_group',
                            'field'    => 'term_id',
                            'terms'    => $group->term_id,
                        ]],
                        'no_found_rows' => true,
                    ]);
                    if (!$leaders->have_posts()) { continue; }
            ?>
                <div class="bca-leader-group">
                    <h2 class="bca-section-heading bca-section-heading--sm"><?php echo esc_html($group->name); ?></h2>
                    <div class="bca-leaders-grid">
                        <?php while ($leaders->have_posts()) : $leaders->the_post();
                            $photo_id = (int) get_post_thumbnail_id();
                            $role     = function_exists('get_field') ? (get_field('role') ?: '') : '';
                            get_template_part('partials/components/card-leader', null, [
                                'post_id'   => get_the_ID(),
                                'name'      => get_the_title(),
                                'permalink' => get_permalink(),
                                'image_id'  => $photo_id,
                                'role'      => $role,
                            ]);
                        endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>
            <?php
                endforeach;
            else :
            ?>
                <p class="bca-empty"><?php esc_html_e('No leaders yet.', 'bca'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    // Shared CTA — use the home page's contact band so admin edits in one place.
    $shared_contact = function_exists('get_field') ? (get_field('contact_band_settings', get_option('page_on_front')) ?: []) : [];
    bca_render_section($shared_contact, 'partials/sections/contact-band', [
        'section_id' => 'leadership-contact',
    ]);
    ?>
</main>

<?php get_footer(); ?>
