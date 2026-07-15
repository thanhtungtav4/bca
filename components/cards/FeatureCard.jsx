import React from "react";
import { Button } from "../core/Button.jsx";

/**
 * Insight feature card: a photo with a dark frosted-glass panel over it holding a
 * headline, supporting copy and a ghost "Explore" link. Matches the homepage
 * "_Feature Item" glass cards.
 */
export function FeatureCard({
  image,
  headline = "Identifying the right partner is crucial for success",
  description = "",
  cta = "Explore",
  height = 452,
  style = {},
}) {
  return (
    <div
      style={{
        position: "relative",
        height,
        borderRadius: "var(--radius)",
        overflow: "hidden",
        background: "var(--bca-border)",
        ...style,
      }}
    >
      {image && <img src={image} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover" }} />}
      <div
        style={{
          position: "absolute",
          left: 61,
          top: 90,
          right: 56,
          borderRadius: "var(--radius)",
          background: "rgba(36,36,36,0.5)",
          backdropFilter: "blur(4px)",
          WebkitBackdropFilter: "blur(4px)",
          padding: "32px 24px",
          display: "flex",
          flexDirection: "column",
          gap: 32,
          boxSizing: "border-box",
        }}
      >
        <div style={{ display: "flex", flexDirection: "column", gap: 16 }}>
          <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "100%", color: "var(--bca-white)" }}>{headline}</span>
          {description && (
            <span style={{ fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 16, lineHeight: "24px", color: "var(--bca-white)" }}>{description}</span>
          )}
        </div>
        {cta && (
          <span style={{ display: "inline-flex" }}>
            <Button variant="ghost" icon size="sm" style={{ color: "var(--bca-white)" }}>{cta}</Button>
          </span>
        )}
      </div>
    </div>
  );
}
