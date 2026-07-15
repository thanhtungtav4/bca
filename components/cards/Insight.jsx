import React from "react";

/**
 * Figma "Insight": the text block of a highlighted-project card — navy uppercase
 * eyebrow, bold headline and a description. Pair with a `FeatureItem` image above.
 */
export function Insight({ eyebrow = "FINTECH PROJECTS", title = "Strategy consulting & Restructuring", description = "", style = {} }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 12, ...style }}>
      {eyebrow && <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 16, lineHeight: "24px", color: "var(--bca-navy)" }}>{eyebrow}</span>}
      <h3 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "100%", color: "var(--bca-ink)" }}>{title}</h3>
      {description && <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>{description}</p>}
    </div>
  );
}
