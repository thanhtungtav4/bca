<?php

declare(strict_types=1);

/**
 * Front page template — BCA Partners Home.
 *
 * Sections (all from ACF group_page_home):
 *   1. hero_settings            → partials/sections/hero.php
 *   2. insights_settings        → partials/sections/insights.php (4 horizontal cards)
 *   3. about_teaser_settings    → partials/sections/about-teaser.php (image + Mission/Value)
 *   4. services_settings        → partials/sections/services-teaser.php (lists CPT 'service')
 *   5. partners_settings        → partials/sections/partners.php (logos)
 *   6. highlights_settings      → partials/sections/projects-highlights.php (1 big + 2 small)
 *   7. leadership_settings      → partials/sections/leadership-teaser.php (lists CPT 'leader')
 *   8. contact_band_settings    → partials/sections/contact-band.php
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-home');

get_header();

$fields = function_exists('get_fields') ? (get_fields() ?: []) : [];

$hero_settings         = $fields['hero_settings']         ?? [];
$insights_settings     = $fields['insights_settings']     ?? [];
$about_teaser_settings = $fields['about_teaser_settings'] ?? [];
$services_settings     = $fields['services_settings']     ?? [];
$partners_settings     = $fields['partners_settings']     ?? [];
$highlights_settings   = $fields['highlights_settings']   ?? [];
$leadership_settings   = $fields['leadership_settings']   ?? [];
$contact_band_settings = $fields['contact_band_settings'] ?? [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    // Hero — only when hero_settings is filled AND is_show is true.
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'home-hero',
        'variant'    => 'home',
    ]);

    // Insights — horizontal row of featured content cards (admin-edited repeater).
    bca_render_section($insights_settings, 'partials/sections/insights', [
        'section_id' => 'home-insights',
    ]);

    // About teaser — image + About / Mission / Value text.
    bca_render_section($about_teaser_settings, 'partials/sections/about-teaser', [
        'section_id' => 'home-about-teaser',
    ]);

    // Services teaser — list rendered from CPT 'service'.
    bca_render_section($services_settings, 'partials/sections/services-teaser', [
        'section_id' => 'home-services',
    ]);

    // Partners — row of client/partner logos.
    bca_render_section($partners_settings, 'partials/sections/partners', [
        'section_id' => 'home-partners',
    ]);

    // Project highlights — 1 big project left + 2 small stacked right.
    bca_render_section($highlights_settings, 'partials/sections/projects-highlights', [
        'section_id' => 'home-highlights',
    ]);

    // Leadership teaser — list rendered from CPT 'leader'.
    bca_render_section($leadership_settings, 'partials/sections/leadership-teaser', [
        'section_id' => 'home-leadership',
    ]);

    // Contact band — CTA strip near bottom of page.
    bca_render_section($contact_band_settings, 'partials/sections/contact-band', [
        'section_id' => 'home-contact',
    ]);
    ?>

</main>

<?php
get_footer();
