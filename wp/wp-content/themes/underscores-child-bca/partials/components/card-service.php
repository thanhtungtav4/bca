<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Service card — used in services archive + home teaser.
 *
 * Args:
 *   - post_id   (int)
 *   - title     (string)
 *   - permalink (string)
 *   - image_id  (int)
 *   - items     (array of {label})
 */

$post_id = (int) ($args['post_id'] ?? 0);
$title = $args['title'] ?? '';
$permalink = $args['permalink'] ?? '';
$image_id = (int) ($args['image_id'] ?? 0);
$items = $args['items'] ?? [];
if ($post_id <= 0 && $title === '') {
    return;
}
?>
<a class="bca-service-card" href="<?php echo esc_url($permalink); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($image_id): ?>
        <div class="bca-service-card-img">
            <?php echo wp_get_attachment_image($image_id, 'medium_large', false, ['loading' => 'lazy']); ?>
        </div>
    <?php endif; ?>

    <div class="bca-service-card-body">
        <?php if ($title !== ''): ?>
            <h3 class="bca-service-card-title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>

        <?php if (!empty($items)): ?>
            <ul class="bca-service-card-items">
                <?php foreach ($items as $item):
                    $label = is_array($item) ? ($item['label'] ?? '') : (string) $item;
                    if ($label === '') {
                        continue;
                    }
                ?>
                    <li class="bca-service-card-item">
                        <span class="bca-service-card-bullet" aria-hidden="true">&mdash;</span>
                        <span class="bca-service-card-item-label"><?php echo esc_html($label); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</a>
