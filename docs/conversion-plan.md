# BCA Partners — HTML → WordPress Conversion Plan

> Plan sinh bởi AI agent theo skill `convert-html-to-wp` (parent theme `.ai/skills/convert-html-to-wp/`).
> Phase 1 = read-only plan. Phase 2 = build (chỉ chạy sau khi user duyệt).
> Nguồn HTML: `ui_kits/website/*.html` (18 files, bỏ `mobile.html`).
> Mục tiêu: child theme `underscores-child-bca` tại `wp/wp-content/themes/underscores-child-bca/`.

---

## Rules đang tuân thủ

Tất cả rule tại `../underscores/.ai/rules/` + `child-theme.md`:

- `lazy-first.md` — không scaffold "để dành", không helper bọc 1 dòng `get_field()`
- `wp-core-first.md` — `wp_nav_menu`, `wp_get_attachment_image`, `WP_Query`, không hardcode
- `data-rendering.md` — `?? ''` / `?: 0` guard rỗng, ẩn khối khi rỗng, **KHÔNG dummy data** (text HTML mockup → `placeholder`/`instructions`)
- `acf-integration.md` — Local JSON, key ổn định, Tab+Group+`is_show` cho page theo section
- `naming-convention.md` — helper `underscores_*` / `underscores_child_*`, class `Theme\Child\Hooks\{Slug}PageHook` (PSR-4)
- `auto-load-system.md` — `composer dump-autoload` sau khi thêm class mới
- `modular-development.md` — không viết logic vào `functions.php`
- `flexible-content-sections.md` — layout name → `partials/sections/{kebab}.php`
- `news-site.md` — News = `post` core (không CPT riêng)
- `ajax-handler.md` — AJAX có nonce check, `wp_send_json_*`

---

## 1. ASCII map — 18 page HTML → 11 WP page

```
index.html             ─►  front-page.php (Home)
about.html             ─►  page-template/template-about.php
vision-mission-values  ─►  (gộp vào about — xem Q2)
services.html          ─►  page-template/template-services.php
service-detail.html    ─►  CPT 'service' single-service.php
projects.html          ─►  CPT 'project' archive-project.php
project-detail.html    ─►  CPT 'project' single-project.php
research.html          ─►  CPT 'research' archive-research.php
research-detail.html   ─►  CPT 'research' single-research.php
news.html              ─►  CPT 'news' (core 'post') archive-news.php
news-detail.html       ─►  CPT 'news' single-news.php
career.html            ─►  CPT 'career' archive-career.php
career-detail.html     ─►  CPT 'career' single-career.php
leadership.html        ─►  CPT 'leader' archive-leader.php
leader-detail.html     ─►  CPT 'leader' single-leader.php
contact.html           ─►  page-template/template-contact.php
privacy.html           ─►  page-template/template-privacy.php
mobile.html            ─►  ⛔ BỎ (chỉ là design preview iPhone frame)

Shared header/footer   ─►  header.php / footer.php
                         partials/header/site-header.php
                         partials/footer/site-footer.php
Navbar (8 link)        ─►  wp_nav_menu('primary'), admin Appearance → Menus
```

---

## 2. Bảng section → loại dữ liệu

