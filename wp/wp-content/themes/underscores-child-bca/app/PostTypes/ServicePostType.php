<?php

declare(strict_types=1);

namespace Theme\Child\PostTypes;

defined('ABSPATH') || exit;

/**
 * Service Post Type.
 *
 * Maps to BCA Partners "Our Services" — 6 service offerings, each with its
 * own detail page and capability list.
 */
final class ServicePostType
{
    public const POST_TYPE = 'service';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Services', 'bca'),
                'singular_name'      => __('Service', 'bca'),
                'add_new_item'       => __('Add New Service', 'bca'),
                'edit_item'          => __('Edit Service', 'bca'),
                'all_items'          => __('All Services', 'bca'),
                'menu_name'          => __('Services', 'bca'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-portfolio',
            'menu_position'       => 5,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'             => [
                'slug'       => 'services',
                'with_front' => false,
            ],
        ]);
    }
}
