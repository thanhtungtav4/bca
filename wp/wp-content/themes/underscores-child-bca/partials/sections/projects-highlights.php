<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Project highlights — 1 big project (left) + 2 small projects stacked (right).
 *
 * Args (from $args via bca_render_section):
 *   - is_show           (bool)
 *   - heading           (string)
 *   - subheading        (string)
 *   - big_project_id    (int)
 *   - small_project_ids (array of int) — max 2
 *
 * Renders nothing if no big + small picked.
 */

$args = $args ?? [];

$is_show    = !empty($args['is_show']);
$heading    = $args['heading']           ?? '';
$subheading = $args['subheading']        ?? '';
// ACF relationship fields with max=1 still return an array — normalise.
$big_raw    = $args['big_project_id']    ?? 0;
$big_id     = is_array($big_raw) ? (int) ($big_raw[0] ?? 0) : (int) $big_raw;
$small_ids  = (array) ($args['small_project_ids'] ?? []);

if (!$is_show) {
    return;
}

// Render a project as a "big" card (large image + body).
$bca_render_highlight_card = function (int $post_id, bool $is_small): string {
    if ($post_id <= 0) {
        return '';
    }
    $title     = get_the_title($post_id);
    $permalink = get_permalink($post_id);
    $thumb_id  = (int) get_post_thumbnail_id($post_id);
    $eyebrow   = function_exists('get_field') ? (get_field('eyebrow', $post_id) ?: '') : '';
    $client    = function_exists('get_field') ? (get_field('client',  $post_id) ?: '') : '';
    $body      = has_excerpt($post_id) ? get_the_excerpt($post_id) : wp_trim_words(wp_strip_all_tags(get_the_content(null, false, $post_id)), 30);

    ob_start();
    ?>
    <article class="bca-highlight <?php echo $is_small ? 'bca-highlight--small' : 'bca-highlight--big'; ?>">
        <?php if ($thumb_id): ?>
            <a class="bca-highlight-media" href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
                <?php echo wp_get_attachment_image($thumb_id, $is_small ? 'medium_large' : 'large', false, ['loading' => 'lazy']); ?>
            </a>
        <?php endif; ?>
        <div class="bca-highlight-text">
            <?php if ($eyebrow): ?>
                <span class="bca-eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <?php endif; ?>
            <h3 class="bca-highlight-title">
                <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
            </h3>
            <?php if ($client): ?>
                <span class="bca-highlight-client"><?php echo esc_html($client); ?></span>
            <?php endif; ?>
            <?php if ($body): ?>
                <p class="bca-highlight-body"><?php echo esc_html($body); ?></p>
            <?php endif; ?>
            <a class="bca-btn bca-btn--ghost" href="<?php echo esc_url($permalink); ?>">
                <?php esc_html_e('Read case study', 'bca'); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>
    </article>
    <?php
    return (string) ob_get_clean();
};

$big_html   = $bca_render_highlight_card($big_id, false);
$small_1    = $bca_render_highlight_card((int) ($small_ids[0] ?? 0), true);
$small_2    = $bca_render_highlight_card((int) ($small_ids[1] ?? 0), true);

if ($big_html === '' && $small_1 === '' && $small_2 === '') {
    return;
}
?>
<section class="bca-section bca-highlights" id="home-highlights">
    <div class="bca-section-inner">
        <?php if ($heading !== '' || $subheading !== ''): ?>
            <div class="bca-teaser-head bca-teaser-head--center">
                <?php if ($heading !== ''): ?>
                    <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>
                <?php if ($subheading !== ''): ?>
                    <p class="bca-section-sub bca-section-sub--center"><?php echo esc_html($subheading); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="bca-highlights-grid">
            <div class="bca-highlights-col bca-highlights-col--big">
                <?php echo $big_html; ?>
            </div>
            <div class="bca-highlights-col bca-highlights-col--small">
                <?php echo $small_1; ?>
                <?php echo $small_2; ?>
            </div>
        </div>
    </div>
</section>
