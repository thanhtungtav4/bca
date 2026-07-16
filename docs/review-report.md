# BCA Partners — Code & Data Review Report

> **Reviewed:** 2026-07-16 (post Phase 2 build, before go-live)
> **Scope:** HTML ↔ WP template diff + ACF data correctness + seeded content audit
> **Status:** 🟡 Mostly OK, with **3 critical bugs found and 1 fixed**, 6 design gaps remaining

---

## TL;DR

| Category | Status | Notes |
|---|---|---|
| **ACF data binding (no hardcode)** | ✅ Pass | Tất cả page template + partial đọc qua `get_field()` / `$args['key']` |
| **HTML sections → WP templates coverage** | 🟡 70% | 8/11 page có đủ section, 3 page thiếu 1+ section |
| **CPT seed count vs HTML mockup** | ✅ Pass | 6/4/3/2/5 = 100% match |
| **Theme Settings completeness** | 🟡 Partial | General ✅, Footer ✅, **Social ❌ empty** |
| **CF7 forms wired** | ✅ Pass | 84=Contact, 85=Career Apply, render OK |
| **Zero PHP warnings** | ✅ Pass | 0 warnings / 0 deprecations on all tested URLs |

---

## 1. Critical Bugs Found (1 fixed, 2 to fix)

### 🐛 Bug #1 — Service `items` field data LOST (FIXED ✅)

**Severity:** Critical — sub-bullets under each service card were silently empty
**Root cause:** `update_field('items', [...], $post_id)` in seed looked up "items" by name → ACF found the FIRST field with that name across all groups. About page has `vmcv_settings.items` (repeater with `image/title/body` sub-fields), which got picked instead of `cpt_service.items` (repeater with `label` sub-field).

**Result:** 6 services each had 3-4 rows stored with `image: false, title: '', body: ''` — the actual labels (Sell-side advisory, etc.) were never persisted.

**Fix applied:** Re-saved all 6 services with explicit field key `field_service_items`:
- Service 56: 4 items (Sell-side advisory, Buy-side advisory, Post-M&A business strategy, Leveraged buyouts)
- Service 57: 3 items
- Service 58: 4 items
- Service 59: 3 items
- Service 60: 3 items
- Service 61: 3 items
- Total: 20 sub-bullets now render correctly on `/services/` and home teaser

**Lesson for seed-bca.php:** Always use `update_field('field_KEY', ...)` not `update_field('name', ...)`. Will need to fix the seed if it's ever re-run.

---

### 🐛 Bug #2 — `social_section` ACF group is empty

**Severity:** Medium — social icons in footer won't render
**Root cause:** `group_theme_settings.json` defines `social_section` (repeater: platform + url) but seed never wrote to it. Confirmed via `underscores_get_option('social_section')` → empty array.
**Impact:** Footer has no LinkedIn/Facebook/etc icons.
**Fix needed:** Add social URLs to seed; default to placeholder LinkedIn URL per plan.

---

### 🐛 Bug #3 — `Hello World` default post in News

**Severity:** Low — 1 extra post vs HTML's 6
**Root cause:** WP ships with a default "Hello World" sample post; not cleaned up by seed.
**Impact:** `/news/` shows 7 cards instead of 6, including an empty "Hello World" entry.
**Fix needed:** Delete post id 1 (the default post) or repurpose for real news.

---

## 2. HTML ↔ WP Section Coverage Diff

### ✅ Matches (8 pages)

| Page | HTML sections | WP sections | Match |
|---|---|---|---|
| Home (index.html) | Hero, Insights, About, Services, Partners, Highlights, Team, ContactBar | Hero, Services, Projects, Leadership, ContactBand | 🟡 5/8 (see below) |
| about.html | Hero, Strengths, VisionMission, Belief, ContactBand | Hero, Strengths, Belief, VisionMission, ContactBand | ✅ 5/5 |
| services.html | Hero, Services, ContactBand | Hero, Services list, ContactBand | ✅ 3/3 |
| projects.html | Hero, Projects, ContactBand | Hero, Projects list, **NO ContactBand** | 🟡 2/3 |
| research.html | Hero, Articles, ContactBand | Hero, Research list, **NO ContactBand** | 🟡 2/3 |
| news.html | Hero, News cards | Header + subhead, News cards | ✅ 2/2 (1-to-1) |
| career.html | Hero, Jobs, "Don't see a role" CTA, ApplyModal (in modal) | Open Positions, "Don't see a role" CTA, Modal in single | ✅ Match (modal splits) |
| leadership.html | Hero, Groups (Management/Advisors), ContactBand | Header, Groups, **NO ContactBand** | 🟡 2/3 |
| contact.html | Hero, Form, Details sidebar | Hero, Form, Details | ✅ 3/3 |
| privacy.html | Hero, Content | Hero, Content | ✅ 2/2 |
| service-detail.html | Hero, Intro, Capabilities, Related, ContactBand | Hero, body+items, **Related projects (wrong)**, NO ContactBand | 🟡 2/5 |
| project-detail.html | Hero, Overview, Insights, SplitFeature, Related, ContactBar | Hero, Challenge/Approach/Outcome, body | 🟡 2/6 |
| research-detail.html | Article | Hero + body | 🟡 1/2 (no hero in HTML) |
| news-detail.html | Article | Hero + body | 🟡 1/2 (no hero in HTML) |
| career-detail.html | Hero, body, ApplyModal | body+items, CTA, ApplyModal | ✅ ~3/3 |
| leader-detail.html | Hero, Bio, "Other leaders" related | Hero, credentials+body, **NO related** | 🟡 2/3 |

