import React from "react";

/**
 * Figma "_Feature Item": a plain rounded photo block (radius 4). The atomic image
 * unit that larger feature/project cards are built from.
 */
export function FeatureItem({ image, width = "100%", height = 280, radius = 4, style = {}, children }) {
  return (
    <div
      style={{
        position: "relative",
        width,
        height,
        borderRadius: radius,
        overflow: "hidden",
        background: "var(--bca-border)",
        ...style,
      }}
    >
      {image && <img src={image} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover" }} />}
      {children}
    </div>
  );
}
