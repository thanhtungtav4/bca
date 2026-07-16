<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Belief section — About page.
 *
 * Args:
 *   - heading (string)
 *   - quote   (string)
 *   - image   (int) — image id
 */

$heading = $args['heading'] ?? '';
$quote = $args['quote'] ?? '';
$image = (int) ($args['image'] ?? 0);

if ($heading === '' && $quote === '') {
    return;
}
?>
<section class="bca-section" id="about-belief">
    <div class="bca-section-inner">
        <div class="bca-belief-card">
            <?php if ($image): ?>
                <div class="bca-belief-bg" aria-hidden="true">
                    <?php echo wp_get_attachment_image($image, 'full', false, ['class' => 'bca-belief-img', 'loading' => 'lazy']); ?>
                    <div class="bca-belief-scrim"></div>
                </div>
            <?php endif; ?>

            <div class="bca-belief-content">
                <?php if ($heading !== ''): ?>
                    <h2 class="bca-belief-heading"><?php echo esc_html($heading); ?></h2>
                <?php endif; ?>

                <?php if ($quote !== ''): ?>
                    <blockquote class="bca-belief-quote">
                        <span class="bca-belief-mark" aria-hidden="true">&ldquo;</span>
                        <p><?php echo esc_html($quote); ?></p>
                    </blockquote>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