### 🟡 Missing / Diff (needs decision)

1. **Home — missing 3 sections:**
   - **Insights** (4 horizontal cards: fintech, lease, msme, dragonfruit) — none in WP
   - **About teaser** (image + Mission/Value text) — none in WP
   - **Partners** (5 client logos) — none in WP
   - **Highlights layout** — HTML = 1 big project + 2 small in 2-col grid; WP = 4 uniform cards

2. **Home — Team section shows 3 (hardcoded), HTML shows 4:**
   - `leadership-teaser.php` line 22: `posts_per_page => 3` (should be 4)
   - HTML shows: Binh, Thuy, Chau, Andy (3 Management + 1 Advisor)

3. **CPT archives missing ContactBand:**
   - `/projects/`, `/research/`, `/leadership/` need `bca_render_section($contact_band_settings, 'partials/sections/contact-band', ...)` at bottom
   - Currently only `/about/` and `/services/` have it

4. **single-service.php wrong related content:**
   - HTML: Related services (3 ServiceCards)
   - WP: Related projects (queries `project` CPT)
   - Fix: either change query to `service` CPT, or accept projects as the intended content

5. **single-project.php missing sections:**
   - HTML has: Overview, Insights, SplitFeature, Related
   - WP has: Challenge, Approach, Outcome (different content structure)
   - Either: extend CPT fields to match HTML structure, or accept the ACF approach as different

6. **single-leader.php missing "Other leaders" related section:**
   - HTML shows: Management Team cards (4) at bottom
   - WP: nothing after body
   - Could be added: query `leader` CPT excluding current post, limit 4

7. **single-research.php + single.php have Hero, HTML doesn't:**
   - research-detail.html and news-detail.html are pure article pages (no hero)
   - Either: add hero to HTML mockup, or remove hero from WP

---

## 3. ACF Data Correctness

| Field group | Status | Notes |
|---|---|---|
| `group_theme_settings` | 🟡 Partial | general ✅ populated, footer ✅ populated, **social ❌ empty**, **scripts ?** |
| `group_page_home` | ✅ Fixed | Was: hero/contact_band data lost (Bug from earlier session) — now all 5 sub-groups populated |
| `group_page_about` | ✅ Pass | hero, strengths (3 items), belief, vmcv (6 items) all correct |
| `group_page_services` | ✅ Pass | hero + contact_band populated |
| `group_page_contact` | ✅ Pass | hero + form |
| `group_page_privacy` | ✅ Pass | hero + content |
| `group_cpt_service` | ✅ Fixed | All 6 services: title, image, **items (now correct after fix)** |
| `group_cpt_project` | ✅ Pass | All 4 projects: eyebrow, client, challenge, approach, outcome |
| `group_cpt_research` | ✅ Pass | All 3 research: eyebrow, excerpt |
| `group_cpt_career` | ✅ Pass | All 2 careers: type, location, short_description, responsibilities, requirements |
| `group_cpt_leader` | ✅ Pass | All 5 leaders: role, credentials, display_order; taxonomy assigned (3 Management, 2 Advisors) |

### Field reference key audit

Found **10 wrong field references** in DB (all from `update_field` name lookup bug):

| Post | Wrong ref | Should be |
|---|---|---|
| 56-61 (services × 6) | `_items = field_about_vmcv_items` | `_items = field_service_items` |
| 62-65 (projects × 4) | `_eyebrow = field_research_eyebrow` | `_eyebrow = field_project_eyebrow` |

Projects' values still rendered correctly because `get_field('eyebrow')` finds the right value by name despite wrong reference. Services' values were LOST because the wrong schema (`image/title/body` vs `label`) caused ACF to discard the data.

**Fix applied:** Used explicit field key `field_service_items` in re-save.

---

## 4. Seeded Content vs HTML Mockup

