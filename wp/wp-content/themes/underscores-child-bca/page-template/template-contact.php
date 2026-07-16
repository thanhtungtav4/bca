<?php

declare(strict_types=1);

/**
 * Template Name: Contact
 *
 * Sections (from ACF group_page_contact):
 *   1. hero_settings       → partials/sections/hero.php
 *   2. form_settings       → partials/sections/contact-form.php
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-contact');

get_header();

$fields = function_exists('get_fields') ? (get_fields() ?: []) : [];

$hero_settings = $fields['hero_settings'] ?? [];
$form_settings = $fields['form_settings'] ?? [];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'contact-hero',
        'variant'    => 'page',
        'tone'       => 'blue',
    ]);

    bca_render_section($form_settings, 'partials/sections/contact-form', [
        'section_id' => 'contact-form',
    ]);
    ?>

</main>

<?php
get_footer();
