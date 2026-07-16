<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * News card — used in news list (post core), category, tag.
 *
 * Args:
 *   - post_id    (int)
 *   - title      (string)
 *   - permalink  (string)
 *   - image_id   (int)
 *   - date       (string) — pre-formatted date label
 *   - category   (string) — eyebrow (first category name)
 *   - excerpt    (string)
 */

$post_id = (int) ($args['post_id'] ?? 0);
$title = $args['title'] ?? '';
$permalink = $args['permalink'] ?? '';
$image_id = (int) ($args['image_id'] ?? 0);
$date = $args['date'] ?? '';
$category = $args['category'] ?? '';
$excerpt = $args['excerpt'] ?? '';
if ($post_id <= 0 && $title === '') {
    return;
}
?>
<a class="bca-news-card" href="<?php echo esc_url($permalink); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($image_id): ?>
        <div class="bca-news-card-img">
            <?php echo wp_get_attachment_image($image_id, 'medium_large', false, ['loading' => 'lazy']); ?>
        </div>
    <?php endif; ?>

    <div class="bca-news-card-meta">
        <?php if ($category !== ''): ?>
            <span class="bca-news-card-cat"><?php echo esc_html($category); ?></span>
        <?php endif; ?>
        <?php if ($date !== ''): ?>
            <span class="bca-news-card-date"><?php echo esc_html($date); ?></span>
        <?php endif; ?>
    </div>

    <?php if ($title !== ''): ?>
        <h3 class="bca-news-card-title"><?php echo esc_html($title); ?></h3>
    <?php endif; ?>

    <?php if ($excerpt !== ''): ?>
        <p class="bca-news-card-excerpt"><?php echo esc_html($excerpt); ?></p>
    <?php endif; ?>
</a>
