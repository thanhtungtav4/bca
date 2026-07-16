<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Projects teaser (Home) — list of CPT 'project' (flip rows).
 *
 * Args (from group_page_home.projects_settings):
 *   - heading     (string)
 *   - subheading  (string)
 *   - project_ids (array of int) — explicit picks; if empty, fallback to latest
 */

$heading = $args['heading'] ?? '';
$subheading = $args['subheading'] ?? '';
$project_ids = $args['project_ids'] ?? [];
if ($heading === '') {
    return;
}

$query_args = [
    'post_type'      => 'project',
    'posts_per_page' => 4,
    'orderby'        => empty($project_ids) ? 'date' : 'post__in',
    'order'          => 'DESC',
    'no_found_rows'  => true,
];
if (!empty($project_ids)) {
    $query_args['post__in'] = array_map('intval', (array) $project_ids);
    $query_args['orderby']  = 'post__in';
}

$query = new WP_Query($query_args);
?>
<section class="bca-section" id="home-projects">
    <div class="bca-section-inner">
        <div class="bca-teaser-head bca-teaser-head--center">
            <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
            <?php if ($subheading !== ''): ?>
                <p class="bca-section-sub bca-section-sub--center"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($query->have_posts()): ?>
        <div class="bca-projects-list">
            <?php $i = 0; while ($query->have_posts()) : $query->the_post();
                $post_id = get_the_ID();
                $flip = ($i % 2) === 1;
                $i++;
                $thumb_id = (int) get_post_thumbnail_id($post_id);
                $eyebrow = function_exists('get_field') ? (get_field('eyebrow', $post_id) ?: '') : '';
                $client  = function_exists('get_field') ? (get_field('client',  $post_id) ?: '') : '';
                $excerpt = has_excerpt($post_id) ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 30);
                get_template_part('partials/components/card-project', null, [
                    'post_id'   => $post_id,
                    'title'     => get_the_title(),
                    'permalink' => get_permalink(),
                    'image_id'  => $thumb_id,
                    'eyebrow'   => $eyebrow,
                    'client'    => $client,
                    'body'      => $excerpt,
                    'flip'      => $flip,
                ]);
            endwhile;
            wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>

        <div class="bca-teaser-foot bca-teaser-foot--center">
            <a class="bca-btn bca-btn--line" href="<?php echo esc_url(get_post_type_archive_link('project')); ?>">
                <?php esc_html_e('View all projects', 'bca'); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </div>
</section>
