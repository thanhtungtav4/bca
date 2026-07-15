import React from "react";

/**
 * Project / insight card: photo on top, navy uppercase eyebrow, headline, client
 * name, and a description. Matches the "Highlighted Projects" cards.
 */
export function ProjectCard({
  image,
  eyebrow = "FINTECH PROJECTS",
  title = "Strategy consulting & Restructuring",
  client,
  description = "",
  style = {},
}) {
  return (
    <article style={{ display: "flex", flexDirection: "column", gap: 24, ...style }}>
      <div style={{ borderRadius: "var(--radius)", overflow: "hidden", background: "var(--bca-border)", aspectRatio: "384 / 256" }}>
        {image && <img src={image} alt="" style={{ width: "100%", height: "100%", objectFit: "cover", display: "block" }} />}
      </div>
      <div style={{ display: "flex", flexDirection: "column", gap: 12 }}>
        {eyebrow && (
          <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 16, lineHeight: "24px", color: "var(--bca-navy)" }}>{eyebrow}</span>
        )}
        <h3 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "100%", color: "var(--bca-ink)" }}>{title}</h3>
        {client && (
          <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 16, lineHeight: "24px", color: "var(--bca-black)" }}>{client}</span>
        )}
        {description && (
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>{description}</p>
        )}
      </div>
    </article>
  );
}
