# Hướng dẫn ACF (Local JSON) - Underscores Theme

Theme dùng **ACF Local JSON** (cơ chế native của ACF Pro). Field group được lưu dưới dạng file `.json`
trong `acf-json/` của **child theme**, sync qua admin UI. KHÔNG dùng `vinkla/extended-acf` (đã gỡ),
KHÔNG định nghĩa field group bằng PHP code.

## 1. CƠ CHẾ

- Field group định nghĩa/sửa trong **wp-admin → Custom Fields**.
- Khi lưu, ACF ghi/cập nhật file `acf-json/group_{key}.json` (nhờ filter `acf/settings/save_json`).
- Khi load, ACF đọc các file trong `acf-json/` (filter `acf/settings/load_json`).
- Cấu hình save/load + đăng ký Options Page nằm ở class `Theme\Child\Acf\LocalJson`
  (`app/Acf/LocalJson.php`), gọi `::register()` trong child `includes/bootstrap.php`.

Yêu cầu: **ACF Pro** cài trên site (đã có).

## 2. THƯ MỤC

```
underscores-child/
├── acf-json/                       # Field group JSON (commit vào git)
│   ├── group_page_about.json
│   ├── group_page_contact.json
│   └── group_theme_settings.json
└── app/Acf/LocalJson.php       # save/load paths + options page
```

## 3. QUY TRÌNH THÊM / SỬA FIELD GROUP

### Cách chuẩn (khuyến nghị) — qua admin UI
1. wp-admin → **Custom Fields → Add New** (hoặc sửa group có sẵn).
2. Thêm field, đặt location rule, lưu.
3. ACF tự ghi `acf-json/group_{key}.json`. **Commit file JSON** vào git.
4. Trên môi trường khác: ACF hiện nút **Sync** để import group từ JSON vào DB.

### Khi cần tạo JSON thủ công (vd qua AI/scaffold)
1. Tạo `acf-json/group_{context}_{name}.json`.
2. Mỗi field cần `key` duy nhất, ổn định (prefix `field_`), `name` snake_case, `type`.
3. `location` là mảng-lồng-mảng (OR ngoài, AND trong).
4. **Validate + review** trước khi commit:
   ```bash
   php underscores-child/acf-json/validate.php --summary
   ```
   - Guard: chặn JSON sai + key trùng + name trùng (3 lỗi làm mất data/hỏng sync).
   - `--summary`: in cây field (name/type/return_format) để review nhanh, không phải đọc raw JSON.
5. Commit JSON. Khi deploy / đổi môi trường: vào admin bấm **Sync** để nạp vào DB.

## 4. QUY TẮC ĐẶT TÊN

- Group key: `group_{context}_{name}` (vd `group_page_about`).
- Field key: `field_{group}_{field}` — duy nhất toàn site, KHÔNG đổi sau khi đã dùng (đổi key = mất data).
- Field name: snake_case (vd `hero_settings`, `is_show`).

## 5. SCHEMA JSON TỐI THIỂU

```json
{
    "key": "group_page_example",
    "title": "Example Page",
    "fields": [
        {
            "key": "field_page_example_heading",
            "label": "Heading",
            "name": "heading",
            "type": "text"
        }
    ],
    "location": [
        [
            { "param": "page_template", "operator": "==", "value": "page-template/template-example.php" }
        ]
    ],
    "style": "seamless",
    "position": "acf_after_title",
    "label_placement": "top",
    "active": true
}
```

Field type thường dùng: `text`, `textarea`, `wysiwyg`, `image` (`"return_format": "id"`),
`link` (`"return_format": "array"`), `true_false`, `group` (`sub_fields`), `repeater` (`sub_fields`),
`tab`, `flexible_content` (`layouts`).

## 6. OPTIONS PAGE (Theme Settings)

- Local JSON KHÔNG lưu options page → đăng ký bằng code trong `Theme\Child\Acf\LocalJson::register_options_page()` (`acf_add_options_page`).
- Options page hiện có: slug `theme-setting`, field group `group_theme_settings.json` (các tab General/Header/Footer/Scripts/Social...).
- Field group dùng `tab` để nhóm, và mỗi nhóm thường là 1 `group` con (vd `general_section`, `header_section`).

### Truy xuất giá trị
Dùng helper `underscores_get_option()` (không gọi `get_field(..., 'option')` trực tiếp trong template):
```php
// File: includes/functions/common-functions.php (child)
if (! function_exists('underscores_get_option')) {
    function underscores_get_option($field_name, $default = null) {
        if (! function_exists('get_field')) {
            return $default;
        }
        $value = get_field($field_name, 'option');
        return $value !== null && $value !== false ? $value : $default;
    }
}
```
Field nằm trong `group` con → đọc group rồi lấy key (đúng cấu trúc data thật, không bịa default):
```php
$general = underscores_get_option('general_section');   // mảng
$hotline = $general['hotline'] ?? '';
$logo    = $general['logo'] ?? 0;
if ($logo) echo wp_get_attachment_image($logo, 'full');
```
> Theo `data-rendering.md`: chỉ `?? ''`/`?: 0` chống lỗi rỗng — KHÔNG `?? '© 2024'` hay text/ảnh mặc định.

### Thêm option mới
Xem skill `add-theme-option`. Cần options page mới → thêm `acf_add_options_page(...)` trong `LocalJson::register_options_page()`.

## 7. THÊM FIELD GROUP — tóm tắt cho agent

- KHÔNG sinh `register_extended_field_group()` / `use Extended\ACF\...`.
- Tạo/sửa = ghi `acf-json/group_*.json` đúng schema mục 5, key ổn định (không đổi sau khi dùng).
- Validate: `php underscores-child/acf-json/validate.php --summary`. Sau đó **Sync** trong wp-admin.

## 8. THAM KHẢO

1. [ACF Local JSON](https://www.advancedcustomfields.com/resources/local-json/)
2. [ACF Field Types](https://www.advancedcustomfields.com/resources/)
3. [Auto-load system](auto-load-system.md)
