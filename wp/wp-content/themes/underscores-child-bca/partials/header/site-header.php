<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Site Header — BCA Partners Navbar.
 *
 * Data:
 *   - Logo: WP core custom-logo (Customizer → Site Identity)
 *   - Nav: wp_nav_menu('primary') — admin configures under Appearance → Menus
 *   - CTA button: Theme Settings > header_section.cta (link)
 *   - Active link: handled by WP nav walker
 *
 * No hardcoded nav items — admin owns the menu.
 */

$header  = function_exists('underscores_get_option') ? underscores_get_option('header_section') : [];

$logo_id = (int) get_theme_mod('custom_logo'); // WP core Site Identity logo.
$cta       = is_array($header) ? ($header['cta'] ?? []) : [];

// Determine which menu item is active for aria-current highlighting.
$current_url = home_url(add_query_arg([], $GLOBALS['wp']->request ?? ''));
?>
<header class="bca-site-header" role="banner">
    <div class="bca-navbar">
        <div class="bca-navbar-inner">
            <a class="bca-navbar-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> — home">
                <?php if ($logo_id): ?>
                    <?php echo wp_get_attachment_image((int) $logo_id, 'full', false, ['class' => 'bca-logo-img', 'loading' => 'eager']); ?>
                <?php else: ?>
                    <span class="bca-logo-fallback"><?php echo esc_html(get_bloginfo('name')); ?></span>
                <?php endif; ?>
            </a>

            <nav id="bca-primary-nav" class="bca-navbar-nav" role="navigation" aria-label="<?php esc_attr_e('Primary navigation', 'bca'); ?>">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'bca-navbar-menu',
                        'depth'          => 2,
                        'fallback_cb'    => false,
                        'link_class'     => 'bca-navbar-link',
                    ]);
                }
                ?>
            </nav>

            <div class="bca-navbar-actions hdr-actions">
                <?php echo bca_render_link($cta, '', ['class' => 'bca-navbar-cta']); ?>

                <button type="button"
                        class="bca-navbar-toggle hdr-burger"
                        aria-controls="bca-primary-nav"
                        aria-expanded="false"
                        aria-label="<?php esc_attr_e('Open menu', 'bca'); ?>">
                    <span class="bca-navbar-toggle-icon" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
</header>
