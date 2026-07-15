import React from "react";
import { ClientLogo } from "./ClientLogo.jsx";

/**
 * Figma "_Client info item": a client/partner logo tile paired with a name (and
 * optional detail). Aligns left or centered.
 */
export function ClientInfoItem({ logo, name = "Client", detail, align = "center", style = {} }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 8, alignItems: align === "center" ? "center" : "flex-start", textAlign: align, ...style }}>
      <ClientLogo logo={logo} name={name} />
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 14, lineHeight: "20px", color: "var(--bca-ink)" }}>{name}</span>
      {detail && <span style={{ fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "20px", color: "var(--bca-ink-2)" }}>{detail}</span>}
    </div>
  );
}
