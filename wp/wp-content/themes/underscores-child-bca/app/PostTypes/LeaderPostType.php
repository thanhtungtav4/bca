<?php

declare(strict_types=1);

namespace Theme\Child\PostTypes;

defined('ABSPATH') || exit;

/**
 * Leader Post Type.
 *
 * Maps to BCA Partners "Our Leadership" — Managing Directors, Advisors.
 * Grouped by the `leader_group` taxonomy (Management Team / Advisors).
 */
final class LeaderPostType
{
    public const POST_TYPE = 'leader';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Leadership', 'bca'),
                'singular_name'      => __('Leader', 'bca'),
                'add_new_item'       => __('Add New Leader', 'bca'),
                'edit_item'          => __('Edit Leader', 'bca'),
                'all_items'          => __('All Leaders', 'bca'),
                'menu_name'          => __('Leadership', 'bca'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-groups',
            'menu_position'       => 9,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'taxonomies'          => ['leader_group'],
            'rewrite'             => [
                'slug'       => 'leadership',
                'with_front' => false,
            ],
        ]);
    }
}
