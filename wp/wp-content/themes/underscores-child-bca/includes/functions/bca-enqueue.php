<?php
/**
 * Enqueue the BCA design-system styles + script on the front-end.
 *
 * Loaded as a separate file so the child can opt in/out per page hook.
 *
 * @package BCA_Child
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Preload the primary Inter font (latin 400) so the body text paints
 * with Inter from the first render — no FOUT fallback flash.
 * Outputs a <link rel="preload" as="font"> directly in wp_head.
 */
if (!function_exists('bca_preload_inter_font')) {
    function bca_preload_inter_font(): void
    {
        $primary = BCA_CHILD_THEME_PATH . '/assets/fonts/inter-400-latin.woff2';
        if (file_exists($primary)) {
            $url = BCA_CHILD_THEME_URI . '/assets/fonts/inter-400-latin.woff2';
            echo '<link rel="preload" href="' . esc_url($url) . '" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
        }
    }
}
add_action('wp_head', 'bca_preload_inter_font', 1);

/**
 * Remove the default Google Fonts preconnect (we self-host Inter now).
 */
if (!function_exists('bca_remove_google_fonts_preconnect')) {
    function bca_remove_google_fonts_preconnect(array $urls, string $relation): array
    {
        if ($relation === 'preconnect' || $relation === 'dns-prefetch') {
            $urls = array_values(array_filter($urls, function ($url) {
                return !preg_match('#fonts\.(googleapis|gstatic)\.com#i', $url);
            }));
        }
        return $urls;
    }
}
add_filter('wp_resource_hints', 'bca_remove_google_fonts_preconnect', 10, 2);

/**
 * Enqueue design-system CSS (tokens + main).
 *
 * Tokens are loaded first so they cascade into the main stylesheet
 * (which is the single entry point the design-system spec describes).
 */
if (!function_exists('bca_enqueue_design_system_assets')) {
    function bca_enqueue_design_system_assets(): void
    {
        // Tokens (single-file per group: colors, typography, spacing, effects, icons, fonts).
        $tokens = [
            'colors',
            'typography',
            'spacing',
            'effects',
            'icons',
            'fonts',
        ];

        foreach ($tokens as $token) {
            $path = BCA_CHILD_THEME_PATH . "/assets/css/tokens/{$token}.css";
            if (file_exists($path)) {
                wp_enqueue_style(
                    "bca-tokens-{$token}",
                    BCA_CHILD_THEME_URI . "/assets/css/tokens/{$token}.css",
                    [],
                    filemtime($path)
                );
            }
        }

        // Main stylesheet (design-system entry point).
        $main_css = BCA_CHILD_THEME_PATH . '/assets/css/main.css';
        if (file_exists($main_css)) {
            wp_enqueue_style(
                'bca-main',
                BCA_CHILD_THEME_URI . '/assets/css/main.css',
                ['bca-tokens-colors', 'bca-tokens-typography'],
                filemtime($main_css)
            );
        }

        // Base layer — applies Inter font + design-system colors to body.
        // MUST load before site.css / sections.css.
        $base_css = BCA_CHILD_THEME_PATH . '/assets/css/base.css';
        if (file_exists($base_css)) {
            wp_enqueue_style(
                'bca-base',
                BCA_CHILD_THEME_URI . '/assets/css/base.css',
                ['bca-main'],
                filemtime($base_css)
            );
        }

        // Site layout (header, footer, common).
        $site_css = BCA_CHILD_THEME_PATH . '/assets/css/site.css';
        if (file_exists($site_css)) {
            wp_enqueue_style(
                'bca-site',
                BCA_CHILD_THEME_URI . '/assets/css/site.css',
                ['bca-base'],
                filemtime($site_css)
            );
        }

        // Section + component layouts.
        $sections_css = BCA_CHILD_THEME_PATH . '/assets/css/sections.css';
        if (file_exists($sections_css)) {
            wp_enqueue_style(
                'bca-sections',
                BCA_CHILD_THEME_URI . '/assets/css/sections.css',
                ['bca-site'],
                filemtime($sections_css)
            );
        }

        // Site interaction JS (mobile menu toggle). Loaded in footer for speed.
        $site_js = BCA_CHILD_THEME_PATH . '/assets/js/site.js';
        if (file_exists($site_js)) {
            wp_enqueue_script(
                'bca-site',
                BCA_CHILD_THEME_URI . '/assets/js/site.js',
                [],
                filemtime($site_js),
                true
            );
        }
    }
}

add_action('wp_enqueue_scripts', 'bca_enqueue_design_system_assets', 20);

/**
 * Dequeue parent theme's `child-theme.js` — it has a duplicate mobile-menu
 * handler that conflicts with our `bca-site` script. The BCA site.js handles
 * the same logic + more (matches our custom nav structure).
 */
add_action('wp_enqueue_scripts', function (): void {
    wp_dequeue_script('underscores-child-script');
    wp_deregister_script('underscores-child-script');
}, 25);
