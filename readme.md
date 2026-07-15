# BCA Partners — Design System

A design system reconstructed from the **"BCA website News"** Figma file for **BCA Partners**, a management-consulting firm based in Ho Chi Minh City, Vietnam. BCA advises on **M&As, Strategy, Corporate Restructuring, Market Entry, Capital Raising and Financial Solutions** in the Vietnamese market.

> "BCA Partners is a consulting firm specializing in M&As, Strategy, Corporate Restructuring, Market Entry, and other Financial Solutions in Vietnam Market. We deliver value to customers through practical solutions and best service experience."

The identity is calm, corporate and trustworthy: a navy-and-blue palette, Inter throughout, a single 4px radius, generous white space, and photography read through frosted-glass panels.

## Sources
- **Figma file:** "BCA website News.fig" (attached, mounted as a virtual filesystem). Pages: Cover, Flow, Guild-line (the design-system definitions), UI-design (the real website + mobile screens), Wireframe, Source, Ref. All token values, component geometry, copy and imagery below are transcribed verbatim from this file.
- **GitHub:** `https://github.com/thanhtungtav4/bca` — attached but currently **empty / inaccessible** (git returned 409 on `main` and `master`). Nothing could be read from it. If the repo is populated later it may hold the production markup and is worth exploring to build higher-fidelity recreations.

## What's inside (index)
- `styles.css` — the single entry point consumers link. `@import`s the token files only.
- `tokens/` — `colors.css`, `typography.css`, `spacing.css`, `effects.css`, `icons.css`, `fonts.css`.
- `components/` — reusable primitives (see below).
- `ui_kits/website/` — interactive recreation of the BCA marketing site (Homepage, Leadership, Contact).
- `guidelines/` — foundation specimen cards (Colors, Type, Spacing, Brand).
- `assets/` — logo, photography, client logos (copied verbatim from Figma); `assets/icons/` — materialized icon set.
- `SKILL.md` — Agent-Skill wrapper.

