import React from "react";

/** A single top-nav / footer link. 14px semibold, subtle navy hover. */
export function NavItem({ children = "About us", active = false, onDark = false, href = "#", style = {}, ...rest }) {
  const base = {
    display: "inline-flex",
    alignItems: "center",
    gap: 4,
    padding: "4px 8px",
    fontFamily: "var(--font-sans)",
    fontWeight: "var(--fw-semibold)",
    fontSize: 14,
    lineHeight: "20px",
    whiteSpace: "nowrap",
    textDecoration: "none",
    cursor: "pointer",
    transition: "var(--transition)",
    color: onDark ? "var(--bca-white)" : active ? "var(--bca-navy)" : "var(--bca-ink)",
    opacity: onDark && !active ? 0.85 : 1,
  };
  return (
    <a href={href} style={{ ...base, ...style }} {...rest}>
      {children}
    </a>
  );
}
