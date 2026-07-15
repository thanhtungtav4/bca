import React from "react";

/** Service tile: full-bleed photo with a frosted caption bar naming the service. */
export function ServiceCard({ image, title = "Strategy", height = 257, href = "#", style = {} }) {
  return (
    <a
      href={href}
      style={{
        position: "relative",
        display: "block",
        height,
        borderRadius: "var(--radius)",
        overflow: "hidden",
        background: "var(--bca-border)",
        textDecoration: "none",
        transition: "var(--transition)",
        ...style,
      }}
    >
      {image && (
        <img src={image} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover" }} />
      )}
      <div
        style={{
          position: "absolute",
          left: 0,
          right: 0,
          bottom: 0,
          height: 61,
          display: "flex",
          alignItems: "center",
          padding: "0 32px",
          background: "rgba(222,225,230,0.8)",
          backdropFilter: "blur(4px)",
          WebkitBackdropFilter: "blur(4px)",
          boxSizing: "border-box",
        }}
      >
        <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "40px", color: "var(--bca-black)", whiteSpace: "nowrap" }}>
          {title}
        </span>
      </div>
    </a>
  );
}
