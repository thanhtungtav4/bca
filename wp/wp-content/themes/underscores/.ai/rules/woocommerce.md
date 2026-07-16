# WooCommerce — Tích hợp chuẩn theme

Mở rộng `wp-core-first.md`. Tích hợp Woo **chuẩn theme classic**, KHÔNG override layout product đợt này.
Nguồn: https://developer.woocommerce.com/docs/theming/ + llms-full.txt.

## Declare support (`after_setup_theme`)

`add_theme_support('woocommerce')` đã có ở `app/Hooks/CommonHook.php::register_menus()`.
Thêm gallery support cạnh đó:

```php
add_theme_support('woocommerce');
add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');
```

## Wrapper — `woocommerce.php` (theme root)

Woo render nội dung qua `woocommerce_content()`. Theme phải bọc wrapper riêng:

```php
// woocommerce.php
get_header();
woocommerce_content();
get_footer();
```

Và unhook wrapper mặc định của Woo, hook lại của theme (trong hook class):

```php
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', fn() => print('<main ' . get_main_class() . '>'), 10);
add_action('woocommerce_after_main_content', fn() => print('</main>'), 10);
```

## Plugin check

`class_exists('WooCommerce')` — KHÔNG `is_plugin_active` (chỉ load admin → frontend fatal).

## Mini-cart (ajax)

- Dùng cart fragments **core**: filter `woocommerce_add_to_cart_fragments` trả HTML mini-cart (count/total).
- Markup từ `WC()->cart` template tag. KHÔNG tự viết ajax handler.

## Hiệu suất (ép buộc)

Chi tiết profiling/caching → skill `wp-performance` (đã có trong repo). Rule này chỉ ép Woo:

- `wc-cart-fragments.js` chạy mọi page = chậm (poll session). Mặc định **dequeue**.
- Re-enqueue conditional khi trang chắc chắn có add-to-cart:

```php
function underscores_needs_cart_fragments(): bool {
    $needs = is_woocommerce() || is_cart() || is_checkout();
    if (!$needs && ($post = get_post())) {
        $needs = has_shortcode($post->post_content, 'products')
            || has_shortcode($post->post_content, 'add_to_cart')
            || has_shortcode($post->post_content, 'product_page');
    }
    // ponytail: detect add-to-cart qua is_woocommerce + shortcode + filter.
    // Ceiling: không bắt nút add-to-cart render bằng template tag thuần ngoài shortcode.
    // Upgrade: page đó add_filter('underscores_needs_cart_fragments','__return_true').
    return apply_filters('underscores_needs_cart_fragments', $needs);
}
add_action('wp_enqueue_scripts', function () {
    if (!underscores_needs_cart_fragments()) {
        wp_dequeue_script('wc-cart-fragments');
    }
}, 99);
```

- Dequeue Woo CSS/JS toàn cục khi `!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()`.
- Giữ `woocommerce_blocks_enqueue_scripts` → `__return_false` (đã có ThemeSetup.php).

## Self-check (bắt buộc khi implement code)

```php
// Minh hoạ — KHÔNG paste trần (assert file-scope sẽ fire mọi page).
// Kiểm tra thủ công: mở /shop → DevTools Network thấy wc-cart-fragments.js;
// mở front-page (không shortcode) → KHÔNG thấy.
// Hoặc test trong context Woo:
add_action('woocommerce_after_main_content', function () {
    assert(underscores_needs_cart_fragments() === true); // đang ở trang Woo
});
```

## Out of scope (đợt này)

- Override template shop/single-product/cart/checkout/archive layout.
- Woo block theme / FSE.
