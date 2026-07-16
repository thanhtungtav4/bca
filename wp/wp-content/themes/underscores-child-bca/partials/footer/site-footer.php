<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Site Footer — BCA Partners (4-col layout matching ui_kits design).
 *
 * Columns:
 *   1. BCA brand   → logo (white) + description + LinkedIn social chip
 *   2. COMPANY     → About us / Our team / Projects / Career
 *   3. SERVICES    → auto from CPT 'service' (Mergers & Acquisitions, Strategy, …)
 *   4. GET IN TOUCH → address + tel + email
 *
 * All data from Theme Settings + CPT 'service'. No hardcoded labels/URLs.
 *
 * @package BCA_Child
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

/* COMPANY links: hardcoded page IDs (About=52, Leadership archive, Projects archive, Career archive). */
$company_pages = [
    ['label' => __('About us', 'bca'),      'url' => get_permalink(52)],
    ['label' => __('Our team', 'bca'),      'url' => get_post_type_archive_link('leader') ?: home_url('/leadership/')],
    ['label' => __('Projects', 'bca'),      'url' => get_post_type_archive_link('project') ?: home_url('/projects/')],
    ['label' => __('Career', 'bca'),        'url' => get_post_type_archive_link('career') ?: home_url('/career/')],
];

/* SERVICES: auto-pull from CPT 'service' (admin can change order via menu_order). */
$service_links = [];
$service_q = new WP_Query([
    'post_type'      => 'service',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'no_found_rows'  => true,
]);
if ($service_q->have_posts()) {
    while ($service_q->have_posts()) {
        $service_q->the_post();
        $service_links[] = ['label' => get_the_title(), 'url' => get_permalink()];
    }
    wp_reset_postdata();
}

$has_any_footer_data = $logo_id || $description || $office_addr || $hotline || $email || $copyright || !empty($social) || !empty($service_links);
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

            <?php if (!empty($social)): ?>
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
                            <?php echo esc_html(strtoupper(substr($platform ?: '', 0, 2))); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>

        <div class="bca-footer-col bca-footer-company">
            <h4 class="bca-footer-heading"><?php esc_html_e('Company', 'bca'); ?></h4>
            <ul class="bca-footer-links">
                <?php foreach ($company_pages as $link): ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bca-footer-col bca-footer-services">
            <h4 class="bca-footer-heading"><?php esc_html_e('Services', 'bca'); ?></h4>
            <ul class="bca-footer-links">
                <?php foreach ($service_links as $link): ?>
                    <li><a href="<?php echo esc_url($link['url']); ?>"><?php echo esc_html($link['label']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bca-footer-col bca-footer-touch">
            <h4 class="bca-footer-heading"><?php esc_html_e('Get in touch', 'bca'); ?></h4>
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
