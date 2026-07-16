<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Site Footer — BCA Partners.
 *
 * Data:
 *   - Logo (white): Theme Settings > general_section.logo_white
 *   - Description: Theme Settings > footer_section.description
 *   - Office label + address: Theme Settings > footer_section
 *   - Hotline / Email: Theme Settings > general_section
 *   - Social: Theme Settings > social_links (repeater)
 *   - Copyright: Theme Settings > general_section.copyright
 *
 * No hardcoded text — everything from Theme Settings.
 */

$general = function_exists('underscores_get_option') ? underscores_get_option('general_section') : [];
$footer  = function_exists('underscores_get_option') ? underscores_get_option('footer_section') : [];
$social  = function_exists('underscores_get_option') ? underscores_get_option('social_links') : [];

$logo_id      = is_array($general) ? ($general['logo_white'] ?? 0) : 0;
$description  = is_array($footer)  ? ($footer['description']  ?? '') : '';
$office_label = is_array($footer)  ? ($footer['office_label'] ?? '') : '';
$office_addr  = is_array($footer)  ? ($footer['office_address'] ?? '') : '';
$hotline      = is_array($general) ? ($general['hotline'] ?? '') : '';
$email        = is_array($general) ? ($general['email'] ?? '') : '';
$copyright    = is_array($general) ? ($general['copyright'] ?? '') : '';
$social       = is_array($social) ? $social : [];

$has_any_footer_data = $logo_id || $description || $office_addr || $hotline || $email || $copyright || !empty($social);
?>
<footer class="bca-site-footer" role="contentinfo">
    <?php if ($has_any_footer_data): ?>
    <div class="bca-footer-inner">
        <div class="bca-footer-col bca-footer-brand">
            <?php if ($logo_id): ?>
                <a class="bca-footer-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
                    <?php echo wp_get_attachment_image((int) $logo_id, 'full', false, ['class' => 'bca-logo-img', 'loading' => 'lazy']); ?>
                </a>
            <?php else: ?>
                <a class="bca-footer-logo-text" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
            <?php endif; ?>

            <?php if ($description): ?>
                <p class="bca-footer-desc"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div>

        <div class="bca-footer-col bca-footer-contact">
            <?php if ($office_label): ?>
                <h4 class="bca-footer-heading"><?php echo esc_html($office_label); ?></h4>
            <?php endif; ?>

            <?php if ($office_addr): ?>
                <p class="bca-footer-address"><?php echo nl2br(esc_html($office_addr)); ?></p>
            <?php endif; ?>

            <?php if ($hotline): ?>
                <p class="bca-footer-line">
                    <span class="bca-footer-label"><?php esc_html_e('Tel:', 'bca'); ?></span>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $hotline)); ?>"><?php echo esc_html($hotline); ?></a>
                </p>
            <?php endif; ?>

            <?php if ($email): ?>
                <p class="bca-footer-line">
                    <span class="bca-footer-label"><?php esc_html_e('Email:', 'bca'); ?></span>
                    <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                </p>
            <?php endif; ?>
        </div>

        <?php if (!empty($social)): ?>
        <div class="bca-footer-col bca-footer-social">
            <h4 class="bca-footer-heading"><?php esc_html_e('Connect', 'bca'); ?></h4>
            <ul class="bca-footer-social-list">
                <?php foreach ($social as $item):
                    $link = is_array($item) ? ($item['url'] ?? []) : [];
                    $url = is_array($link) ? ($link['url'] ?? '') : (string) $link;
                    $target = is_array($link) && !empty($link['target']) ? $link['target'] : '_blank';
                    $platform = is_array($item) ? ($item['platform'] ?? '') : '';
                    if ($url === '') {
                        continue;
                    }
                ?>
                    <li>
                        <a class="bca-social-chip" href="<?php echo esc_url($url); ?>" target="<?php echo esc_attr($target); ?>" rel="noopener" aria-label="<?php echo esc_attr($platform ?: get_bloginfo('name')); ?>">
                            <?php echo esc_html(strtoupper(substr($platform, 0, 2))); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <div class="bca-footer-bottom">
        <?php if ($copyright): ?>
            <p class="bca-footer-copy"><?php echo esc_html($copyright); ?></p>
        <?php else: ?>
            <p class="bca-footer-copy">&copy; <?php echo esc_html(gmdate('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</footer>
