<?php
add_action('wp_loaded', function() {
    add_action('template_include', function($template) {
        if (strpos($template, 'front-page') !== false) {
            add_action('loop_start', function() {
                $fields = get_fields();
                error_log("FRONT-PAGE get_fields: " . (is_array($fields) ? "array with " . count($fields) . " keys: " . implode(',', array_keys($fields)) : "NOT ARRAY"));
            }, 1);
        }
        return $template;
    });
});
