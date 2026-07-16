<?php

declare(strict_types=1);

namespace Theme\Child\PostTypes;

defined('ABSPATH') || exit;

/**
 * Project Post Type.
 *
 * Maps to BCA Partners "Highlighted Projects" — case studies (FINTECH, M&A,
 * market entry, restructuring) with eyebrow, client, body and detail page.
 */
final class ProjectPostType
{
    public const POST_TYPE = 'project';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Projects', 'bca'),
                'singular_name'      => __('Project', 'bca'),
                'add_new_item'       => __('Add New Project', 'bca'),
                'edit_item'          => __('Edit Project', 'bca'),
                'all_items'          => __('All Projects', 'bca'),
                'menu_name'          => __('Projects', 'bca'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-portfolio',
            'menu_position'       => 6,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'             => [
                'slug'       => 'projects',
                'with_front' => false,
            ],
        ]);
    }
}
