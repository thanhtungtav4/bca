<?php

declare(strict_types=1);

/**
 * Default page template — fallback for any page without a custom template.
 *
 * Renders the post content via the editor (Wysiwyg / blocks).
 * Layout stays minimal: hero (page title + featured image) + content.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('page-default');

get_header();
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-page-default">
        <header class="bca-page-header">
            <div class="bca-section-inner">
                <h1 class="bca-page-title"><?php echo esc_html(get_the_title()); ?></h1>
            </div>
        </header>

        <section class="bca-section">
            <div class="bca-section-inner bca-page-content">
                <?php
                while (have_posts()) :
                    the_post();
                    the_content();
                endwhile;
                ?>
            </div>
        </section>
    </article>
</main>

<?php
get_footer();
