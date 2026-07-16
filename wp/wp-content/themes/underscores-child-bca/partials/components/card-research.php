<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Research card — used in research archive (flip row layout).
 *
 * Args:
 *   - post_id   (int)
 *   - title     (string)
 *   - permalink (string)
 *   - image_id  (int)
 *   - eyebrow   (string) — VD: "FINTECH"
 *   - excerpt   (string)
 *   - flip      (bool)
 */

$post_id = (int) ($args['post_id'] ?? 0);
$title = $args['title'] ?? '';
$permalink = $args['permalink'] ?? '';
$image_id = (int) ($args['image_id'] ?? 0);
$eyebrow = $args['eyebrow'] ?? '';
$excerpt = $args['excerpt'] ?? '';
$flip      = (bool) ($args['flip'] ?? false);

if ($post_id <= 0 && $title === '') {
    return;
}
?>
<article class="bca-research-card <?php echo $flip ? 'bca-research-card--flip' : ''; ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($image_id): ?>
        <div class="bca-research-card-media">
            <a href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
                <?php echo wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="bca-research-card-text">
        <?php if ($eyebrow !== ''): ?>
            <span class="bca-eyebrow"><?php echo esc_html($eyebrow); ?></span>
        <?php endif; ?>

        <h2 class="bca-research-card-title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h2>

        <?php if ($excerpt !== ''): ?>
            <p class="bca-research-card-excerpt"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <a class="bca-btn bca-btn--ghost" href="<?php echo esc_url($permalink); ?>">
            <?php esc_html_e('Read more', 'bca'); ?>
            <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
        </a>
    </div>
</article>
