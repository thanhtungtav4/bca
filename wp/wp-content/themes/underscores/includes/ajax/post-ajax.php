<?php
defined('ABSPATH') || exit;

if (! function_exists('underscores_ajax_get_posts')) {
    /**
     * AJAX endpoint for post listing with optional taxonomy filters.
     *
     * Request params:
     * - posts_per_page (int, optional, clamped to 1..50)
     * - paged (int, optional, >= 1)
     * - taxonomies (array, optional): [taxonomy => [term_id, ...]]
     */
    function underscores_ajax_get_posts() {
        try {
            if (! check_ajax_referer('underscores-ajax-security', 'security', false)) {
                throw new Exception(__('Hành động không được xác thực', 'underscores'));
            }

            $response = [
                'success' => true,
                'data' => [],
            ];

            // Normalize pagination input to safe bounds.
            $posts_per_page = filter_input(INPUT_POST, 'posts_per_page', FILTER_VALIDATE_INT);
            if (! $posts_per_page) {
                $posts_per_page = UNDERSCORES_POSTS_PER_PAGE;
            }
            $posts_per_page = max(1, min(50, (int) $posts_per_page));

            $paged = filter_input(INPUT_POST, 'paged', FILTER_VALIDATE_INT);
            if (! $paged) {
                $paged = 1;
            }
            $paged = max(1, (int) $paged);

            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'fields' => 'ids',
                // Keep relation fixed so filters can be appended conditionally below.
                'tax_query' => ['relation' => 'AND'],
                'orderby' => ['date' => 'DESC'],
            ];

            /**
             * Input format
             * 
             * [
             *     'category' => [1, 2],
             *     'post_tag' => [1, 2],
             * ]
             */
            $raw_taxonomies = isset($_POST['taxonomies']) ? wp_unslash($_POST['taxonomies']) : [];
            if (! empty($raw_taxonomies) && is_array($raw_taxonomies)) {
                foreach ($raw_taxonomies as $taxonomy => $terms) {
                    if (! is_array($terms)) continue;

                    // Only allow valid, registered taxonomy keys.
                    $taxonomy = sanitize_key((string) $taxonomy);
                    if (empty($taxonomy) || ! taxonomy_exists($taxonomy)) {
                        continue;
                    }

                    // Terms are expected as integer term IDs.
                    $sanitized_terms = array_filter(array_map('absint', $terms));
                    if (empty($sanitized_terms)) {
                        continue;
                    }

                    $args['tax_query'][] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'term_id',
                        'terms' => $sanitized_terms,
                    ];
                }
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                $response['data']['posts'] = [];
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();

                    $response['data']['posts'][] = [
                        'id' => $post_id,
                        'title' => get_the_title($post_id),
                        'thumbnail' => get_the_post_thumbnail($post_id, 'full'),
                        'permalink' => get_the_permalink($post_id),
                        'date' => get_the_date('d/m/Y', $post_id),
                        'author' => get_the_author_meta('display_name', (int) get_post_field('post_author', $post_id)),
                    ];
                }

                // Return HTML pagination because frontend currently renders server markup.
                $response['data']['pagination_html'] = underscores_pagination_links($query, $paged);
            } else {
                $response['data']['empty_message'] = __('Không có bài viết nào được tìm thấy', 'underscores');
            }
            
            wp_send_json($response);
        } catch (\Throwable $th) {
            wp_send_json([
                'success' => false,
                'errors' => [
                    __('Đã xảy ra sự cố', 'underscores'),
                    // $th->getMessage(), // Debug
                ],
            ]);
        }

        wp_die();
    }
}
