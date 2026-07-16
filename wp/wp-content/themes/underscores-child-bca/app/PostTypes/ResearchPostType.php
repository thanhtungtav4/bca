<?php

declare(strict_types=1);

namespace Theme\Child\PostTypes;

defined('ABSPATH') || exit;

/**
 * Research Post Type.
 *
 * Maps to BCA Partners "Research / Insights" — long-form analysis pieces
 * (FINTECH, MSME FINANCE, AGRICULTURE) with eyebrow and detail page.
 */
final class ResearchPostType
{
    public const POST_TYPE = 'research';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_post_type']);
    }

    public function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'               => __('Research', 'bca'),
                'singular_name'      => __('Research Article', 'bca'),
                'add_new_item'       => __('Add New Research', 'bca'),
                'edit_item'          => __('Edit Research', 'bca'),
                'all_items'          => __('All Research', 'bca'),
                'menu_name'          => __('Research', 'bca'),
            ],
            'public'              => true,
            'has_archive'         => true,
            'show_in_rest'        => true,
            'menu_icon'           => 'dashicons-chart-line',
            'menu_position'       => 7,
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite'             => [
                'slug'       => 'research',
                'with_front' => false,
            ],
        ]);
    }
}
