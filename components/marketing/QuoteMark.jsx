import React from "react";

/** Oversized decorative quotation mark in brand blue/navy. */
export function QuoteMark({ color = "var(--bca-blue)", size = 96, style = {} }) {
  return (
    <span aria-hidden="true" style={{ fontFamily: "var(--font-sans)", fontWeight: 800, fontSize: size, lineHeight: 1, color, display: "inline-block", ...style }}>
      &ldquo;
    </span>
  );
}
