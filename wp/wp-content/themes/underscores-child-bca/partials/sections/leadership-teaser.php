<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Leadership teaser (Home) — grid of CPT 'leader'.
 *
 * Args (from group_page_home.leadership_settings):
 *   - heading     (string)
 *   - subheading  (string)
 *   - leader_ids  (array of int) — explicit picks; if empty, fallback to latest
 */

$heading = $args['heading'] ?? '';
$subheading = $args['subheading'] ?? '';
$leader_ids = $args['leader_ids'] ?? [];
if ($heading === '') {
    return;
}

$query_args = [
    'post_type'      => 'leader',
    'posts_per_page' => 4,
    'order'          => 'ASC',
    // sort by display_order but keep leaders that never set it (LEFT JOIN via OR)
    'meta_query'     => [
        'relation' => 'OR',
        'display_order' => ['key' => 'display_order', 'type' => 'NUMERIC', 'compare' => 'EXISTS'],
        'no_order'      => ['key' => 'display_order', 'compare' => 'NOT EXISTS'],
    ],
    'orderby'        => ['display_order' => 'ASC', 'menu_order' => 'ASC'],
    'no_found_rows'  => true,
];
if (!empty($leader_ids)) {
    $query_args['post__in'] = array_map('intval', (array) $leader_ids);
    $query_args['orderby']  = 'post__in';
    unset($query_args['meta_query']);
}

$query = new WP_Query($query_args);
?>
<section class="bca-section" id="home-leadership">
    <div class="bca-section-inner">
        <div class="bca-teaser-head bca-teaser-head--center">
            <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
            <?php if ($subheading !== ''): ?>
                <p class="bca-section-sub bca-section-sub--center"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($query->have_posts()): ?>
        <div class="bca-leaders-grid">
            <?php while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                $photo_id = (int) get_post_thumbnail_id($post_id);
                $role    = function_exists('get_field') ? (get_field('role', $post_id) ?: '') : '';
                get_template_part('partials/components/card-leader', null, [
                    'post_id'   => $post_id,
                    'name'      => get_the_title(),
                    'permalink' => get_permalink(),
                    'image_id'  => $photo_id,
                    'role'      => $role,
                ]);
            endwhile;
            wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <div class="bca-teaser-foot bca-teaser-foot--center">
            <a class="bca-btn bca-btn--line" href="<?php echo esc_url(get_post_type_archive_link('leader')); ?>">
                <?php esc_html_e('View all leaders', 'bca'); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>
