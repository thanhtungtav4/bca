import React from "react";
import { Avatar } from "./Avatar.jsx";

/**
 * Figma "People": a row of team portraits (used in the homepage team strip).
 * Renders `Avatar`s at a shared size with a consistent gap.
 */
export function People({ people = [], size = 96, gap = 24, shape = "square", style = {} }) {
  return (
    <div style={{ display: "flex", gap, flexWrap: "wrap", alignItems: "flex-start", ...style }}>
      {people.map((p, i) => (
        <Avatar key={p.name || i} photo={p.photo} name={p.name} size={size} shape={shape} />
      ))}
    </div>
  );
}
