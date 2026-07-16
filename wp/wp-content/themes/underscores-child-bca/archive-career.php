<?php

declare(strict_types=1);

/**
 * Archive: Career — list of open positions.
 *
 * @package BCA_Child
 */

defined('ABSPATH') || exit;

bca_set_main_class('archive-career');

get_header();

// Hero data — kept here (not ACF) since the design copy rarely changes.
$hero_settings = [
    'is_show'    => 1,
    'heading'    => 'Career',
    'subheading' => 'Join a team that turns complex business challenges into practical solutions. We recruit talent from diverse backgrounds and invest in their growth.',
    'image'      => 0,
];
?>

<main id="main" <?php bca_main_class(); ?>>

    <?php
    bca_render_section($hero_settings, 'partials/sections/hero', [
        'section_id' => 'career-hero',
        'variant'    => 'page',
    ]);
    ?>

    <section class="bca-section">
        <div class="bca-section-inner">
            <h2 class="bca-section-heading bca-section-heading--sm"><?php esc_html_e('Open Positions', 'bca'); ?></h2>

            <?php if (have_posts()): ?>
            <div class="bca-career-list">
                <?php while (have_posts()): the_post();
                    $type = function_exists('get_field') ? (get_field('type') ?: '') : '';
                    $location = function_exists('get_field') ? (get_field('location') ?: '') : '';
                    $desc = function_exists('get_field') ? (get_field('short_description') ?: '') : '';
                    if (!$desc && has_excerpt()) { $desc = get_the_excerpt(); }
                    get_template_part('partials/components/card-career', null, [
                        'post_id'           => get_the_ID(),
                        'title'             => get_the_title(),
                        'permalink'         => get_permalink(),
                        'type'              => $type,
                        'location'          => $location,
                        'desc'              => $desc,
                    ]);
                endwhile; ?>
            </div>
            <?php else: ?>
                <p class="bca-empty"><?php esc_html_e('No open positions right now.', 'bca'); ?></p>
            <?php endif; ?>

            <div class="bca-career-cta-band">
                <div>
                    <h3><?php esc_html_e("Don't see a role that fits?", 'bca'); ?></h3>
                    <p><?php esc_html_e("Send us your CV — we're always keen to meet talented people.", 'bca'); ?></p>
                </div>
                <a class="bca-btn bca-btn--filled" href="<?php echo esc_url(home_url('/career/#apply')); ?>">
                    <?php esc_html_e('Send your CV', 'bca'); ?>
                    <span class="bca-btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
