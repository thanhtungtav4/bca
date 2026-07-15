import React from "react";

/** White logo tile with a hairline border — the key-partners grid unit. */
export function ClientLogo({ logo, name = "", width = 176, height = 112, style = {} }) {
  return (
    <div
      style={{
        width,
        height,
        borderRadius: "var(--radius)",
        background: "var(--bca-white)",
        boxShadow: "inset 0 0 0 1px rgba(0,0,0,0.1)",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        overflow: "hidden",
        ...style,
      }}
    >
      {logo ? (
        <img src={logo} alt={name} style={{ maxWidth: "62%", maxHeight: "62%", objectFit: "contain" }} />
      ) : (
        <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 16, color: "var(--bca-muted)" }}>{name}</span>
      )}
    </div>
  );
}
