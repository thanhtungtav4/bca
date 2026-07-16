<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Career card — used in careers archive (row layout with Apply button).
 *
 * Args:
 *   - post_id          (int)
 *   - title            (string)
 *   - permalink        (string)
 *   - type             (string)
 *   - location         (string)
 *   - short_description(string)
 */

$post_id = (int) ($args['post_id'] ?? 0);
$title = $args['title'] ?? '';
$permalink = $args['permalink'] ?? '';
$type = $args['type'] ?? '';
$location = $args['location'] ?? '';
$desc = $args['desc'] ?? '';
if ($post_id <= 0 && $title === '') {
    return;
}
?>
<article class="bca-career-row" data-post-id="<?php echo esc_attr($post_id); ?>">
    <div class="bca-career-row-text">
        <h3 class="bca-career-row-title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h3>

        <div class="bca-career-row-meta">
            <?php if ($type !== ''): ?>
                <span class="bca-career-tag"><?php echo esc_html($type); ?></span>
            <?php endif; ?>
            <?php if ($location !== ''): ?>
                <span class="bca-career-tag"><?php echo esc_html($location); ?></span>
            <?php endif; ?>
        </div>

        <?php if ($desc !== ''): ?>
            <p class="bca-career-row-desc"><?php echo esc_html($desc); ?></p>
        <?php endif; ?>
    </div>

    <div class="bca-career-row-cta">
        <a class="bca-btn bca-btn--line" href="<?php echo esc_url($permalink); ?>">
            <?php esc_html_e('View role', 'bca'); ?>
            <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
        </a>
    </div>
</article>
