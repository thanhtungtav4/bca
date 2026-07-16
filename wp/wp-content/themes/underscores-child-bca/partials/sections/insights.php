<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Insights — horizontal row of featured content cards (repeater).
 *
 * Args (from $args via bca_render_section):
 *   - is_show   (bool)
 *   - heading   (string)
 *   - subheading(string)
 *   - items     (repeater) — array of {image, eyebrow, title, body, cta (link)}
 *   - cta       (link array: title/url/target) — bottom CTA
 *
 * Renders nothing if is_show is off or no items.
 */

$args = $args ?? [];

$is_show   = !empty($args['is_show']);
$heading   = $args['heading']    ?? '';
$subheading = $args['subheading'] ?? '';
$items     = $args['items']      ?? [];
$cta       = $args['cta']        ?? [];
$cta_url    = is_array($cta) ? ($cta['url'] ?? '') : '';
$cta_label  = is_array($cta) ? ($cta['title'] ?? '') : '';
$cta_target = is_array($cta) && !empty($cta['target']) ? $cta['target'] : '';

if (!$is_show || empty($items)) {
    return;
}
?>
<section class="bca-section bca-insights" id="home-insights">
    <div class="bca-section-inner">
        <?php if ($heading !== ''): ?>
            <div class="bca-teaser-head bca-teaser-head--center">
                <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
                <?php if ($subheading !== ''): ?>
                    <p class="bca-section-sub bca-section-sub--center"><?php echo esc_html($subheading); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="bca-insights-row">
            <?php foreach ($items as $item):
                $image_id = (int) ($item['image'] ?? 0);
                $eyebrow  = $item['eyebrow']  ?? '';
                $title    = $item['title']    ?? '';
                $body     = $item['body']     ?? '';
                $i_cta    = $item['cta'] ?? [];
                $i_url    = is_array($i_cta) ? ($i_cta['url'] ?? '') : '';
                $i_label  = is_array($i_cta) ? ($i_cta['title'] ?? '') : '';
                $i_target = is_array($i_cta) && !empty($i_cta['target']) ? $i_cta['target'] : '';
                if ($title === '' && $body === '' && !$image_id) {
                    continue;
                }
            ?>
            <article class="bca-insight-card">
                <?php if ($image_id): ?>
                    <div class="bca-insight-card-img">
                        <?php echo wp_get_attachment_image($image_id, 'large', false, ['loading' => 'lazy']); ?>
                    </div>
                <?php endif; ?>

                <div class="bca-insight-card-body">
                    <?php if ($eyebrow !== ''): ?>
                        <span class="bca-eyebrow"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ($title !== ''): ?>
                        <h3 class="bca-insight-card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    <?php if ($body !== ''): ?>
                        <p class="bca-insight-card-body"><?php echo esc_html($body); ?></p>
                    <?php endif; ?>
                    <?php if ($i_label !== '' && $i_url !== ''): ?>
                        <a class="bca-btn bca-btn--ghost" href="<?php echo esc_url($i_url); ?>"<?php echo $i_target ? ' target="' . esc_attr($i_target) . '" rel="noopener"' : ''; ?>>
                            <?php echo esc_html($i_label); ?>
                            <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                        </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if ($cta_label !== '' && $cta_url !== ''): ?>
            <div class="bca-insights-foot bca-teaser-foot bca-teaser-foot--center">
                <a class="bca-btn bca-btn--filled" href="<?php echo esc_url($cta_url); ?>"<?php echo $cta_target ? ' target="' . esc_attr($cta_target) . '" rel="noopener"' : ''; ?>>
                    <?php echo esc_html($cta_label); ?>
                    <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>
