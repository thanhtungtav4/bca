<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Partners — row of client/partner logos.
 *
 * Args (from $args via bca_render_section):
 *   - is_show (bool)
 *   - heading (string)
 *   - items   (repeater) — array of {logo, url}
 */

$args = $args ?? [];

$is_show = !empty($args['is_show']);
$heading = $args['heading'] ?? '';
$items   = $args['items']   ?? [];

if (!$is_show || empty($items)) {
    return;
}
?>
<section class="bca-section bca-partners" id="home-partners">
    <div class="bca-section-inner">
        <?php if ($heading !== ''): ?>
            <div class="bca-teaser-head bca-teaser-head--center">
                <h2 class="bca-section-heading bca-section-heading--center"><?php echo esc_html($heading); ?></h2>
            </div>
        <?php endif; ?>

        <div class="bca-partners-list">
            <?php foreach ($items as $item):
                $logo_id = (int) ($item['logo'] ?? 0);
                $link    = $item['url'] ?? [];
                $url     = is_array($link) ? ($link['url'] ?? '') : (string) $link;
                $target  = is_array($link) && !empty($link['target']) ? $link['target'] : '_blank';
                if (!$logo_id) {
                    continue;
                }
                $img = wp_get_attachment_image($logo_id, 'medium', false, [
                    'class'    => 'bca-partner-logo-img',
                    'loading'  => 'lazy',
                ]);
                if ($url !== '') {
                    $img = '<a class="bca-partner-logo-link" href="' . esc_url($url) . '" target="' . esc_attr($target) . '" rel="noopener">' . $img . '</a>';
                }
            ?>
                <div class="bca-partner-logo"><?php echo $img; ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
