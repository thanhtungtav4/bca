<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Services teaser (Home) — list of CPT 'service'.
 *
 * Args (from group_page_home.services_settings):
 *   - heading     (string)
 *   - subheading  (string)
 *   - service_ids (array of int) — explicit picks; if empty, fallback to latest
 */

$heading = $args['heading'] ?? '';
$subheading = $args['subheading'] ?? '';
$service_ids = $args['service_ids'] ?? [];
if ($heading === '') {
    return;
}

$query_args = [
    'post_type'      => 'service',
    'posts_per_page' => 6,
    'orderby'        => empty($service_ids) ? 'date' : 'post__in',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];
if (!empty($service_ids)) {
    $query_args['post__in'] = array_map('intval', (array) $service_ids);
    $query_args['orderby']  = 'post__in';
}

$query = new WP_Query($query_args);
?>
<section class="bca-section" id="home-services">
    <div class="bca-section-inner">
        <div class="bca-teaser-head bca-teaser-head--center">
            <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
            <?php if ($subheading !== ''): ?>
                <p class="bca-section-sub bca-section-sub--center"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($query->have_posts()): ?>
        <div class="bca-services-grid">
            <?php while ($query->have_posts()) : $query->the_post();
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
            endwhile;
            wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <div class="bca-teaser-foot bca-teaser-foot--center">
            <a class="bca-btn bca-btn--line" href="<?php echo esc_url(get_post_type_archive_link('service')); ?>">
                <?php esc_html_e('View all services', 'bca'); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>
