<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Project card — used in projects archive + home teaser (flip row layout).
 *
 * Args:
 *   - post_id   (int)
 *   - title     (string)
 *   - permalink (string)
 *   - image_id  (int)
 *   - eyebrow   (string) — VD: "FINTECH PROJECTS"
 *   - client    (string) — VD: "MM TECHNOLOGY"
 *   - body      (string)
 *   - flip      (bool)   — alternate image/text sides
 */

$post_id = (int) ($args['post_id'] ?? 0);
$title = $args['title'] ?? '';
$permalink = $args['permalink'] ?? '';
$image_id = (int) ($args['image_id'] ?? 0);
$eyebrow = $args['eyebrow'] ?? '';
$client = $args['client'] ?? '';
$body = $args['body'] ?? '';
$flip      = (bool) ($args['flip'] ?? false);

if ($post_id <= 0 && $title === '') {
    return;
}
?>
<article class="bca-project-card <?php echo $flip ? 'bca-project-card--flip' : ''; ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($image_id): ?>
        <div class="bca-project-card-media">
            <a href="<?php echo esc_url($permalink); ?>" tabindex="-1" aria-hidden="true">
                <?php echo wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="bca-project-card-text">
        <?php if ($eyebrow !== ''): ?>
            <span class="bca-eyebrow"><?php echo esc_html($eyebrow); ?></span>
        <?php endif; ?>

        <h2 class="bca-project-card-title">
            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
        </h2>

        <?php if ($client !== ''): ?>
            <span class="bca-project-card-client"><?php echo esc_html($client); ?></span>
        <?php endif; ?>

        <?php if ($body !== ''): ?>
            <p class="bca-project-card-body"><?php echo esc_html($body); ?></p>
        <?php endif; ?>

        <a class="bca-btn bca-btn--ghost" href="<?php echo esc_url($permalink); ?>">
            <?php esc_html_e('Read case study', 'bca'); ?>
            <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
        </a>
    </div>
</article>
