<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Vision / Mission / Core Values — About page.
 *
 * Args:
 *   - heading (string)
 *   - items   (array of {image, title, body})
 */

$heading = $args['heading'] ?? '';
$items = $args['items'] ?? [];
if ($heading === '' && empty($items)) {
    return;
}
?>
<section class="bca-section" id="about-vmcv">
    <div class="bca-section-inner">
        <div class="bca-vmcv-head">
            <h2 class="bca-section-heading"><?php echo esc_html($heading); ?></h2>
            <?php $vmcv = get_page_by_path('vision-mission-core-values'); ?>
            <a class="bca-btn bca-btn--filled" href="<?php echo esc_url($vmcv ? get_permalink($vmcv) : home_url('/vision-mission-core-values/')); ?>">
                <?php esc_html_e('Explore more', 'bca'); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="bca-vmcv-grid">
            <?php foreach ($items as $item):
                $image_id = (int) ($item['image'] ?? 0);
                $title    = $item['title'] ?? '';
                $body     = $item['body']  ?? '';
                if ($title === '' && $body === '' && !$image_id) {
                    continue;
                }
            ?>
                <article class="bca-vmcv-card">
                    <?php if ($image_id): ?>
                        <div class="bca-vmcv-img">
                            <?php echo wp_get_attachment_image($image_id, 'medium_large', false, ['loading' => 'lazy']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($title !== ''): ?>
                        <h3 class="bca-vmcv-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    <?php if ($body !== ''): ?>
                        <p class="bca-vmcv-body"><?php echo esc_html($body); ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
