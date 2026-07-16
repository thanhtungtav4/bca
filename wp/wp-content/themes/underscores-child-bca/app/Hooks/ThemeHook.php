<?php

declare(strict_types=1);

namespace Theme\Child\Hooks;

defined('ABSPATH') || exit;

/**
 * Child-owned common CSS/JS pipeline.
 *
 * Fires the parent extension points so context hooks still work:
 *   underscores_before_common_css / underscores_after_common_css
 *   underscores_before_common_js  / underscores_after_common_js
 */
final class ThemeHook
{
    public static function register(): void
    {
        $self = new self();
        add_action('wp_enqueue_scripts', [$self, 'enqueue_common_css_assets'], 10);
        add_action('wp_enqueue_scripts', [$self, 'enqueue_common_js_assets'], 10);
    }

    public function enqueue_common_css_assets(): void
    {
        // ponytail: parent /template/css/style.css + backdoor.css don't exist
        // in this repo/deploy (old build-only mount) -> 404. Dropped. Child
        // pipeline (assets/css/*) owns all real CSS. Re-add if /template ships.

        do_action('underscores_before_common_css');

        wp_enqueue_style(
            'underscores-main-style',
            get_stylesheet_uri(),
            [],
            underscores_child_asset_version('style.css')
        );

        wp_enqueue_style(
            'underscores-child-style',
            underscores_child_asset_uri('assets/css/child-theme.css'),
            ['underscores-main-style'],
            underscores_child_asset_version('assets/css/child-theme.css')
        );

        do_action('underscores_after_common_css');
    }

    public function enqueue_common_js_assets(): void
    {
        // ponytail: /template/assets/library/* (swiper, aos, gsap, fancybox,
        // select2, smoothscroll, scrolltrigger, splitting), /template/js/main.js
        // and assets/scripts/underscores-frontend.js all 404 (don't exist in
        // repo/deploy). Dropped. Re-add per-library when a section actually
        // needs it and the file ships.

        do_action('underscores_before_common_js');

        wp_enqueue_script(
            'underscores-child-script',
            underscores_child_asset_uri('assets/scripts/child-theme.js'),
            ['jquery'],
            underscores_child_asset_version('assets/scripts/child-theme.js'),
            true
        );

        do_action('underscores_after_common_js');
    }
}
