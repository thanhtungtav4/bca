<?php

declare(strict_types=1);

namespace Theme\Hooks;

defined('ABSPATH') || exit;

/**
 * Image-related output filters.
 */
final class ImageHook
{
    public static function register(): void
    {
        $self = new self();
        add_filter('wp_get_attachment_image_attributes', [$self, 'remove_sizes_attr'], 10, 1);
        add_filter('post_thumbnail_html', [$self, 'fallback_thumbnail'], 20, 1);
    }

    public function remove_sizes_attr(array $attr): array
    {
        unset($attr['sizes']);
        return $attr;
    }

    public function fallback_thumbnail($html)
    {
        if (empty($html)) {
            return '<img src="' . esc_url(UNDERSCORES_THEME_PATH_URI . '/assets/images/default-thumbnail.jpg') . '" alt="' . esc_attr(get_bloginfo('name')) . '"/>';
        }

        return $html;
    }
}
