<?php

declare(strict_types=1);

namespace Theme\Hooks;

defined('ABSPATH') || exit;

/**
 * Core theme setup, asset pipeline and MIME/header tweaks.
 *
 * Fires the cross-theme extension points:
 *   underscores_before_common_css / underscores_after_common_css
 *   underscores_before_common_js  / underscores_after_common_js
 */
final class CommonHook
{
    public static function register(): void
    {
        $self = new self();

        add_action('after_setup_theme', [$self, 'register_menus']);
        add_filter('admin_url', [$self, 'tag_ajax_admin_url'], 999, 3);
        add_action('wp_enqueue_scripts', [$self, 'jquery_alias'], 1);
        add_action('wp_enqueue_scripts', [$self, 'enqueue_common_css'], 10);
        add_action('wp_enqueue_scripts', [$self, 'enqueue_common_js'], 10);
        add_action('underscores_after_common_js', [$self, 'localize_frontend_params'], 10);
        add_filter('script_loader_tag', [$self, 'script_to_module'], 10, 2);
        add_action('wp_head', [$self, 'preconnect_fonts'], 1);

        add_filter('upload_mimes', [$self, 'allow_modern_image_mimes']);
        add_filter('wp_check_filetype_and_ext', [$self, 'fix_modern_image_filetype'], 10, 4);
        add_action('init', [$self, 'send_avif_header']);

        if (function_exists('underscores_optimize_custom_logo_attrs')) {
            add_filter('get_custom_logo_image_attributes', 'underscores_optimize_custom_logo_attrs');
        }
    }

    public function register_menus(): void
    {
        register_nav_menus([
            'top-header-menu' => __('Top Header Menu', 'underscores'),
            'header-menu'     => __('Header Menu', 'underscores'),
        ]);

        add_theme_support('woocommerce');
    }

    public function tag_ajax_admin_url($url, $path, $blog_id)
    {
        if ($path === 'admin-ajax.php' && ! is_admin()) {
            $url .= '?underscores-ajax';
        }

        return $url;
    }

    public function jquery_alias(): void
    {
        if (! wp_script_is('jquery', 'registered')) {
            return;
        }

        wp_add_inline_script('jquery', 'var $ = jQuery;', 'before');
    }

    public function enqueue_common_css(): void
    {
        if (is_404()) {
            wp_enqueue_style('underscores-404', UNDERSCORES_THEME_PATH_URI . '/assets/css/404.css');
        }

        if (is_child_theme()) {
            return;
        }

        if (! apply_filters('underscores_enable_parent_common_css', true)) {
            return;
        }

        wp_enqueue_style('underscores-font-CenturySchoolbookBT', UNDERSCORES_SITE_TEMPLATE_URL . '/assets/fonts/SFU-CenturySchoolbookBT/stylesheet.css');

        do_action('underscores_before_common_css');

        wp_enqueue_style('underscores-common', UNDERSCORES_SITE_TEMPLATE_URL . '/assets/css/common.css');
        wp_enqueue_style('underscores-main-style', get_stylesheet_uri());

        do_action('underscores_after_common_css');
    }

    public function enqueue_common_js(): void
    {
        if (is_child_theme()) {
            return;
        }

        if (! apply_filters('underscores_enable_parent_common_js', true)) {
            return;
        }

        do_action('underscores_before_common_js');
        do_action('underscores_after_common_js');
    }

    public function localize_frontend_params(): void
    {
        if (! wp_script_is('underscores-frontend', 'enqueued') && ! wp_script_is('underscores-frontend', 'registered')) {
            return;
        }

        $params = apply_filters('underscores_ajax_params', [
            'siteURL'   => get_site_url(),
            'ajaxURL'   => admin_url('admin-ajax.php'),
            'ajaxNonce' => wp_create_nonce('underscores-ajax-security'),
        ]);

        wp_localize_script('underscores-frontend', 'underscores_params', $params);
    }

    public function script_to_module($tag, $handle)
    {
        $handlers = apply_filters('underscores_script_to_module', [
            'underscores-main',
            'underscores-frontend',
        ]);

        if (in_array($handle, $handlers, true)) {
            $tag = str_replace('<script', '<script type="module"', $tag);
        }

        return $tag;
    }

    public function preconnect_fonts(): void
    {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">' . "\n";
    }

    public function allow_modern_image_mimes($mimes)
    {
        $mimes['avif'] = 'image/avif';
        $mimes['webp'] = 'image/webp';
        return $mimes;
    }

    public function fix_modern_image_filetype($data, $file, $filename, $mimes)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext === 'avif') {
            $data['ext']  = 'avif';
            $data['type'] = 'image/avif';
        }
        if ($ext === 'webp') {
            $data['ext']  = 'webp';
            $data['type'] = 'image/webp';
        }
        return $data;
    }

    public function send_avif_header(): void
    {
        $request_uri = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';
        if ($request_uri && preg_match('/\.avif$/i', $request_uri)) {
            header('Content-Type: image/avif');
        }
    }
}
