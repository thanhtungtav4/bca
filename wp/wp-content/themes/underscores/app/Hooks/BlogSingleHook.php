<?php

declare(strict_types=1);

namespace Theme\Hooks;

defined('ABSPATH') || exit;

/**
 * Single post context assets + body class.
 */
final class BlogSingleHook
{
    public static function register(): void
    {
        $self = new self();
        add_action('underscores_before_common_css', [$self, 'before_common_css'], 10);
        add_action('underscores_after_common_css', [$self, 'after_common_css']);
        add_action('underscores_before_common_js', [$self, 'before_common_js']);
        add_filter('body_class', [$self, 'body_class']);
    }

    private function matches(): bool
    {
        return is_singular('post');
    }

    public function before_common_css($prefix): void
    {
        if ($this->matches()) {
            wp_enqueue_style($prefix . 'swipper', UNDERSCORES_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.css');
        }
    }

    public function after_common_css($prefix): void
    {
        if ($this->matches()) {
            wp_enqueue_style($prefix . 'news', UNDERSCORES_SITE_TEMPLATE_URL . '/assets/css/news.css');
        }
    }

    public function before_common_js(): void
    {
        if ($this->matches()) {
            wp_enqueue_script(
                'underscores-template-swiper',
                UNDERSCORES_SITE_TEMPLATE_URL . '/assets/js/library/swiper/swiper-bundle.min.js',
                array(),
                UNDERSCORES_THEME_VERSION,
                array('in_footer' => true)
            );
        }
    }

    public function body_class(array $classes): array
    {
        if ($this->matches()) {
            $classes[] = 'newsdtpage';
        }

        return $classes;
    }
}