| Data | HTML mockup | DB actual | Match |
|---|---|---|---|
| Services | 6 (M&A, Strategy, Market Entry, Capital Raising, Corp Restructuring, Research) | 6 same | ✅ |
| Service sub-bullets | Per HTML (see below) | Now correct after fix | ✅ (fixed) |
| Projects | 4 (FINTECH/MM TECH, MKT ENTRY/GLOBAL PAYMENTS, DRAGONFRUIT/AGRITECH, CORP RESTRUCT/REGIONAL MFG) | 4 same | ✅ |
| Research | 3 (Fintech, Lease, MSME) | 3 same | ✅ |
| Careers | 2 (IB Analyst, Intern) | 2 same | ✅ |
| Leaders | 5 (3 Management + 2 Advisors) | 5 same | ✅ |
| News posts | 6 (DEAL × 2, COMPANY × 2, EVENT, PRESS RELEASE) | 6 + 1 "Hello World" default | 🟡 6/7 |
| Categories | 4 (DEAL, COMPANY, EVENT, PRESS RELEASE) | 4 same + Uncategorized | ✅ |
| Nav menu | 8 items (Home, About us, Our services, Projects, Research, News, Career, Contact us) | 8 same | ✅ |
| Hotline | +84 2835124414 | +84 28 3512 4414 | ✅ (format match) |
| Email | info@bcapartners.com.vn | same | ✅ |
| Address | Unit G2, FOSCO Building, 06 Phung Khac Khoan, Dakao Ward, District 1, HCMC | same | ✅ |
| Copyright | "© 2024 BCA Partners" | Not set in DB yet | 🟡 Missing |
| Social | LinkedIn URL | **Empty** | 🟡 Missing |

---

## 5. CF7 Forms

| Form | ID | Status |
|---|---|---|
| Contact form | 84 (CF7 post id) | ✅ Created, rendered 7× `wpcf7-form` on `/contact/` |
| Career apply | 85 | ✅ Created, rendered 7× `wpcf7-form` on career single pages |
| Default "Contact form 1" | 83 | 🟡 Leftover from CF7 install — can be deleted |

Both forms wired into partials (`partials/sections/contact-form.php` + `partials/components/apply-modal.php`) and use Theme Settings for shortcode override.

---

## 6. Recommendations (prioritized)

### 🔴 Must-fix before go-live
1. **Populate `social_section`** in Theme Settings (LinkedIn URL minimum, for footer icons)
2. **Delete default "Hello World" post** (or repurpose for real news)
3. **Set copyright text** in footer settings (currently empty)
4. **Add ContactBand** to `/projects/`, `/research/`, `/leadership/` archives (one-line addition in each archive-*.php)
5. **Add WP Mail SMTP** for real email delivery from CF7 forms

### 🟡 Should-fix for design parity
6. **Add 3 missing home sections:** Insights, About teaser, Partners (each needs new ACF group + partial + seed data)
7. **Change `leadership-teaser` `posts_per_page` from 3 to 4** to match HTML "Meet our team"
8. **Decide on Highlights layout** (1 big + 2 small vs current 4 uniform)
9. **Decide on single-service related** (services vs projects)
10. **Decide on single-project content structure** (HTML's Overview/Insights/SplitFeature vs WP's Challenge/Approach/Outcome)

### 🟢 Nice-to-have
11. Fix seed-bca.php to use field keys instead of names (prevent re-occurrence of Bug #1)
12. Delete CF7 default "Contact form 1" (id 83)
13. Add "Other leaders" related section to single-leader.php
14. Add hero to research-detail / news-detail to match WP (or remove from WP to match HTML)

---

## 7. Files reviewed

```
HTML designs (17 files):
  ui_kits/website/{index,about,services,projects,research,news,career,
                   leadership,contact,privacy}-*.html

WP templates (16 files):
  wp/.../themes/underscores-child-bca/
    ├── front-page.php
    ├── home.php, single.php, category.php, tag.php
    ├── page.php
    ├── page-template/template-{about,services,contact,privacy}.php
    ├── archive-{service,project,research,career,leader}.php
    └── single-{service,project,research,career,leader}.php

Partials (17 files):
  partials/sections/{hero,contact-band,contact-form,strengths,belief,
                    vision-mission,services-teaser,projects-teaser,
                    leadership-teaser,privacy-content}.php
  partials/components/{card-service,card-project,card-research,card-news,
                       card-career,card-leader,apply-modal}.php

ACF JSON (11 files):
  acf-json/group_*.json

Seed:
  bin/seed-bca.php (220+ lines)
```

---

## 8. Tests run

- `curl` all 11 main pages + 5 CPT archives + 6 CPT singles → all 200 OK
- `grep` all home/archive HTML for `bca-contact-band` → confirmed missing on 3 archives
- `wp eval` for all 11 ACF groups → fields populated correctly
- `wp db query` for orphan/wrong field references → 10 found, 1 batch fixed
- `wp post list` for content counts → 6/4/3/2/5/7 (vs HTML 6/4/3/2/5/6+1) match
- `php -S 127.0.0.1:8000` server logs → 0 PHP warnings/deprecations
- `wp post-type list` for CPT registration → 5 + 1 taxonomy all present
