import React from "react";

/**
 * BCA logo (Figma "Logo" / "Logomark"). Renders the real logo image on light
 * backgrounds; on dark, renders the "BCA Partners" wordmark in type (blue),
 * matching the footer treatment in the source.
 */
export function Logo({ src = "assets/logo-bca.png", variant = "image", height = 46, style = {} }) {
  if (variant === "wordmark") {
    return (
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: height * 0.52, lineHeight: 1, color: "var(--bca-blue)", ...style }}>
        BCA Partners
      </span>
    );
  }
  return <img src={src} alt="BCA Partners" style={{ height, width: "auto", display: "block", ...style }} />;
}
