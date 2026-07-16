<?php
/**
 * Header template — BCA Partners.
 *
 * Loads the BCA design-system tokens + main stylesheet and the nav walker.
 * The actual <nav> markup is rendered by header-nav partial.
 *
 * @package BCA_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

// Body class — front-end + admin preview aware.
if (wp_is_mobile()) {
    $body = 'mobile-detect';
} else {
    $body = 'desktop-detect';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#003F6F">
    <?php wp_head(); ?>
</head>
<body <?php body_class($body); ?>>
<?php wp_body_open(); ?>
<?php
/**
 * Header rendered by partial. Hooked here so child themes can override
 * the entire <header> block by re-implementing the partial.
 */
if (locate_template('partials/header/site-header.php')) {
    get_template_part('partials/header/site-header');
}
