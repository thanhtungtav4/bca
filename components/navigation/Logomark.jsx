import React from "react";

/**
 * Figma "Logomark": the compact BCA mark for tight spaces. The source has no
 * standalone glyph mark, so this renders the "BCA" lockup in navy type.
 */
export function Logomark({ color = "var(--bca-navy)", size = 40, style = {} }) {
  return (
    <span style={{ display: "inline-flex", flexDirection: "column", alignItems: "center", lineHeight: 1, ...style }}>
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 800, fontSize: size, letterSpacing: "-0.02em", color }}>BCA</span>
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: size * 0.26, letterSpacing: "0.24em", color, marginTop: size * 0.08 }}>PARTNERS</span>
    </span>
  );
}
