<?php

declare(strict_types=1);

namespace Theme\Nav;

defined('ABSPATH') || exit;

use Walker_Nav_Menu;

/**
 * Nav walker khớp markup template: <ul class="menu-list"> > <li class="menu-item [dropdown]"> > <a class="menu-link">.
 * Dùng với wp_nav_menu(['walker' => new \Theme\Nav\MenuWalker, 'menu_class' => 'menu-list', 'container' => false]).
 */
final class MenuWalker extends Walker_Nav_Menu
{
    /** @param string $output @param int $depth @param array $args */
    public function start_lvl(&$output, $depth = 0, $args = null): void
    {
        $output .= '<ul class="menu-list">';
    }

    public function end_lvl(&$output, $depth = 0, $args = null): void
    {
        $output .= '</ul>';
    }

    /**
     * @param string $output
     * @param \WP_Post $item
     * @param int $depth
     * @param array $args
     * @param int $id
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
    {
        $classes = ['menu-item'];

        if (in_array('menu-item-has-children', (array) $item->classes, true)) {
            $classes[] = 'dropdown';
        }
        if (in_array('current-menu-item', (array) $item->classes, true)) {
            $classes[] = 'current-menu-item';
        }

        $class_attr = implode(' ', array_map('sanitize_html_class', $classes));
        $url = $item->url ? esc_url($item->url) : '#';
        $title = esc_html($item->title);

        $output .= sprintf('<li class="%s"><a class="menu-link" href="%s">%s</a>', $class_attr, $url, $title);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null): void
    {
        $output .= '</li>';
    }
}
