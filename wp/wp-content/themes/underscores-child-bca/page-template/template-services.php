<?php

declare(strict_types=1);

/**
 * Template Name: Services
 *
 * Sections (from ACF group_page_services):
 *   1. hero_settings            → partials/sections/hero.php
 *   2. (no service list section — services come from CPT 'service' archive-style rendering below)
 *   3. contact_band_settings    → partials/sections/contact-band.php
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-services');

get_header();

$fields = function_exists('get_fields') ? (get_fields() ?: []) : [];

$hero_settings         = $fields['hero_settings']         ?? [];
$contact_band_settings = $fields['contact_band_settings'] ?? [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'services-hero',
        'variant'    => 'page',
    ]);
    ?>

    <?php
    /**
     * Services list — query the 'service' CPT.
     * Render via the card-service partial for DRY (reused on home teaser).
     */
    $service_query = new WP_Query([
        'post_type'      => 'service',
        'posts_per_page' => 12,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);
    if ($service_query->have_posts()) : ?>
        <section class="bca-section" id="services-list">
            <div class="bca-section-inner">
                <div class="bca-services-grid">
                    <?php while ($service_query->have_posts()) : $service_query->the_post();
                        $post_id = get_the_ID();
                        $thumb_id = (int) get_post_thumbnail_id($post_id);
                        $items = function_exists('get_field') ? (get_field('items', $post_id) ?: []) : [];
                        get_template_part('partials/components/card-service', null, [
                            'post_id'   => $post_id,
                            'title'     => get_the_title(),
                            'permalink' => get_permalink(),
                            'image_id'  => $thumb_id,
                            'items'     => $items,
                        ]);
                    endwhile;
                    wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php
    bca_render_section($contact_band_settings, 'partials/sections/contact-band', [
        'section_id' => 'services-contact',
    ]);
    ?>

</main>

<?php
get_footer();
