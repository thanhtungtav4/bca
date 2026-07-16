<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Leader card — used in leadership archive + home teaser.
 *
 * Args:
 *   - post_id   (int)
 *   - name      (string)
 *   - permalink (string)
 *   - image_id  (int) — portrait
 *   - role      (string) — VD: "Managing Director"
 */

$post_id = (int) ($args['post_id'] ?? 0);
$name = $args['name'] ?? '';
$permalink = $args['permalink'] ?? '';
$image_id = (int) ($args['image_id'] ?? 0);
$role = $args['role'] ?? '';
if ($post_id <= 0 && $name === '') {
    return;
}
?>
<a class="bca-leader-card" href="<?php echo esc_url($permalink); ?>" data-post-id="<?php echo esc_attr($post_id); ?>">
    <?php if ($image_id): ?>
        <div class="bca-leader-card-img">
            <?php echo wp_get_attachment_image($image_id, 'medium_large', false, ['loading' => 'lazy']); ?>
        </div>
    <?php endif; ?>

    <div class="bca-leader-card-body">
        <?php if ($name !== ''): ?>
            <h3 class="bca-leader-card-name"><?php echo esc_html($name); ?></h3>
        <?php endif; ?>
        <?php if ($role !== ''): ?>
            <p class="bca-leader-card-role"><?php echo esc_html($role); ?></p>
        <?php endif; ?>
    </div>
</a>
