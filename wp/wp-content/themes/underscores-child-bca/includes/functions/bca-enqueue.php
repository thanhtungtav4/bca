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
        // Single bundled CSS file (tokens + base + site + sections + pages).
        // Source files in assets/css/ stay readable for editing; the bundle
        // is rebuilt by bin/build-css.sh. Cuts ~11 HTTP requests to 1 and
        // shaves FCP by 200-300ms. We always enqueue this single file.
        $bundle = BCA_CHILD_THEME_PATH . '/assets/dist/bca.bundle.css';
        if (file_exists($bundle)) {
            wp_enqueue_style(
                'bca',
                BCA_CHILD_THEME_URI . '/assets/dist/bca.bundle.css',
                [],
                (string) filemtime($bundle)
            );
            return;
        }

        // Fallback for first install (bundle hasn't been built yet) — enqueue
        // source files individually. Run ./bin/build-css.sh to generate the
        // bundle and drop back to the fast path above.
        $tokens = ['colors', 'typography', 'spacing', 'effects', 'icons', 'fonts'];
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
        foreach (['main.css' => 'bca-main', 'base.css' => 'bca-base', 'site.css' => 'bca-site', 'sections.css' => 'bca-sections'] as $file => $handle) {
            $path = BCA_CHILD_THEME_PATH . "/assets/css/{$file}";
            if (file_exists($path)) {
                wp_enqueue_style($handle, BCA_CHILD_THEME_URI . "/assets/css/{$file}", [], filemtime($path));
            }
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
