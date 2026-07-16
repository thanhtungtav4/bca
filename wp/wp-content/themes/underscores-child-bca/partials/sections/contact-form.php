<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Contact form + details section — Contact page.
 *
 * Args:
 *   - heading         (string)
 *   - intro           (string)
 *   - recipient_email (string)  — optional; falls back to Theme Settings general email
 *   - success_message (string)  — optional; informational copy
 *
 * Form: rendered via Contact Form 7 (form id 84 by default; admin can override
 * via Theme Settings > Footer > Contact form shortcode).
 *
 * Contact details (office, phone, email) come from Theme Settings, not from
 * this field group, so they're shared with header/footer.
 */

$settings = $args ?? [];

$heading         = $settings['heading']         ?? '';
$intro           = $settings['intro']           ?? '';
$recipient_email = $settings['recipient_email'] ?? '';
$success_message = $settings['success_message'] ?? 'Thank you — your message has been sent. We will be in touch shortly.';

$general = function_exists('underscores_get_option') ? underscores_get_option('general_section') : [];
$general = is_array($general) ? $general : [];

$office_addr  = $general['address'] ?? '';
$hotline      = $general['hotline'] ?? '';
$contact_mail = $general['email']   ?? $recipient_email;

// CF7 form id — can be overridden via Theme Settings > Forms (CF7) > Contact shortcode.
$cf7_settings = function_exists('underscores_get_option') ? underscores_get_option('cf7_shortcodes') : [];
$cf7_settings = is_array($cf7_settings) ? $cf7_settings : [];
$cf7_shortcode = !empty($cf7_settings['contact']) ? $cf7_settings['contact'] : '[contact-form-7 id="84" title="Contact"]';
?>
<section class="bca-section" id="contact-form">
    <div class="bca-section-inner">
        <div class="bca-contact-grid">
            <div class="bca-contact-form-col">
                <h2 class="bca-section-heading bca-section-heading--sm"><?php echo esc_html($heading); ?></h2>
                <?php if ($intro !== ''): ?>
                    <p class="bca-contact-intro"><?php echo esc_html($intro); ?></p>
                <?php endif; ?>

                <div class="bca-form">
                    <?php echo do_shortcode($cf7_shortcode); ?>
                </div>
            </div>

            <aside class="bca-contact-details">
                <h3 class="bca-contact-details-heading"><?php echo esc_html(get_bloginfo('name')); ?></h3>

                <?php if ($office_addr): ?>
                    <p class="bca-contact-line">
                        <span class="bca-contact-line-label"><?php esc_html_e('Office', 'bca'); ?></span>
                        <span class="bca-contact-line-value"><?php echo nl2br(esc_html($office_addr)); ?></span>
                    </p>
                <?php endif; ?>

                <?php if ($hotline): ?>
                    <p class="bca-contact-line">
                        <span class="bca-contact-line-label"><?php esc_html_e('Telephone', 'bca'); ?></span>
                        <a class="bca-contact-line-value" href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $hotline)); ?>"><?php echo esc_html($hotline); ?></a>
                    </p>
                <?php endif; ?>

                <?php if ($contact_mail): ?>
                    <p class="bca-contact-line">
                        <span class="bca-contact-line-label"><?php esc_html_e('Email', 'bca'); ?></span>
                        <a class="bca-contact-line-value" href="mailto:<?php echo esc_attr($contact_mail); ?>"><?php echo esc_html($contact_mail); ?></a>
                    </p>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
