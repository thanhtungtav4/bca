<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('underscores_child_render_flexible_sections')) {
    function underscores_child_render_flexible_sections(string $field_name = 'sections', ?int $post_id = null): bool
    {
        $rendered = false;

        if (!function_exists('have_rows')) {
            return false;
        }

        $post_id = $post_id ?: (int) get_the_ID();

        if ($post_id <= 0 || !have_rows($field_name, $post_id)) {
            return false;
        }

        while (have_rows($field_name, $post_id)) {
            the_row();

            $layout = (string) get_row_layout();

            if ($layout === '') {
                continue;
            }

            $template_slug = 'partials/sections/' . str_replace('_', '-', $layout);

            if (!locate_template($template_slug . '.php', false, false)) {
                continue;
            }

            get_template_part(
                $template_slug,
                null,
                [
                    'layout' => $layout,
                    'post_id' => $post_id,
                ]
            );

            $rendered = true;
        }

        return $rendered;
    }
}
