# BCA Partners — Website UI Kit

An interactive recreation of the BCA Partners marketing site, composed entirely from this design system's components (loaded from `_ds_bundle.js`).

## Screens
- **Homepage.jsx** — hero, About Us, feature grid, Our Services (6 tiles), Highlighted Projects, Key Partners, contact band.
- **Leadership.jsx** — "Our Leadership" hero + Management Team / Advisors grids of `LeaderCard`s (real team names & roles from the source file).
- **Contact.jsx** — blue CTA hero, working (fake) contact form using `Input` + `Button`, and the HCMC office block.

## Interactions
`index.html` wires a tiny nav router: clicking **Home**, **Our team**, or **Contact us** switches screens; other links route Home. The contact form validates and shows a success state.

## Notes
- Built on a 1440px desktop canvas — the site's design width. Outer gutter is 112px.
- All imagery is copied verbatim from the Figma file (`assets/`). Nothing is redrawn.
