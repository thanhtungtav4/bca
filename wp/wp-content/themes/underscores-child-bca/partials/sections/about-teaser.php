<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * About teaser — two-column: image left, About/Mission/Value right.
 *
 * Args (from $args via bca_render_section):
 *   - is_show         (bool)
 *   - image           (int)  — image id
 *   - eyebrow         (string)
 *   - heading         (string)
 *   - body            (string)
 *   - mission_heading (string)
 *   - mission_body    (string)
 *   - value_heading   (string)
 *   - value_body      (string)
 *   - cta             (link array: title/url/target)
 */

$args = $args ?? [];

$is_show       = !empty($args['is_show']);
$image_id      = (int) ($args['image']           ?? 0);
$eyebrow       = $args['eyebrow']         ?? '';
$heading       = $args['heading']         ?? '';
$body          = $args['body']            ?? '';
$mission_h     = $args['mission_heading'] ?? '';
$mission_b     = $args['mission_body']    ?? '';
$value_h       = $args['value_heading']   ?? '';
$value_b       = $args['value_body']      ?? '';
$cta           = $args['cta']            ?? [];
$cta_url       = is_array($cta) ? ($cta['url'] ?? '') : '';
$cta_label     = is_array($cta) ? ($cta['title'] ?? '') : '';
$cta_target    = is_array($cta) && !empty($cta['target']) ? $cta['target'] : '';

if (!$is_show) {
    return;
}
?>
<section class="bca-section bca-about-teaser" id="home-about-teaser">
    <div class="bca-section-inner bca-about-teaser-grid">
        <div class="bca-about-teaser-img">
            <?php if ($image_id): ?>
                <?php echo wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']); ?>
            <?php else: ?>
                <div class="bca-about-teaser-img-fallback" aria-hidden="true"></div>
            <?php endif; ?>
        </div>

        <div class="bca-about-teaser-text">
            <?php if ($eyebrow !== ''): ?>
                <span class="bca-eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <?php endif; ?>
            <?php if ($heading !== ''): ?>
                <h2 class="bca-section-heading bca-section-heading--sm"><?php echo esc_html($heading); ?></h2>
            <?php endif; ?>
            <?php if ($body !== ''): ?>
                <p class="bca-about-teaser-body"><?php echo esc_html($body); ?></p>
            <?php endif; ?>

            <?php if ($mission_h !== '' || $mission_b !== ''): ?>
                <div class="bca-about-teaser-block">
                    <?php if ($mission_h !== ''): ?>
                        <h3 class="bca-about-teaser-block-heading"><?php echo esc_html($mission_h); ?></h3>
                    <?php endif; ?>
                    <?php if ($mission_b !== ''): ?>
                        <p class="bca-about-teaser-block-body"><?php echo esc_html($mission_b); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($value_h !== '' || $value_b !== ''): ?>
                <div class="bca-about-teaser-block">
                    <?php if ($value_h !== ''): ?>
                        <h3 class="bca-about-teaser-block-heading"><?php echo esc_html($value_h); ?></h3>
                    <?php endif; ?>
                    <?php if ($value_b !== ''): ?>
                        <p class="bca-about-teaser-block-body"><?php echo esc_html($value_b); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($cta_label !== '' && $cta_url !== ''): ?>
                <a class="bca-btn bca-btn--filled" href="<?php echo esc_url($cta_url); ?>"<?php echo $cta_target ? ' target="' . esc_attr($cta_target) . '" rel="noopener"' : ''; ?>>
                    <?php echo esc_html($cta_label); ?>
                    <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>
