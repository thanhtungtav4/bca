import React from "react";

/**
 * Figma "Store badge" / "_App badges": an app-store download badge. The official
 * App Store / Google Play marks are third-party brand assets and are NOT redrawn —
 * drop the official badge PNG in via `src`. Falls back to a labelled placeholder.
 */
export function StoreBadge({ src, store = "appstore", href = "#", height = 56, style = {} }) {
  const label = store === "googleplay" ? "Google Play" : "App Store";
  return (
    <a href={href} aria-label={label} style={{ display: "inline-flex", height, ...style }}>
      {src ? (
        <img src={src} alt={label} style={{ height, width: "auto", display: "block" }} />
      ) : (
        <span style={{ display: "inline-flex", alignItems: "center", height, padding: "0 20px", borderRadius: "var(--radius)", background: "var(--bca-black)", color: "var(--bca-white)", fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 14 }}>
          {label} — add badge asset
        </span>
      )}
    </a>
  );
}
