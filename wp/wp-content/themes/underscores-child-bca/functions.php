<?php
declare(strict_types=1);

/**
 * BCA Partners Child Theme — bootstrap & helpers.
 *
 * @package BCA_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('BCA_CHILD_THEME_VERSION')) {
    define('BCA_CHILD_THEME_VERSION', (string) wp_get_theme(get_stylesheet())->get('Version'));
}

if (!defined('BCA_CHILD_THEME_PATH')) {
    define('BCA_CHILD_THEME_PATH', get_stylesheet_directory());
}

if (!defined('BCA_CHILD_THEME_URI')) {
    define('BCA_CHILD_THEME_URI', get_stylesheet_directory_uri());
}

if (!defined('BCA_CHILD_THEME_APP_PATH')) {
    define('BCA_CHILD_THEME_APP_PATH', BCA_CHILD_THEME_PATH . '/app');
}

if (!defined('BCA_CHILD_THEME_INCLUDES_PATH')) {
    define('BCA_CHILD_THEME_INCLUDES_PATH', BCA_CHILD_THEME_PATH . '/includes');
}

if (!defined('BCA_CHILD_THEME_STUB_PATH')) {
    define('BCA_CHILD_THEME_STUB_PATH', BCA_CHILD_THEME_PATH . '/stubs');
}

// Back-compat aliases — helper files inherited from the Underscores parent
// convention (includes/functions/*.php) still reference the old names.
if (!defined('UNDERSCORES_CHILD_THEME_PATH')) {
    define('UNDERSCORES_CHILD_THEME_PATH', BCA_CHILD_THEME_PATH);
}
if (!defined('UNDERSCORES_CHILD_THEME_URI')) {
    define('UNDERSCORES_CHILD_THEME_URI', BCA_CHILD_THEME_URI);
}
if (!defined('UNDERSCORES_CHILD_THEME_VERSION')) {
    define('UNDERSCORES_CHILD_THEME_VERSION', BCA_CHILD_THEME_VERSION);
}
if (!defined('UNDERSCORES_CHILD_THEME_INCLUDES_PATH')) {
    define('UNDERSCORES_CHILD_THEME_INCLUDES_PATH', BCA_CHILD_THEME_INCLUDES_PATH);
}
if (!defined('UNDERSCORES_CHILD_THEME_STUB_PATH')) {
    define('UNDERSCORES_CHILD_THEME_STUB_PATH', BCA_CHILD_THEME_STUB_PATH);
}
if (!defined('UNDERSCORES_CHILD_THEME_APP_PATH')) {
    define('UNDERSCORES_CHILD_THEME_APP_PATH', BCA_CHILD_THEME_APP_PATH);
}
if (!defined('UNDERSCORES_CHILD_THEME_CONFIG_PATH')) {
    define('UNDERSCORES_CHILD_THEME_CONFIG_PATH', BCA_CHILD_THEME_INCLUDES_PATH . '/config');
}

if (!function_exists('bca_get_main_class')) {
    function bca_get_main_class(string $css_class = ''): string
    {
        $classes = ['main'];
        $registered = $GLOBALS['bca_main_class'] ?? '';

        if (!empty($registered)) {
            $registered = is_array($registered) ? $registered : preg_split('#\s+#', (string) $registered);
            $classes = array_merge($classes, $registered);
        }

        if ($css_class !== '') {
            $css_class = is_array($css_class) ? $css_class : preg_split('#\s+#', $css_class);
            $classes = array_merge($classes, $css_class);
        }

        $classes = apply_filters('bca_main_class', $classes, $css_class);
        $classes = array_filter(array_map('sanitize_html_class', array_unique((array) $classes)));

        return $classes === [] ? '' : sprintf('class="%s"', esc_attr(implode(' ', $classes)));
    }
}

if (!function_exists('bca_main_class')) {
    function bca_main_class(string $css_class = ''): void
    {
        echo bca_get_main_class($css_class); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if (!function_exists('bca_set_main_class')) {
    function bca_set_main_class(string $css_class = ''): void
    {
        $GLOBALS['bca_main_class'] = $css_class;
    }
}

if (!defined('ABSPATH') || !function_exists('add_action')) {
    return;
}

/**
 * Register navigation menu locations.
 *
 * Mirrors the 8-link nav in the BCA Partners design system:
 * Home / About us / Our services / Projects / Research / News / Career / Contact us.
 */
add_action('after_setup_theme', function (): void {
    register_nav_menus([
        'primary'    => __('Primary Navigation', 'bca'),
        'footer'     => __('Footer Navigation', 'bca'),
    ]);
});

/**
 * Add `bca-navbar-link` class to every <a> in the primary menu.
 *
 * `wp_nav_menu( 'link_class' )` is not always honored (some walkers
 * hardcode the class list), so we attach via the standard
 * `nav_menu_link_attributes` filter — applied only to the primary location
 * so we don't pollute other menus.
 */
