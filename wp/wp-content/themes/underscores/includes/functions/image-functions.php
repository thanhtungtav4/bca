<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Optimize custom logo attributes for LCP and CLS.
 *
 * @param array $attrs
 * @return array
 */
if ( ! function_exists( 'underscores_optimize_custom_logo_attrs' ) ) {
    function underscores_optimize_custom_logo_attrs($attrs)
    {
        $attrs['fetchpriority'] = 'high';
        unset($attrs['loading']);

        // Add explicit dimensions to reduce layout shift.
        $custom_logo_id = get_theme_mod('custom_logo');
        if ($custom_logo_id) {
            $meta = wp_get_attachment_metadata($custom_logo_id);
            if ($meta && isset($meta['width']) && isset($meta['height'])) {
                $attrs['width'] = $meta['width'];
                $attrs['height'] = $meta['height'];
            }
        }

        return $attrs;
    }
}
