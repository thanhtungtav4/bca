import React from "react";

/**
 * Figma "Mobile": the phone device frame used to preview mobile screens
 * (iPhone 13 mini, 375pt wide in the source). Renders a simple bezel around
 * its children.
 */
export function MobileFrame({ width = 375, height = 812, children, style = {} }) {
  return (
    <div
      style={{
        width,
        height,
        borderRadius: 40,
        background: "#0b1720",
        padding: 10,
        boxSizing: "border-box",
        boxShadow: "0 12px 40px rgba(0,0,0,0.25)",
        ...style,
      }}
    >
      <div style={{ position: "relative", width: "100%", height: "100%", borderRadius: 30, overflow: "hidden", background: "#fff" }}>
        <div style={{ position: "absolute", top: 0, left: "50%", transform: "translateX(-50%)", width: 150, height: 26, background: "#0b1720", borderRadius: "0 0 16px 16px", zIndex: 2 }} />
        <div style={{ width: "100%", height: "100%", overflowY: "auto" }}>{children}</div>
      </div>
    </div>
  );
}