### Components
Grouped by concern. Every component is exported on `window.BCAPartnersDesignSystem_f92865`.
- **core/** — `Button` (filled / line / ghost, sizes sm·md·lg, optional arrow), `Component1` (inline arrow-link wrapper).
- **navigation/** — `Navbar` (sticky top bar), `NavItem` (link), `NavGroup` (link row/column), `Logo` (image mark / wordmark), `Logomark` (compact type lockup), `Footer` (black band + address + LinkedIn).
- **cards/** — `Service` (exact source tile, 22px title), `ServiceCard` (homepage-grid tile, 24px title), `Insight` (project text block), `ProjectCard` (case study = FeatureItem + Insight), `FeatureItem` (atomic rounded photo block), `FeatureCard` (photo + glass panel), `LeaderCard` (team portrait), `Avatar` (portrait block), `People` (team portrait row), `ClientLogo` (partner tile), `ClientInfoItem` (logo tile + name).
- **marketing/** — `HeadingProject` (navy heading), `SectionHeading` (heading + lead), `ContactBand` (blue CTA band), `SocialIcon`, `QuoteMark`, `Testimonial`, `StoreBadge` (app-store badge slot).
- **forms/** — `Input` / `FormGroup` (labelled field / textarea), `FormTitle` (label), `FormHelpText` (helper / error text).
- **layout/** — `MobileFrame` (phone device bezel for mobile screens).
- **assets/icons/** — `Icon` (renders the materialized glyph set — 19 essential line icons + 64 social variants).

## CONTENT FUNDAMENTALS
How BCA writes:
- **Voice:** professional, plain, confidence without hype. Sentences are declarative and benefit-led — "Fostering Business Evolution", "Trusted partner for your most important business challenges", "How can we help you succeed?".
- **Person:** speaks about the firm as **"we"/"BCA Partners"** and addresses the client as **"you"** ("How can we help *you* succeed?"). Never first-person singular.
- **Casing:** Title Case for page/section headings ("Our Services", "Our Highlighted Projects", "Key Partners", "Our Leadership"). Sentence case for body copy. Category eyebrows are **ALL CAPS** in navy ("FINTECH PROJECTS", "MARKET RESEARCH SERVICES").
- **Names & credentials:** people are always shown with post-nominals ("Binh Pham, MBA, CFA"; "Thuy Huynh, PhD"; "Andy Phan, PhD, MBA"). Roles are formal ("Managing Director", "Advisor, Financial Market & Strategy").
- **No emoji, no exclamation, no slang.** Numbers/contact details are literal ("Tel: +84 2835124414", "info@bcapartners.com.vn").
- **Vibe:** institutional, understated, credible. Copy is short; imagery and whitespace carry the tone.

## VISUAL FOUNDATIONS
- **Color:** a two-tone blue identity. **Navy `#003F6F`** is the primary — headings, filled buttons, eyebrows. **Blue `#0079A6`** is the accent — the wordmark, links, and the translucent CTA band (`rgba(0,121,166,0.8)` over a photo). Text is near-black ink `#111827` (headings) and `#374151` (body). Neutrals are cool greys: `#F5F5F5` (muted section), `#E9ECF2` (soft section / cards), `#E0E1E5` (image placeholder / border), `#8896A6` (hairlines). The footer is pure black. No gradients other than photo overlays; no purple, no warm accents.
- **Type:** **Inter** exclusively, weights 400–800. Large display/section headings (40–60px) carry **-0.025em** tracking and sit in navy. Card headlines are 24/700. Body is 16/24 and lead is 18/28. Nav/footer/eyebrow are 14–16 semibold. (Inter ships from Google Fonts here — no binaries were in the file; swap in self-hosted woff2 if required.)
- **Spacing & layout:** built on a **1440px** desktop canvas with a **112px** outer gutter; mobile is 375px with a 20px gutter. Sections open with 80px top padding. Gaps follow an 8px rhythm (4/8/12/16/24/32). Content grids are 3-up (services, projects, leadership).
- **Radius:** a **single 4px radius** on cards, buttons, images and inputs. Icon/social chips use a full pill.
- **Backgrounds & imagery:** full-bleed photography (business, cityscape, people), often behind a **frosted-glass panel** — dark `rgba(36,36,36,0.5)` for feature copy, light `rgba(222,225,230,0.8)` for service captions, or a `rgba(255,255,255,0.8)` blur band behind partner logos. Blur is `4px` on panels, `12px` on bands. Photos are neutral/cool, natural-light, not heavily filtered.
- **Elevation & borders:** shadows are minimal — `0 1px 4px rgba(0,0,0,0.15)` under the sticky navbar, and a `1px inset rgba(0,0,0,0.1)` hairline on partner-logo tiles. The line/outline button uses a `2px inset` border in `currentColor`. Dividers are 0.5px `#8896A6` hairlines.
- **Cards:** flat — a 4px radius, an image or soft-grey fill, occasionally a hairline; rarely a drop shadow. Content cards are image-on-top + text-below; feature cards layer a glass panel over the image.
- **Motion & states:** understated. A 160ms ease is the house transition; hover darkens links toward navy and lifts opacity on nav items. No bounces, no large motion. Press states are subtle color shifts (not scale).
- **Transparency & blur:** used deliberately for legibility of text over imagery (the glass panels and the blue CTA wash), never decoratively.

## ICONOGRAPHY
- The BCA site itself uses **very few icons**: a right-pointing **arrow** inside buttons/links, a **hamburger** menu on mobile, a round **LinkedIn** chip in the footer, and the odd chevron. Iconography is line-style, thin stroke, monochrome (navy or white), sized 16–24px.
- The Figma file bundles a broader **"Essential icon"** line set (19 glyphs: arrows, chevrons, mail, phone, map-pin, send, plus, close, check-circle, play-circle, menu, mobile) and a **"Social icon"** set (64 variants across 8 networks × 4 styles × 2 shapes). Both are materialized into `assets/icons/` as `icon-data.js` + an `Icon` React wrapper. Render with `<Icon name="EssentialIconIconArrowRight" size={24} />`; single-color glyphs paint with `currentColor`. See `assets/icons/Icon.d.ts` for the full name list.
- **No icon font, no emoji, no unicode-as-icon.** The button arrow is drawn inline in `Button` (a simple 2px stroked arrow) to match the source exactly.

## Brand assets & the logo
- **Logo present:** `assets/logo-bca.png` — the real BCA Partners mark (navy "BCA" over "PARTNERS" with a rule), copied verbatim from the Figma navbar. Use it on light backgrounds. On dark backgrounds the footer uses the **wordmark in type** (blue "BCA Partners"), matching the source.
- The uploaded `uploads/lgoo.svg` is a 128×46 logo frame whose embedded bitmap has **no image data** (it renders empty) — `assets/logo-bca.png` is used instead.
- All photography and partner logos in `assets/` are the exact bitmaps referenced by the Figma design.

## Component coverage (the 40 Figma "families")
The compiler counts **40 component families** in the source, but that number is inflated by duplicate symbols across the file's seven pages and by third-party reference/template material on the "Ref" and "Source" pages. **28 of 40 are built**; the rest are duplicates, glyph families served by `Icon`, or non-primitives. Here is the honest mapping.

**Built (21 components covering the real BCA families):**
- `Button` ← unifies the source's `Button` (×4 duplicate sets: 20/120/120/204-variant), `_Button base` (×2) and the Filled/Line/Ghost symbols — one component, `variant` prop.
- `NavItem` ← `_Nav item`; `NavGroup` ← `_Nav group`; `Navbar` ← `Navbar`; `Footer` ← `Footer`; `Logo` ← `Logo` + `Logomark`.
- `Service` ← `Service`; `ServiceCard` ← the homepage service tile; `ProjectCard` ← `Insight` + `Project card`; `FeatureItem` ← `_Feature Item` (×2); `FeatureCard` ← the glass feature; `Avatar` ← `Avatar`; `LeaderCard` ← leadership frames; `ClientLogo` ← `_Client logo`.
- `ContactBand` ← `Contact`; `Input` ← `form-group` (+ `form-title` / `form-help-text`); `Icon` ← `Essential icon` (19) + `Social icon` (64) + the vuesax/editor glyphs; `SocialIcon` ← the footer social treatment; `QuoteMark` ← `Quote mark`; `Testimonial` ← `Testimonial 02`.

**Intentional additions (2, not 1:1 in the kit):** `SectionHeading` (extracted from the repeated navy section titles) and `Icon` (a wrapper over the materialized glyph set). `FeatureCard`, `LeaderCard` and `Input` are BCA-appropriate names for the glass-feature, leadership and `form-group` families respectively — kept for clarity.

**Intentionally not built (8 — duplicates, glyph families, or non-primitives, with reasons):** the duplicate `Button`/`_Button base` sets ×4 (fold into `Button`); `Essential icon` ×2 and `Social icon` glyph families (served by `Icon`); `ảnh` (a 701×3571 scrolling image strip) and `SaaS Landing Page` — page-specific demo layouts, not reusable primitives; `Frame 157` / `Frame 2567` (unnamed Figma layout frames). None are part of BCA's reusable component vocabulary.

## Caveats
- **Fonts:** Inter is loaded from Google Fonts (the file shipped no binaries). Provide self-hosted Inter woff2 for production.
- **GitHub repo** `thanhtungtav4/bca` was empty/inaccessible — recreations are Figma-sourced only.
- Component coverage is deliberately scoped to the real BCA site rather than every template family embedded in the file (see above).
