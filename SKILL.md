---
name: bca-design
description: Use this skill to generate well-branded interfaces and assets for BCA Partners (a Vietnam-based M&A / strategy consulting firm), either for production or throwaway prototypes/mocks/etc. Contains essential design guidelines, colors, type, fonts, assets, and UI kit components for prototyping.
user-invocable: true
---

Read the README.md file within this skill, and explore the other available files.
If creating visual artifacts (slides, mocks, throwaway prototypes, etc), copy assets out and create static HTML files for the user to view. If working on production code, you can copy assets and read the rules here to become an expert in designing with this brand.
If the user invokes this skill without any other guidance, ask them what they want to build or design, ask some questions, and act as an expert designer who outputs HTML artifacts _or_ production code, depending on the need.

## Quick reference
- **Brand:** BCA Partners — corporate consulting, calm and trustworthy. Navy `#003F6F` (primary) + blue `#0079A6` (accent), near-black ink, cool greys, black footer.
- **Type:** Inter throughout (400–800). Big headings in navy with -0.025em tracking; body 16/24.
- **Shape:** one 4px radius everywhere; frosted-glass panels over photography; minimal shadows.
- **Tokens:** `styles.css` → `tokens/*.css`. Link `styles.css` and use the CSS custom properties.
- **Components:** load `_ds_bundle.js`, then `const { Button, Navbar, Footer, ServiceCard, ProjectCard, FeatureCard, LeaderCard, ClientLogo, SectionHeading, ContactBand, SocialIcon, QuoteMark, Testimonial, Input, Icon } = window.BCAPartnersDesignSystem_f92865`.
- **UI kit:** `ui_kits/website/` is a full interactive recreation of the marketing site.
- **Assets:** logo + photography + client logos in `assets/`; icons in `assets/icons/`.
