import React from "react";
import { Button } from "../core/Button.jsx";

/** Full-width blue CTA band over imagery — "How can we help you succeed?" */
export function ContactBand({
  image = "assets/contact-bg.jpg",
  headline = "How can we help you succeed?",
  cta = "Contact us",
  style = {},
}) {
  return (
    <div
      style={{
        position: "relative",
        overflow: "hidden",
        display: "flex",
        alignItems: "center",
        justifyContent: "space-between",
        gap: 32,
        padding: "53px 216px",
        minHeight: 162,
        boxSizing: "border-box",
        backgroundImage: `linear-gradient(rgba(0,121,166,0.8),rgba(0,121,166,0.8))${image ? `, url(${image})` : ""}`,
        backgroundSize: "cover",
        backgroundPosition: "center",
        ...style,
      }}
    >
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 30, lineHeight: "40px", color: "var(--bca-white)" }}>{headline}</span>
      <Button variant="filled" style={{ background: "var(--bca-navy)" }}>{cta}</Button>
    </div>
  );
}
