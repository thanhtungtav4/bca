# Underscores Theme (WordPress)

Theme WordPress custom cho hệ sinh thái Underscores.

- Theme name: `Underscores Theme`
- Current version: `4.3.0`
- Main branch: `main`

## 1) Mục tiêu dự án

Underscores Theme được tổ chức theo hướng:

- tối ưu hiệu năng frontend (enqueue có kiểm soát, defer có chọn lọc)
- tách cấu trúc rõ ràng giữa `classes`, `functions`, `hooks`, `ajax`, `caches`
- hỗ trợ tối ưu ảnh (AVIF/WebP + admin tools regenerate)
- mở rộng bằng cấu hình load file thay vì hard-code nhiều nơi

## 2) Yêu cầu môi trường

- WordPress: `6.x` (khuyến nghị bản mới nhất)
- PHP: `>= 8.1` (khuyến nghị)
- Composer: để cài dependency PHP
- Node.js: không bắt buộc, dùng để check syntax JS

## 3) Cài đặt

1. Đưa source vào:
   - `wp-content/themes/underscores`
2. Cài dependency:
   - `composer install`
3. Kích hoạt theme trong WP Admin.
4. Thiết lập:
   - Front page
   - Posts page
   - Menus (`top-header-menu`, `header-menu`)

## 4) Lưu ý quan trọng về assets

Theme đang dùng 2 nguồn assets:

- Assets trong theme: `assets/...`
- Assets template ngoài theme qua constant:
  - `UNDERSCORES_SITE_TEMPLATE_URL = {site_url}/template`

Điều này nghĩa là server cần có thư mục `/template` hợp lệ (chứa `assets/css/common.css`, `assets/js/main.js`, fonts, icons...) để frontend hoạt động đầy đủ.

## 5) Cấu trúc thư mục

```text
underscores/
├── .ai/                        # Rules + Skills cho AI agent
│   ├── rules/                    # Quy tắc phát triển dự án
│   └── skills/                   # Các kỹ năng tự động hóa (Automation)
├── functions.php                 # define constants + bootstrap
├── composer.json                 # PSR-4 autoload (Theme\ -> inc/src)
├── configs/
│   └── ajax.php                  # map action AJAX -> callback
├── inc/
│   ├── bootstrap.php                  # require helper + ::register() các hook class
│   ├── src/                      # Class PSR-4 (namespace Theme\)
│   │   ├── Setup/ThemeSetup.php
│   │   └── Hooks/{Common,Image,BlogPage,BlogSingle,DefaultPage,Ajax}Hook.php
│   ├── functions/                # Helper global (Prefix: underscores_)
│   └── ajax/                     # AJAX handler (hàm global)
├── partials/templates/           # Template parts (phẳng)
├── assets/                       # css/js/images trong theme
└── vendor/                       # composer autoloader
```

## 6) Bootstrap flow

1. `functions.php`
   - define constants (`UNDERSCORES_THEME_PATH`, `UNDERSCORES_THEME_INC_PATH`, ...)
   - require `vendor/autoload.php`
   - require `includes/bootstrap.php`
2. `includes/bootstrap.php`
   - `require_once` các helper trong `includes/functions/` + `includes/ajax/`
   - gọi `::register()` cho từng hook class (autoload PSR-4):
     - `\Theme\Setup\ThemeSetup::register()`
     - `\Theme\Hooks\CommonHook::register()`, `ImageHook`, `BlogPageHook`, ...
3. `\Theme\Hooks\AjaxHook::register()`
   - đọc map trong `includes/config/ajax.php`
   - đăng ký `wp_ajax_*` và `wp_ajax_nopriv_*`

---

## 7) Agent Integration (Rules & Skills)

Bộ quy tắc + skill cho mọi AI agent (xem `AGENTS.md`). Tất cả ở `.ai/`.

