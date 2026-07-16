<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

/**
 * Apply modal — career detail page.
 *
 * Args:
 *   - role     (string) — job title to show
 *   - post_id  (int)    — career post id (kept for context, not strictly used by CF7)
 *
 * Form: rendered via Contact Form 7 (form id 85 by default; admin can override
 * via Theme Settings > Footer > Career apply shortcode).
 *
 * Modal is opened by client-side script (see single-career.php) when user
 * clicks "Apply" on career detail.
 */

$settings = $args ?? [];

$role    = $settings['role']    ?? '';
$post_id = (int) ($settings['post_id'] ?? 0);

// CF7 form id — can be overridden via Theme Settings > Forms (CF7) > Career shortcode.
$cf7_settings = function_exists('underscores_get_option') ? underscores_get_option('cf7_shortcodes') : [];
$cf7_settings = is_array($cf7_settings) ? $cf7_settings : [];
$cf7_career_shortcode = !empty($cf7_settings['career']) ? $cf7_settings['career'] : '[contact-form-7 id="85" title="Career Apply"]';
?>
<div class="bca-apply-modal" hidden role="dialog" aria-modal="true" aria-labelledby="bca-apply-title">
    <div class="bca-apply-modal-overlay" data-apply-close></div>
    <div class="bca-apply-modal-card">
        <button class="bca-apply-modal-close" data-apply-close aria-label="<?php esc_attr_e('Close', 'bca'); ?>">&times;</button>
        <h3 id="bca-apply-title" class="bca-apply-modal-title"><?php echo esc_html($role); ?></h3>
        <p class="bca-apply-modal-sub"><?php esc_html_e('Send us your CV', 'bca'); ?></p>

        <div class="bca-apply-form">
            <?php echo do_shortcode($cf7_career_shortcode); ?>
        </div>
    </div>
</div>
