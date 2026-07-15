import React from "react";

/** Centered section heading in navy with tight tracking. Optional lead paragraph. */
export function SectionHeading({ children = "Our Services", lead, align = "center", color = "var(--bca-navy)", style = {} }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 16, textAlign: align, alignItems: align === "center" ? "center" : "flex-start", ...style }}>
      <h2 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 40, lineHeight: "72px", letterSpacing: "-0.025em", color }}>
        {children}
      </h2>
      {lead && (
        <p style={{ margin: 0, maxWidth: 840, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 18, lineHeight: "28px", color: "var(--bca-ink-2)" }}>{lead}</p>
      )}
    </div>
  );
}
