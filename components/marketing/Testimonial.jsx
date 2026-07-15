import React from "react";
import { QuoteMark } from "./QuoteMark.jsx";

/** Testimonial card: quote mark, quote body, and attribution. */
export function Testimonial({
  quote = "BCA Partners delivered value through practical solutions and the best service experience.",
  name = "Client Name",
  role = "Chief Executive Officer",
  style = {},
}) {
  return (
    <figure
      style={{
        margin: 0,
        display: "flex",
        flexDirection: "column",
        gap: 16,
        padding: 32,
        background: "var(--bca-surface-section)",
        borderRadius: "var(--radius)",
        maxWidth: 520,
        ...style,
      }}
    >
      <QuoteMark size={64} />
      <blockquote style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 500, fontSize: 20, lineHeight: "32px", color: "var(--bca-ink)" }}>{quote}</blockquote>
      <figcaption style={{ display: "flex", flexDirection: "column", gap: 2 }}>
        <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 16, lineHeight: "24px", color: "var(--bca-navy)" }}>{name}</span>
        <span style={{ fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "20px", color: "var(--bca-ink-2)" }}>{role}</span>
      </figcaption>
    </figure>
  );
}
