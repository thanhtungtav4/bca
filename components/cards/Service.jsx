import React from "react";

/**
 * Service tile (Figma "Service" / Property1=Default): a photo with a solid frosted
 * caption bar carrying the service name in 22px bold. 384×256 in the source.
 */
export function Service({ image, title = "Mergers & Acquisitions (M&As)", height = 256, href = "#", style = {} }) {
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
        ...style,
      }}
    >
      {image && <img src={image} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover" }} />}
      <div
        style={{
          position: "absolute",
          left: 0,
          right: 0,
          bottom: 0,
          height: 60,
          display: "flex",
          alignItems: "center",
          padding: "0 24px",
          background: "rgba(222,225,230,0.8)",
          backdropFilter: "blur(4px)",
          WebkitBackdropFilter: "blur(4px)",
          boxSizing: "border-box",
        }}
      >
        <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 22, lineHeight: "100%", color: "var(--bca-black)" }}>{title}</span>
      </div>
    </a>
  );
}