### 7.1 Rules (.ai/rules/)
- **Lazy-First**: Dòng code tốt nhất là dòng không viết — ladder YAGNI → tái dùng → WP core → native → 1 dòng; không cắt validation/security/a11y (triết lý [ponytail](https://github.com/DietrichGebert/ponytail)).
- **WP-Core-First**: Ưu tiên WP API/hook/template tag có sẵn, theo template hierarchy (`wp_nav_menu`, `title-tag`, `wp_get_attachment_image`, `paginate_links`...) — không tự chế lại.
- **Naming Convention**: Đặt tên đồng nhất (`underscores_` prefix, kebab-case template...).
- **Modular Development**: Hook/class trong `app/` (PSR-4), đăng ký qua `::register()` trong `includes/bootstrap.php`.
- **Auto-load System**: Cơ chế tải file (PSR-4 + require helper, không loadFile.php).
- **AJAX Handler**: Bảo mật + phản hồi AJAX (nonce, JSON success/error).
- **ACF Integration**: ACF Local JSON (`acf-json/`, sync qua admin) + Theme Options.
- **Data Rendering**: Không bịa dummy data; guard `?? ''` chống lỗi rỗng, ẩn khối khi trống; không spam helper.
- **Flexible Content**: Mapping Layout name ↔ template file.

### 7.2 Skills nội bộ (.ai/skills/)
- `convert-html-to-wp`: scan file HTML template → plan (vùng nào ACF/CPT/Taxonomy) → **chờ duyệt** → build.
- `create-new-module` · `create-ajax-endpoint` · `create-flexible-section` · `create-cpt` · `add-theme-option`

### 7.3 Skills WordPress chính thức (.ai/skills/, từ [WordPress/agent-skills](https://github.com/WordPress/agent-skills))
- `wp-performance`: caching, DB optimization, profiling, Server-Timing.
- `wp-phpstan`: static analysis (phpstan.neon, baseline, WP typing).
- `wp-playground`: local env tức thì qua WordPress Playground.
- `wp-wpcli-and-ops`: WP-CLI, search-replace, automation, multisite.

---

## 8) Thành phần chính

### 8.1 Theme setup

File: `inc/classes/class-setup-theme.php`

- add/remove hỗ trợ WP core
- cleanup các action/filter không cần thiết ở `wp_head`
- custom login style
- quản lý upload mime (SVG giới hạn theo quyền)

### 8.2 Common hook / Asset pipeline

File: `inc/hooks/CommonHook.php`

- enqueue CSS/JS chung
- localize biến AJAX (`underscores_params`)
- cấu hình script type module
- defer/async tối ưu có chọn lọc

### 8.3 AJAX posts

File: `includes/ajax/PostAjax.php`

Action: `underscores_ajax_get_posts`

Input:

- `posts_per_page` (int, clamp 1..50)
- `paged` (int >= 1)
- `taxonomies` dạng:
  - `{ taxonomy_key: [term_id, ...] }`

Output:

- `success: true|false`
- `data.posts[]`
- `data.pagination_html`
- hoặc `data.empty_message`

## 9) Quy trình phát triển

### 9.1 Cài dependency

```bash
composer install
```

### 9.2 Sử dụng AI Automation
Khi phát triển tính năng mới, ưu tiên dùng các **Skills** đã định nghĩa để code sinh ra đúng chuẩn.

Ví dụ: *"Tạo một AJAX endpoint mới để xử lý form liên hệ"* -> AI sẽ sử dụng skill `create-ajax-endpoint`.

### 9.3 Kiểm tra syntax PHP

```bash
find . -type f -name '*.php' -print0 | while IFS= read -r -d '' f; do
  php -l "$f" || exit 1
done
```

## 10) Quy ước code (bắt buộc)

- **Tuân thủ tuyệt đối các Rules trong `.ai/rules/`**.
- Escape output đúng ngữ cảnh: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`.
- Validate/sanitize input trước query: `sanitize_key`, `sanitize_text_field`, `absint`, ...
- Luồng AJAX luôn có nonce check.
- Không viết query SQL thô nếu WP API đã hỗ trợ.
- Không query nặng trong loop.

## 11) Troubleshooting nhanh

### Lỗi thiếu CSS/JS template

- Kiểm tra thư mục `/template/assets/...` tồn tại trên site.

### AJAX trả lỗi xác thực

- Kiểm tra nonce `underscores-ajax-security` đã được localize vào `underscores_params`.

---

### Visual Guide
Xem chi tiết trình bày về cấu trúc, quy tắc và kỹ năng tại: [preview.html](https://coreai.underscoresweb.dev/aicode/preview.html)

