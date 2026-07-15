import React from "react";
import { Button } from "../core/Button.jsx";

/**
 * Figma "Component 1": the arrow-link button used inline on cards and the hero
 * (Type × Icon variants). A thin wrapper over `Button` in ghost/line/filled form.
 */
export function Component1({ children = "Explore", type = "ghost", icon = true, tone = "dark", ...rest }) {
  const variant = type === "filled" ? "filled" : type === "line" ? "line" : "ghost";
  return (
    <Button variant={variant} icon={icon} tone={tone} size={variant === "ghost" ? "sm" : "lg"} {...rest}>
      {children}
    </Button>
  );
}
