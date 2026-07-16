<?php

declare(strict_types=1);

/**
 * Template Name: About
 *
 * Sections (all from ACF group_page_about):
 *   1. hero_settings            → partials/sections/hero.php
 *   2. strengths_settings       → partials/sections/strengths.php
 *   3. belief_settings          → partials/sections/belief.php
 *   4. vmcv_settings            → partials/sections/vision-mission.php
 *   5. contact_band_settings    → partials/sections/contact-band.php
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-about');

get_header();

$fields = function_exists('get_fields') ? (get_fields() ?: []) : [];

$hero_settings         = $fields['hero_settings']         ?? [];
$strengths_settings    = $fields['strengths_settings']    ?? [];
$belief_settings       = $fields['belief_settings']       ?? [];
$vmcv_settings         = $fields['vmcv_settings']         ?? [];
$contact_band_settings = $fields['contact_band_settings'] ?? [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'about-hero',
        'variant'    => 'page',
    ]);

    bca_render_section($strengths_settings, 'partials/sections/strengths', [
        'section_id' => 'about-strengths',
    ]);

    bca_render_section($belief_settings, 'partials/sections/belief', [
        'section_id' => 'about-belief',
    ]);

    bca_render_section($vmcv_settings, 'partials/sections/vision-mission', [
        'section_id' => 'about-vmcv',
    ]);

    bca_render_section($contact_band_settings, 'partials/sections/contact-band', [
        'section_id' => 'about-contact',
    ]);
    ?>

</main>

<?php
get_footer();
