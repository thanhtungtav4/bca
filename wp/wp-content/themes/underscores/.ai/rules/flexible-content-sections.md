# Quy tắc Sections Nội dung Linh hoạt (Flexible Content) - Underscores Theme

Tài liệu này quy định quy trình làm việc với ACF Flexible Content để xây dựng các trang có cấu trúc động, dựa trên việc mapping giữa Layouts và các file template partials.

## 1. NGUYÊN TẮC CỐT LÕI: LAYOUT-TO-FILE MAPPING

Mỗi "Layout" được định nghĩa trong một field Flexible Content phải tương ứng với một file template partial trong thư mục `partials/sections/`. Hệ thống sẽ tự động tìm và load file partial này dựa trên tên của Layout.

- **Field Type**: `Flexible Content`
- **Thư mục Partials**: `partials/sections/`

## 2. QUY TRÌNH LÀM VIỆC

### Bước 1: Định nghĩa Field Group với Flexible Content (ACF Local JSON)

- Tạo/sửa field group trong wp-admin → **Custom Fields**, thêm field type **Flexible Content** tên `sections`.
- Thêm các `Layout` cần thiết. **Tên (name) của Layout là yếu tố quyết định** (map sang file partial).
- Lưu → ACF ghi `underscores-child/acf-json/group_*.json`. Commit JSON, **Sync** trên môi trường khác.

**Ví dụ JSON** (field `sections` trong một group):
```json
{
    "key": "field_page_sections",
    "label": "Sections",
    "name": "sections",
    "type": "flexible_content",
    "button_label": "Thêm Section",
    "layouts": [
        {
            "key": "layout_hero_banner",
            "name": "hero_banner",
            "label": "Hero Banner",
            "display": "block",
            "sub_fields": [ /* sub-fields cho Hero Banner */ ]
        },
        {
            "key": "layout_image_gallery",
            "name": "image_gallery",
            "label": "Image Gallery",
            "display": "block",
            "sub_fields": [ /* ... */ ]
        }
    ]
}
```

### Bước 2: Tạo file Template Partials

- Với mỗi `Layout` đã tạo ở trên, hãy tạo một file PHP tương ứng trong thư mục `partials/sections/`.
- **Quy tắc đặt tên file (quan trọng)**: Tên file phải là phiên bản `kebab-case` của tên Layout. `get_row_layout()` sẽ trả về tên layout (ví dụ: `hero_banner`), và theme sẽ tìm file `hero-banner.php`.

- `Layout::make('Hero Banner', 'hero_banner')` -> `partials/sections/hero-banner.php`
- `Layout::make('Image Gallery', 'image_gallery')` -> `partials/sections/image-gallery.php`
- `Layout::make('Call to Action', 'cta')` -> `partials/sections/cta.php`

### Bước 3: Hiển thị các Sections trong Template chính

- Trong các file template chính (ví dụ: `page.php`, `single-product.php`), sử dụng một vòng lặp `while` với `have_rows()` để duyệt qua các section.
- Bên trong vòng lặp, sử dụng `get_template_part()` để load file partial tương ứng.

**Ví dụ**: Code trong `page.php`.
```php
<?php
// Kiểm tra xem có section nào không
if (have_rows('sections')) :
    // Lặp qua các section
    while (have_rows('sections')) : the_row();
        // Lấy tên layout (ví dụ: 'hero_banner')
        $layout_name = get_row_layout();
        
        // Chuyển đổi tên layout thành tên file (ví dụ: 'hero-banner')
        $file_name = str_replace('_', '-', $layout_name);
        
        // Load file partial từ thư mục partials/sections/
        get_template_part('partials/sections/' . $file_name);
        
    endwhile;
else :
    // Fallback: Nếu không có section nào, hiển thị nội dung mặc định của trang
    the_content();
endif;
?>
```

### Bước 4: Lấy dữ liệu trong file Section Partial

- Bên trong mỗi file section partial (ví dụ: `partials/sections/hero-banner.php`), sử dụng hàm `get_sub_field()` để lấy giá trị của các sub-fields đã định nghĩa cho layout đó.

**Ví dụ**: Code trong `partials/sections/hero-banner.php`.
```php
<?php
$title = get_sub_field('title');
$subtitle = get_sub_field('subtitle');
$background_image_id = get_sub_field('background_image');
?>
<section class="underscores-hero-banner" style="background-image: url(<?php echo wp_get_attachment_image_url($background_image_id, 'full'); ?>);">
    <div class="container">
        <h1><?php echo esc_html($title); ?></h1>
        <p><?php echo esc_html($subtitle); ?></p>
    </div>
</section>
```

## 3. Ví dụ yêu cầu

> "Thêm một section 'Video Player' vào field group trang.
> 1. Trong file `acf-json/group_*.json` chứa field `sections`, thêm một layout mới `video_player` với sub-fields `video_url` (oembed) và `caption` (text). Bấm **Sync** trong wp-admin.
> 2. Tạo file template partial mới tại `partials/sections/video-player.php`.
> 3. Trong file partial, lấy dữ liệu từ `video_url` và `caption` để hiển thị trình phát video và chú thích."
