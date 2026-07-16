<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

$args = $args ?? [];

/**
 * Privacy content — wysiwyg body.
 *
 * Args:
 *   - body (string, wysiwyg content)
 */

$body = $args['body'] ?? '';
if ($body === '') {
    return;
}
?>
<section class="bca-section" id="privacy-content">
    <div class="bca-section-inner bca-privacy-body">
        <?php echo wp_kses_post($body); ?>
    </div>
</section>