| Section | Loại | Lý do | Field/data dự kiến | Text mockup (→ placeholder/instructions) |
|---|---|---|---|---|
| Navbar | `wp_nav_menu` + Theme Settings | toàn site | menu `primary` (8 link) | — |
| Logo (Navbar + Footer) | ACF Options (`general_section.logo`) | toàn site | `image` (return id) | — |
| Hotline, email, address | ACF Options | toàn site | `text` | "info@bcapartners.com.vn", "+84 2835124414" |
| Social icons | ACF Options (`social_section`) | toàn site | `repeater` platform/url | LinkedIn URL |
| Footer © | ACF Options | toàn site | `text` | "© 2024 BCA Partners" |
| **Home (index.html)** Hero | ACF page (`hero_settings`) | tĩnh 1 lần | heading, subheading, image, cta | "Fostering Business Evolution" / "Trusted partner..." |
| Home services teaser | ACF page (`services_teaser`) + CPT 'service' | list + single | heading, list service IDs (relationship) | "Our Services" / Strategy, M&A... |
| Home projects teaser | ACF page (`projects_teaser`) + CPT 'project' | tương tự | heading, list project IDs | — |
| Home leadership teaser | ACF page (`leadership_teaser`) + CPT 'leader' | tương tự | — | — |
| Home contact band | ACF page (`contact_band`) | tĩnh 1 lần | heading, image | "How can we help you succeed?" |
| **About** Hero | ACF page (`hero_settings`) | tĩnh | heading, subheading, image | "Company Overview" |
| About strengths (3 item) | ACF page (`strengths_settings`) — Group | 3 item cố định | `repeater` icon/title/body (max 3) | "Local market insights", "Strong expertise...", "Extensive relationships" |
| About vision/mission/values (6) | ACF page (`vmcv_settings`) | list lặp | `repeater` image/title/body | "Vision", "Mission", "Integrity", "Social Responsibility"... |
| About belief | ACF page (`belief_settings`) | tĩnh | heading, quote, image | "Our Belief" |
| **Services** Hero | ACF page | tĩnh | heading, sub | "Our Services" |
| Services list (6) | **CPT 'service'** archive | 6 item, mỗi item có detail | title, image, items (repeater short text) | "Mergers & Acquisitions", "Strategy" + sub-items |
| Service detail | **CPT 'service'** single | có single page | + content, related projects | — |
| **Projects** Hero | ACF page | tĩnh | heading, sub | "Highlighted Projects" |
| Projects list (4) | **CPT 'project'** archive | flip rows | eyebrow, client, title, body, image | "FINTECH PROJECTS / MM TECHNOLOGY / Strategy consulting..." |
| Project detail | **CPT 'project'** single | có single | + body long, gallery?, related | — |
| **Research** Hero | ACF page | tĩnh | heading, sub | "Research" |
| Research list (3) | **CPT 'research'** archive | flip rows | eyebrow, title, excerpt, image | "FINTECH / The future of payment..." |
| Research detail | **CPT 'research'** single | có single | + body | — |
| **News** Hero | ACF page | tĩnh | heading, sub | "News" |
| News list (6) | **`post` core** (không CPT) | lặp + category | category, date, title, excerpt, image | "DEAL / 18 Apr 2024 / BCA Partners advises..." |
| News detail | **`post` core** single | có single | + body | — |
| **Career** Hero | ACF page | tĩnh | heading, sub | "Career" |
| Career list (2) | **CPT 'career'** archive | row layout | title, type, location, description | "Investment Banking Analyst / Full-time / District 1, HCMC" |
| Career detail | **CPT 'career'** single | có single | + responsibilities, requirements, apply | — |
| Apply modal | Form (POST → email hoặc DB) | tĩnh | enqueue JS modal | — |
| **Leadership** Hero | ACF page | tĩnh | heading, sub, image | "Our Leadership" |
| Management team (3) + Advisors (2) | **CPT 'leader'** archive | 2 group (taxonomy) | photo, name, role, bio | "Binh Pham, MBA, CFA / Managing Director" |
| Leader detail | **CPT 'leader'** single | có single | + bio long, credentials | — |
| **Contact** Hero | ACF page | tĩnh | heading, sub, image | "Contact Us" |
| Contact form | ACF page (`contact_form`) | tĩnh | form action, success msg | — |
| Contact details | **Theme Settings** | toàn site | hotline, email, address (đã có 1 phần trong `group_theme_settings.json`) | — |
| **Privacy** | page-template | tĩnh | wysiwyg content | "Last updated: 1 January 2024" |

---

## 3. CPT + Taxonomy đề xuất

### Custom Post Types (5)

