<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Contact band — full-bleed CTA strip near bottom of page.
 *
 * Design: solid blue background with a subtle wave pattern overlay.
 * Layout: heading on the left, CTA button on the right.
 * Button: white background + navy text (inverted from the default filled button).
 *
 * Args (from $args via bca_render_section):
 *   - heading     (string)
 *   - subheading  (string) — optional secondary line
 *   - image       (int)    — optional background image (off by default; the design uses solid blue)
 *   - cta_label   (string) — CTA button label
 *   - cta_url     (string) — CTA button URL
 *
 * Renders only if there's at least a heading OR a CTA.
 */

$args = $args ?? [];

$heading    = $args['heading']    ?? '';
$subheading = $args['subheading'] ?? '';
$image_id   = (int) ($args['image'] ?? 0);

// CTA — support both shapes: ACF link field (array) and legacy label/url scalars.
$cta = $args['cta'] ?? null;
if (is_array($cta)) {
    $cta_label = (string) ($cta['title'] ?? '');
    $cta_url   = (string) ($cta['url']   ?? '');
} else {
    $cta_label = (string) ($args['cta_label'] ?? '');
    $cta_url   = (string) ($args['cta_url']   ?? '');
}

if ($heading === '' && $cta_label === '') {
    return;
}
?>
<section class="bca-contact-band">
    <?php if ($image_id): ?>
        <div class="bca-contact-band-bg" aria-hidden="true">
            <?php echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'bca-contact-band-img', 'loading' => 'lazy']); ?>
            <div class="bca-contact-band-scrim"></div>
        </div>
    <?php else: ?>
        <div class="bca-contact-band-bg" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="bca-contact-band-inner">
        <div class="bca-contact-band-text">
            <?php if ($heading !== ''): ?>
                <h2 class="bca-contact-band-heading"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($subheading !== ''): ?>
                <p class="bca-contact-band-sub"><?php echo esc_html($subheading); ?></p>
            <?php endif; ?>
        </div>
        <?php if ($cta_label !== '' && $cta_url !== ''): ?>
            <a class="bca-btn bca-contact-band-cta" href="<?php echo esc_url($cta_url); ?>">
                <?php echo esc_html($cta_label); ?>
                <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
            </a>
        <?php endif; ?>
    </div>
</section>
