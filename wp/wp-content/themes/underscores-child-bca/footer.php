<?php
/**
 * Footer template — BCA Partners.
 *
 * @package BCA_Child
 */

if (!defined('ABSPATH')) {
    exit;
}

if (locate_template('partials/footer/site-footer.php')) {
    get_template_part('partials/footer/site-footer');
}
?>

<?php wp_footer(); ?>
</body>
</html>
