<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Hero section — reusable across pages.
 *
 * Args (from $args via bca_render_section):
 *   - heading      (string)
 *   - subheading   (string)
 *   - image        (int) — image id
 *   - cta          (link array: title/url/target)
 *   - variant      (string)  'home' | 'page' (default: 'page')
 *   - tone         (string)  'navy' | 'blue' | 'image' (default: 'image')
 *
 * Renders nothing if heading is empty.
 */

$heading = $args['heading'] ?? '';
$subheading = $args['subheading'] ?? '';
$image_id   = (int) ($args['image'] ?? 0);
$cta = $args['cta'] ?? [];
$variant = $args['variant'] ?? 'page';
$tone = $args['tone'] ?? 'image';
if ($heading === '') {
    return;
}

$cta_url    = is_array($cta) ? ($cta['url'] ?? '') : '';
$cta_label  = is_array($cta) ? ($cta['title'] ?? '') : '';
$cta_target = is_array($cta) && !empty($cta['target']) ? $cta['target'] : '';
$has_cta  = $cta_url !== '' && $cta_label !== '';
$has_img  = $image_id > 0;
$section_class = 'bca-hero bca-hero--' . sanitize_html_class($variant) . ' bca-hero--' . sanitize_html_class($tone);
?>
<section class="<?php echo esc_attr($section_class); ?>" <?php echo $has_img ? 'data-has-image="true"' : ''; ?>>
    <?php if ($has_img): ?>
        <div class="bca-hero-bg" aria-hidden="true">
            <?php echo wp_get_attachment_image($image_id, 'full', false, ['class' => 'bca-hero-bg-img', 'loading' => 'eager', 'fetchpriority' => 'high']); ?>
            <div class="bca-hero-scrim"></div>
        </div>
    <?php endif; ?>

    <div class="bca-hero-inner">
        <h1 class="bca-hero-heading"><?php echo esc_html($heading); ?></h1>

        <?php if ($subheading !== ''): ?>
            <p class="bca-hero-sub"><?php echo esc_html($subheading); ?></p>
        <?php endif; ?>

        <?php if ($has_cta): ?>
            <div class="bca-hero-cta">
                <a class="bca-btn bca-btn--filled" href="<?php echo esc_url($cta_url); ?>"<?php echo $cta_target ? ' target="' . esc_attr($cta_target) . '" rel="noopener"' : ''; ?>>
                    <?php echo esc_html($cta_label); ?>
                    <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
