<?php
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Render default pagination
 * 
 * @param WP_Query $wp_query
 * @param bool $echo
 * 
 * @return string
 */
if ( ! function_exists( 'underscores_pagination_links' ) ) {
    function underscores_pagination_links( $wp_query = null, $echo = false ) {
        if ( empty( $wp_query ) ) {
            global $wp_query;
        }

        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        $bignum = 999999999;

        // Output
        $output = '';
        $output .= '<div class="paginations">';
        $output .= paginate_links( [
            'base'      => str_replace( $bignum, '%#%', esc_url( get_pagenum_link( $bignum ) ) ),
            'format'    => '',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'prev_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow previous" title="Arrow previous" loading="lazy" />',
            'next_text' => '<img src="/template/assets/images/icons/chevron-right.svg" alt="Arrow next" title="Arrow next" loading="lazy" />',
            'type'      => 'list',
            'end_size'  => 2,
            'mid_size'  => 3
        ] );
        $output .= '</div>';

        if ( $echo ) {
            echo $output;
        }
        else {
            return $output;
        }
    }
}