| CPT slug | Label | Supports | Archive | Single | Has archive |
|---|---|---|---|---|---|
| `service` | Services | title, editor, thumbnail, excerpt | `archive-service.php` | `single-service.php` | ✓ |
| `project` | Projects | title, editor, thumbnail, excerpt | `archive-project.php` | `single-project.php` | ✓ |
| `research` | Research | title, editor, thumbnail, excerpt | `archive-research.php` | `single-research.php` | ✓ |
| `career` | Careers | title, editor, thumbnail, excerpt | `archive-career.php` | `single-career.php` | ✓ |
| `leader` | Leadership | title, editor, thumbnail, excerpt | `archive-leader.php` | `single-leader.php` | ✓ |

### CPT dùng core `post` (theo `news-site.md`)
- **News** = `post` core + `category` core + `tag` core → KHÔNG tạo CPT riêng.

### Taxonomy (1)
- **`leader_group`** (slug `leader-group`) gắn vào `leader` — values: "Management Team", "Advisors". Dùng `get_the_terms()` để render đúng group.

### Bỏ qua (lazy-first)
- `service_category` — services chỉ 6 item cố định, taxonomy thừa.
- `project_category` / `research_category` — eyebrow lưu trong field, không cần taxonomy.

---

## 4. File mapping — section → WP file

### Page templates (5 + 1 default)
| Slug | File | ACF group |
|---|---|---|
| home | `front-page.php` | `group_page_home` (Tab: Hero, Services teaser, Projects teaser, Leadership teaser, Contact band) |
| about | `page-template/template-about.php` | `group_page_about` (Tab: Hero, Strengths, Belief, V/M/V) |
| services | `page-template/template-services.php` | `group_page_services` (Tab: Hero, Contact band) — list render từ CPT |
| contact | `page-template/template-contact.php` | `group_page_contact` (Tab: Hero, Form) |
| privacy | `page-template/template-privacy.php` | `group_page_privacy` (Tab: Content wysiwyg) |
| default | `page.php` | fallback `the_content()` cho page admin tạo mới |

### CPT archives + singles (5 × 2 = 10 files)
- `archive-service.php`, `single-service.php`
- `archive-project.php`, `single-project.php`
- `archive-research.php`, `single-research.php`
- `archive-career.php`, `single-career.php`
- `archive-leader.php`, `single-leader.php`

### News (dùng `post` core)
- `home.php` (blog index — archive)
- `single.php` (single)
- `category.php` (category archive)
- `tag.php` (tag archive)

### Partials — sections (1 partial per layout)
```
partials/sections/
├── hero.php                    (dùng cho tất cả page có hero)
├── contact-band.php            (CTA band — dùng cho nhiều page)
├── strengths.php               (about)
├── belief.php                  (about)
└── vision-mission.php          (about)
```

### Partials — components (DRY)
```
partials/components/
├── card-service.php            (services list + service teaser)
├── card-project.php            (projects list + project teaser + row layout)
├── card-research.php           (research list + research teaser)
├── card-news.php               (news list — dùng cho home, blog, category)
├── card-career.php             (career list row)
├── card-leader.php             (leadership grid)
└── apply-modal.php             (career apply form)
```

### Header / Footer
```
partials/header/site-header.php     (Navbar — data từ wp_nav_menu + Theme Settings)
partials/footer/site-footer.php     (address + social + © — data từ Theme Settings)
```

### Hooks (PSR-4, namespace `Theme\Child\Hooks`)
```
app/Hooks/
├── HomePageHook.php              (load assets + body class)
├── AboutPageHook.php
├── ServicesPageHook.php
├── ContactPageHook.php
├── PrivacyPageHook.php
├── ServiceCptHook.php            (CPT 'service' rewrite + body class)
├── ProjectCptHook.php
├── ResearchCptHook.php
├── CareerCptHook.php
├── LeaderCptHook.php
└── NewsPostTypeHook.php          (chỉ nếu cần custom hook cho post core)
```

### PostTypes / Taxonomies (PSR-4, namespace `Theme\Child\PostTypes` / `Theme\Child\Taxonomies`)
```
app/PostTypes/
├── ServicePostType.php
├── ProjectPostType.php
├── ResearchPostType.php
├── CareerPostType.php
└── LeaderPostType.php

app/Taxonomies/
└── LeaderGroupTaxonomy.php
```

