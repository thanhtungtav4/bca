<?php

declare(strict_types=1);

/**
 * Template Name: Privacy
 *
 * Sections (from ACF group_page_privacy):
 *   1. hero_settings    → partials/sections/hero.php (navy)
 *   2. content_settings → partials/sections/privacy-content.php
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-privacy');

get_header();

$fields = function_exists('get_fields') ? (get_fields() ?: []) : [];

$hero_settings    = $fields['hero_settings']    ?? [];
$content_settings = $fields['content_settings'] ?? [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'privacy-hero',
        'variant'    => 'page',
        'tone'       => 'navy',
    ]);

    bca_render_section($content_settings, 'partials/sections/privacy-content', [
        'section_id' => 'privacy-content',
    ]);
    ?>

</main>

<?php
get_footer();
