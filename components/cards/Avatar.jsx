import React from "react";

/**
 * Figma "Avatar": a portrait image block for team/people rows. Square by default;
 * pass `shape="circle"` for a round crop.
 */
export function Avatar({ photo, name = "", size = 96, shape = "square", style = {} }) {
  return (
    <div
      title={name}
      style={{
        width: size,
        height: size,
        borderRadius: shape === "circle" ? "50%" : "var(--radius)",
        overflow: "hidden",
        background: "var(--bca-border)",
        flexShrink: 0,
        ...style,
      }}
    >
      {photo && <img src={photo} alt={name} style={{ width: "100%", height: "100%", objectFit: "cover", objectPosition: "top center", display: "block" }} />}
    </div>
  );
}
