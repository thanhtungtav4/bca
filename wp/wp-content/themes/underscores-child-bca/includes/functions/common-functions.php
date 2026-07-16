<?php
declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('underscores_get_option')) {
    function underscores_get_option(string $field_name, $default = null)
    {
        if (!function_exists('get_field')) {
            return $default;
        }

        $value = get_field($field_name, 'option');

        if ($value === null || $value === '' || $value === []) {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('underscores_child_asset_path')) {
    function underscores_child_asset_path(string $relative_path): string
    {
        return UNDERSCORES_CHILD_THEME_PATH . '/' . ltrim($relative_path, '/');
    }
}

if (!function_exists('underscores_child_asset_uri')) {
    function underscores_child_asset_uri(string $relative_path): string
    {
        return UNDERSCORES_CHILD_THEME_URI . '/' . ltrim($relative_path, '/');
    }
}

if (!function_exists('underscores_child_asset_version')) {
    function underscores_child_asset_version(string $relative_path): string
    {
        $asset_path = underscores_child_asset_path($relative_path);

        if (file_exists($asset_path)) {
            return (string) filemtime($asset_path);
        }

        return UNDERSCORES_CHILD_THEME_VERSION ?: '1.0.0';
    }
}
