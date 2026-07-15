One-line: BCA's button — filled navy CTA, a line/outline variant for placing over photography, and a ghost arrow-link. Use it for every call to action.

```jsx
<Button variant="filled" icon>Explore more</Button>
<Button variant="line" tone="light">Contact us</Button>
<Button variant="ghost" icon>Explore</Button>
```

Notes:
- `variant="filled"` is the primary navy CTA. `line` is transparent with a 2px outline — pass `tone="light"` when it sits on a dark image (hero). `ghost` is a small blue text link with a trailing arrow.
- `icon` adds the trailing right-arrow. Sizes `sm | md | lg` (default `lg`, 56px tall — matches the site's CTAs).
