<?php

declare(strict_types=1);

namespace Theme\Child\Taxonomies;

use Theme\Child\PostTypes\LeaderPostType;

defined('ABSPATH') || exit;

/**
 * Leader Group taxonomy.
 *
 * Groups leaders into "Management Team" and "Advisors" (or any other bucket
 * the firm wants to expose publicly). Attached to the `leader` CPT.
 */
final class LeaderGroupTaxonomy
{
    public const TAXONOMY = 'leader_group';

    public static function register(): void
    {
        $self = new self();
        add_action('init', [$self, 'register_taxonomy']);
    }

    public function register_taxonomy(): void
    {
        register_taxonomy(self::TAXONOMY, [LeaderPostType::POST_TYPE], [
            'labels' => [
                'name'              => __('Leader Groups', 'bca'),
                'singular_name'     => __('Leader Group', 'bca'),
                'search_items'      => __('Search Groups', 'bca'),
                'all_items'         => __('All Groups', 'bca'),
                'parent_item'       => __('Parent Group', 'bca'),
                'parent_item_colon' => __('Parent Group:', 'bca'),
                'edit_item'         => __('Edit Group', 'bca'),
                'update_item'       => __('Update Group', 'bca'),
                'add_new_item'      => __('Add New Group', 'bca'),
                'new_item_name'     => __('New Group Name', 'bca'),
                'menu_name'         => __('Groups', 'bca'),
            ],
            'hierarchical'      => true,
            'public'            => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => [
                'slug'       => 'leader-group',
                'with_front' => false,
            ],
        ]);
    }
}
