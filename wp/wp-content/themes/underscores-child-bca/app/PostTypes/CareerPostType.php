<?php

declare(strict_types=1);

namespace Theme\Child\PostTypes;

defined('ABSPATH') || exit;

/**
 * Career Post Type.
 *
 * Maps to BCA Partners "Open Positions" — job listings with type, location
 * and description, each with a detail page + apply modal.
 */
final class CareerPostType
{
    public const POST_TYPE = 'career';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Careers', 'bca'),
                'singular_name'      => __('Career', 'bca'),
                'add_new_item'       => __('Add New Position', 'bca'),
                'edit_item'          => __('Edit Position', 'bca'),
                'all_items'          => __('All Positions', 'bca'),
                'menu_name'          => __('Careers', 'bca'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-businessperson',
            'menu_position'       => 8,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'             => [
                'slug'       => 'career',
                'with_front' => false,
            ],
        ]);
    }
}
