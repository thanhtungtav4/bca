import React from "react";

/** Leadership card: portrait over a grey plate, name, and role. */
export function LeaderCard({ photo, name = "Binh Pham, MBA, CFA", role = "Managing Director", style = {} }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", width: 384, ...style }}>
      <div style={{ height: 336, overflow: "hidden", background: "var(--bca-border)" }}>
        {photo && <img src={photo} alt={name} style={{ width: "100%", height: "100%", objectFit: "cover", objectPosition: "top center", display: "block" }} />}
      </div>
      <span style={{ marginTop: 16, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "40px", color: "rgba(0,0,0,0.6)" }}>{name}</span>
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 16, lineHeight: "24px", color: "rgba(0,0,0,0.6)" }}>{role}</span>
    </div>
  );
}
