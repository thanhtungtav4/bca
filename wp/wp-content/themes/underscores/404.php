<?php 
/**
 * The template for displaying 404.
 *
 * @package Underscores
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <!-- Meta ================================================== -->
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
    <?php wp_site_icon(); ?>
    <link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>" />
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <main>
        <div class="wrapper">
            <div class="underscores-notfound-template">
                <div class="notfound">
                    <div class="notfound-404">
                        <h3><?php echo esc_html__( 'Oops! Page not found', 'underscores' ); ?></h3>
                        <h1><span>4</span><span>0</span><span>4</span></h1>
                    </div>
                    <h2><?php echo esc_html__( 'we are sorry, but the page you requested was not found', 'underscores' ); ?></h2>
                    <a href="<?php echo esc_url(UNDERSCORES_SITE_URL); ?>">
                        <?php echo esc_html__( 'Back to the home page', 'underscores' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
