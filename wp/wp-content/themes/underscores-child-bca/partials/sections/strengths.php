<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Strengths section — About page.
 *
 * Args:
 *   - heading    (string)
 *   - side_image (int) — image id
 *   - items      (array of {icon, title, body})
 */

$heading = $args['heading'] ?? '';
$side_image = (int) ($args['side_image'] ?? 0);
$items = $args['items'] ?? [];
if ($heading === '' && empty($items)) {
    return;
}
?>
<section class="bca-section" id="about-strengths">
    <div class="bca-section-inner">
        <h2 class="bca-section-heading"><?php echo esc_html($heading); ?></h2>

        <div class="bca-strengths-grid">
            <div class="bca-strengths-list">
                <?php foreach ($items as $item):
                    $icon_id  = (int) ($item['icon'] ?? 0);
                    $title    = $item['title'] ?? '';
                    $body     = $item['body']  ?? '';
                    if ($title === '' && $body === '') {
                        continue;
                    }
                ?>
                    <div class="bca-strength-item">
                        <?php if ($icon_id): ?>
                            <div class="bca-strength-icon">
                                <?php echo wp_get_attachment_image($icon_id, 'thumbnail', false, ['loading' => 'lazy']); ?>
                            </div>
                        <?php endif; ?>
                        <div class="bca-strength-text">
                            <?php if ($title !== ''): ?>
                                <h3 class="bca-strength-title"><?php echo esc_html($title); ?></h3>
                            <?php endif; ?>
                            <?php if ($body !== ''): ?>
                                <p class="bca-strength-body"><?php echo esc_html($body); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($side_image): ?>
                <div class="bca-strengths-side">
                    <?php echo wp_get_attachment_image($side_image, 'large', false, ['loading' => 'lazy', 'class' => 'bca-strengths-side-img']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
