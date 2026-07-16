<?php

declare(strict_types=1);

/**
 * Single: Career — position detail + apply modal.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('single-career');

get_header();

while (have_posts()) : the_post();
    $post_id = get_the_ID();
    $type = function_exists('get_field') ? (get_field('type', $post_id) ?: '') : '';
    $location = function_exists('get_field') ? (get_field('location', $post_id) ?: '') : '';
    $responsibilities = function_exists('get_field') ? (get_field('responsibilities', $post_id) ?: '') : '';
    $requirements = function_exists('get_field') ? (get_field('requirements', $post_id) ?: '') : '';
?>

<main id="main" <?php bca_main_class(); ?>>
    <article class="bca-single bca-single--career">

        <section class="bca-section">
            <div class="bca-section-inner">
                <a class="bca-back-link" href="<?php echo esc_url(get_post_type_archive_link('career')); ?>">&larr; <?php esc_html_e('All open positions', 'bca'); ?></a>
                <h1 class="bca-section-heading"><?php the_title(); ?></h1>

                <div class="bca-career-row-meta">
                    <?php if ($type): ?><span class="bca-career-tag"><?php echo esc_html($type); ?></span><?php endif; ?>
                    <?php if ($location): ?><span class="bca-career-tag"><?php echo esc_html($location); ?></span><?php endif; ?>
                </div>

                <?php if ($responsibilities): ?>
                    <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Responsibilities', 'bca'); ?></h2>
                    <div class="bca-wysiwyg"><?php echo wp_kses_post($responsibilities); ?></div>
                <?php endif; ?>

                <?php if ($requirements): ?>
                    <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Requirements', 'bca'); ?></h2>
                    <div class="bca-wysiwyg"><?php echo wp_kses_post($requirements); ?></div>
                <?php endif; ?>

                <?php if (trim((string) get_the_content()) !== ''): ?>
                    <div class="bca-wysiwyg"><?php the_content(); ?></div>
                <?php endif; ?>

                <div class="bca-career-cta-band" id="apply">
                    <div>
                        <h3><?php esc_html_e('Interested?', 'bca'); ?></h3>
                        <p><?php esc_html_e('Send us your CV and a short cover note. We will get back within a week.', 'bca'); ?></p>
                    </div>
                    <button type="button" class="bca-btn bca-btn--filled" data-apply-open>
                        <?php esc_html_e('Apply now', 'bca'); ?>
                        <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </div>
        </section>

        <?php get_template_part('partials/components/apply-modal', null, ['role' => get_the_title(), 'post_id' => $post_id]); ?>
    </article>

    <script>
    document.addEventListener('click', function(e){
        var open = e.target.closest('[data-apply-open]');
        var close = e.target.closest('[data-apply-close]');
        var modal = document.querySelector('.bca-apply-modal');
        if (!modal) return;
        if (open) { modal.hidden = false; document.body.style.overflow = 'hidden'; }
        if (close) { modal.hidden = true; document.body.style.overflow = ''; }
    });
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') {
            var modal = document.querySelector('.bca-apply-modal');
            if (modal) { modal.hidden = true; document.body.style.overflow = ''; }
        }
    });
    </script>
</main>

<?php
endwhile;
get_footer();
