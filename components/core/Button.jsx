import React from "react";

/**
 * BCA Partners button. Three variants map to the Figma set:
 *  - filled : solid navy, white label (primary CTA)
 *  - line   : transparent with a 2px outline (used on imagery — set tone="light")
 *  - ghost  : inline text link with a trailing arrow (blue)
 * Optional trailing arrow via `icon`. Sizes sm / md / lg.
 */
export function Button({
  children = "Explore more",
  variant = "filled",
  size = "lg",
  icon = false,
  tone = "dark",
  as = "button",
  className = "",
  style = {},
  ...rest
}) {
  const sizes = {
    sm: { h: 40, padX: 16, font: 14 },
    md: { h: 48, padX: 20, font: 16 },
    lg: { h: 56, padX: 24, font: 16 },
  };
  const s = sizes[size] || sizes.lg;

  const base = {
    display: "inline-flex",
    alignItems: "center",
    justifyContent: "center",
    gap: "var(--btn-gap)",
    fontFamily: "var(--font-sans)",
    fontWeight: "var(--fw-semibold)",
    fontSize: s.font,
    lineHeight: "24px",
    borderRadius: "var(--radius)",
    cursor: "pointer",
    border: "none",
    textDecoration: "none",
    transition: "var(--transition)",
    boxSizing: "border-box",
    whiteSpace: "nowrap",
  };

  let variantStyle = {};
  if (variant === "filled") {
    variantStyle = {
      height: s.h,
      padding: `0 ${s.padX}px`,
      background: "var(--bca-navy)",
      color: "var(--bca-white)",
    };
  } else if (variant === "line") {
    const c = tone === "light" ? "var(--bca-white)" : "var(--bca-navy)";
    variantStyle = {
      height: s.h,
      padding: `0 ${s.padX}px`,
      background: "transparent",
      color: c,
      boxShadow: "inset 0 0 0 2px currentColor",
    };
  } else {
    // ghost / text link
    variantStyle = {
      height: "auto",
      padding: 0,
      background: "transparent",
      color: "var(--bca-blue)",
      gap: 8,
    };
  }

  const Tag = as;
  return (
    <Tag className={className} style={{ ...base, ...variantStyle, ...style }} {...rest}>
      {children}
      {icon && <Arrow />}
    </Tag>
  );
}

function Arrow() {
  return (
    <svg width="22" height="16" viewBox="0 0 22 16" fill="none" aria-hidden="true" style={{ flexShrink: 0 }}>
      <path d="M1 8h19M14 2l6 6-6 6" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
    </svg>
  );
}