### ACF JSON (Local — commit vào git)
```
acf-json/
├── group_page_home.json
├── group_page_about.json
├── group_page_services.json
├── group_page_contact.json
├── group_page_privacy.json
├── group_cpt_service.json
├── group_cpt_project.json
├── group_cpt_research.json
├── group_cpt_career.json
├── group_cpt_leader.json
└── group_theme_settings.json      (đã có — extend thêm social + footer + contact info)
```

---

## 5. Câu hỏi mờ — cần user duyệt

**Q1. Services: page tĩnh 6 item, hay CPT?**
- HTML hiện có `services.html` (6) + `service-detail.html` (1 detail). Nếu CPT → admin tự thêm service mới. Nếu ACF repeater → cố định 6.
- ✅ Recommend: **CPT** (vì đã có service-detail, có khả năng mở rộng).

**Q2. Vision/Mission/Values: page riêng, hay section trong About?**
- Có 2 file: `about.html` (section V/M/V) + `vision-mission-values.html` (full page).
- ✅ Recommend: gộp vào About (1 page, 1 ACF group), bỏ page riêng.

**Q3. Leadership taxonomy: 1 group 'leader_group' (Management vs Advisors), hay 2 group riêng, hay không taxonomy (lưu field)?**
- HTML tách rõ 2 group. Taxonomy giúp admin tự sắp xếp + query đúng group.
- ✅ Recommend: **taxonomy `leader_group`** với 2 terms.

**Q4. News dùng `post` core, category admin tạo sao?**
- Theo `news-site.md`: dùng `category` core. Admin tạo category trong Posts → Categories (DEAL / COMPANY / EVENT / PRESS RELEASE).
- Eyebrow trong card = category name.

**Q5. Apply form (Career modal): gửi email hay lưu DB?**
- (a) `wp_mail()` gửi thẳng `info@bcapartners.com.vn`
- (b) Lưu vào CPT `application` (admin xem trong wp-admin, tracking)
- ✅ Recommend: (a) cho gọn, hoặc (b) nếu muốn tracking.

**Q6. `page.php` (default page) dùng layout gì?**
- 5 page template chuyên cho page có markup đặc biệt.
- Page khác admin tạo → dùng `page.php` mặc định, render `the_content()`.
- ✅ Recommend: tạo `page.php` minimal với `the_content()`.

**Q7. Mobile preview (`mobile.html`) — có cần responsive riêng không?**
- Recommend: **bỏ** `mobile.html` làm reference, để design tokens (spacing, type) responsive tự nhiên.

---

## Phase 2 — Build thứ tự (chỉ chạy sau khi user duyệt Phase 1)

1. **CPT classes** (5) + **Taxonomy** (1) → `composer dump-autoload`
2. **Theme Settings extend** — thêm `social_section`, `contact_section` (hotline/email/address)
3. **ACF JSON** — 6 page groups + 5 CPT groups
4. **Page templates** (5) + **partials** (sections, components, header, footer)
5. **CPT archives + singles** (10 files)
6. **Page hooks** (asset enqueue + body class)
7. **Verify** — `php -l` mọi file PHP, ACF Sync, Settings → Permalinks flush, browser test

---

## Checklist Phase 1 (theo skill)

- [x] Phase 1 KHÔNG tạo file code (chỉ plan)
- [x] Đủ 5 phần (map, bảng, CPT/tax, mapping file, câu hỏi)
- [x] Đã hỏi phần mơ hồ (7 câu hỏi)
- [ ] **Chờ user duyệt trước Phase 2**
- [x] Không tạo CPT/field cho vùng tĩnh (lazy-first)
- [x] Markup lặp tách partial (đã liệt kê)
- [x] Field toàn site → Theme Settings (general, footer, social)

---

## Status

- **Created:** 2026-07-15 (Phase 1)
- **Status:** Awaiting user approval (Q1-Q7)
- **Next step:** User approves → run Phase 2 build
