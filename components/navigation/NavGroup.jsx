import React from "react";
import { NavItem } from "./NavItem.jsx";

const LINKS = ["Home", "Our team", "Our services", "Projects", "Research", "Career", "Contact us"];

/** Figma "_Nav group": the horizontal (or vertical) row of nav links. */
export function NavGroup({ links = LINKS, active, onDark = false, direction = "row", gap = 8, style = {} }) {
  return (
    <nav style={{ display: "flex", flexDirection: direction, alignItems: direction === "row" ? "center" : "flex-start", gap, flexWrap: "wrap", ...style }}>
      {links.map((l) => (
        <NavItem key={l} active={l === active} onDark={onDark}>
          {l}
        </NavItem>
      ))}
    </nav>
  );
}
