import React from "react";

/** Figma "Heading project": a navy project/section heading, 40px bold, tight tracking. */
export function HeadingProject({ children = "Contact Us", size = 40, color = "var(--bca-navy)", style = {} }) {
  return (
    <h2 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: size, lineHeight: "100%", letterSpacing: "-0.025em", color, ...style }}>
      {children}
    </h2>
  );
}
