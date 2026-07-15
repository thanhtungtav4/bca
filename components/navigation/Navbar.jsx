import React from "react";
import { NavItem } from "./NavItem.jsx";

const LINKS = ["Home", "Our team", "Our services", "Projects", "Research", "Career", "Contact us"];

/** Sticky white top navbar: BCA logo left, links right, drop shadow. */
export function Navbar({ logoSrc = "assets/logo-bca.png", links = LINKS, active = "Home", style = {} }) {
  return (
    <header
      style={{
        display: "flex",
        alignItems: "center",
        justifyContent: "space-between",
        height: 80,
        padding: "0 112px",
        background: "var(--bca-white)",
        boxShadow: "var(--shadow-nav)",
        boxSizing: "border-box",
        ...style,
      }}
    >
      <img src={logoSrc} alt="BCA Partners" style={{ height: 46, width: "auto", display: "block" }} />
      <nav style={{ display: "flex", alignItems: "center", gap: 8 }}>
        {links.map((l) => (
          <NavItem key={l} active={l === active}>
            {l}
          </NavItem>
        ))}
      </nav>
    </header>
  );
}