add_filter('nav_menu_link_attributes', function (array $atts, $item, $args, $depth): array {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $existing = !empty($atts['class']) ? explode(' ', (string) $atts['class']) : [];
        $existing[] = 'bca-navbar-link';
        $atts['class'] = trim(implode(' ', array_unique(array_filter($existing))));
    }
    return $atts;
}, 10, 4);

/**
 * Force `current-menu-item` on primary menu links whose URL matches the
 * current request path. WP's default `current-menu-item` matching is
 * unreliable for *custom* menu items on archive views (e.g. /career/).
 */
add_filter('nav_menu_css_class', function (array $classes, $item, $args, $depth): array {
    if (!isset($args->theme_location) || $args->theme_location !== 'primary') {
        return $classes;
    }
    $item_url = (string) ($item->url ?? '');
    if ($item_url === '') {
        return $classes;
    }
    $current_path = trim((string) (parse_url(home_url(add_query_arg([], $GLOBALS['wp']->request ?? '')), PHP_URL_PATH) ?? ''), '/');
    $item_path   = trim((string) parse_url($item_url, PHP_URL_PATH), '/');
    // Also fall back to comparing the relative path.
    if ($current_path !== '' && $item_path !== '' && $current_path === $item_path) {
        $classes[] = 'current-menu-item';
    }
    return array_values(array_unique($classes));
}, 20, 4);

/**
 * Helper: render an image (ACF id) with fallback.
 *
 * Returns the full <img> tag string. If no image, returns ''.
 * Caller decides whether to print.
 */
if (!function_exists('bca_render_image')) {
    function bca_render_image($image_id, string $size = 'full', array $attr = []): string
    {
        if (empty($image_id) || !is_numeric($image_id)) {
            return '';
        }
        return wp_get_attachment_image((int) $image_id, $size, false, $attr);
    }
}

/**
 * Helper: render a link (ACF array or URL string) as <a> tag.
 * Returns '' when no URL.
 */
if (!function_exists('bca_render_link')) {
    function bca_render_link($link, string $label = '', array $attr = []): string
    {
        if (empty($link)) {
            return '';
        }
        $url = '';
        $title = $label;
        $target = '';
        if (is_array($link)) {
            $url = $link['url'] ?? '';
            $title = $link['title'] ?? $label;
            $target = !empty($link['target']) ? ' target="' . esc_attr($link['target']) . '" rel="noopener"' : '';
        } elseif (is_string($link)) {
            $url = $link;
        }
        if ($url === '') {
            return '';
        }
        $class = $attr['class'] ?? '';
        $class_attr = $class !== '' ? ' class="' . esc_attr($class) . '"' : '';
        return sprintf(
            '<a href="%s"%s%s>%s</a>',
            esc_url($url),
            $class_attr,
            $target,
            esc_html($title)
        );
    }
}

/**
 * Helper: only render the partial if `is_show` is true. Otherwise output nothing.
 * Keeps the page template as a clean orchestration layer.
 */
if (!function_exists('bca_render_section')) {
    function bca_render_section(array $settings, string $partial_slug, array $extra_args = []): void
    {
        if (empty($settings['is_show'])) {
            return;
        }
        // WP 7.0 locate_template() does NOT auto-append .php — append it explicitly.
        $slug_with_ext = str_ends_with($partial_slug, '.php') ? $partial_slug : $partial_slug . '.php';
        $args = array_merge($settings, $extra_args);
        if (locate_template($slug_with_ext)) {
            // WP 7.0 load_template() does NOT extract $args into the partial's scope —
            // partials must read their data via $args['key'] (array access), not $key.
            get_template_part($partial_slug, null, $args);
        }
    }
}

// Back-compat function aliases — front-page.php and other old page templates
// still call the old function names. Map them to the new ones.
if (!function_exists('underscores_set_main_class')) {
    function underscores_set_main_class(string $css_class = ''): void
    {
        bca_set_main_class($css_class);
    }
}
if (!function_exists('underscores_child_set_main_class')) {
    function underscores_child_set_main_class(string $css_class = ''): void
    {
        bca_set_main_class($css_class);
    }
}
if (!function_exists('get_main_class')) {
    function get_main_class(string $css_class = ''): string
    {
        return bca_get_main_class($css_class);
    }
}
if (!function_exists('main_class')) {
    function main_class(string $css_class = ''): void
    {
        bca_main_class($css_class);
    }
}

function bca_child_bootstrap(): void
{
    static $bootstrapped = false;

    if ($bootstrapped) {
        return;
    }

    $bootstrapped = true;

    $init_file = BCA_CHILD_THEME_INCLUDES_PATH . '/bootstrap.php';

    if (file_exists($init_file)) {
        require_once $init_file;
    }
}

add_action('after_setup_theme', 'bca_child_bootstrap', 0);
