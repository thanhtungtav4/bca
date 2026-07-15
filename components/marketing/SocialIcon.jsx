import React from "react";
import { Icon } from "../../assets/icons/Icon.jsx";

/**
 * Circular social chip — the footer's translucent-white LinkedIn treatment.
 * `name` is any icon from the materialized set (see assets/icons/Icon.d.ts),
 * e.g. "SocialIconSocialLinkedInStyleWhiteType".
 */
export function SocialIcon({ name = "SocialIconSocialLinkedInStyleWhiteType", href = "#", size = 40, glyph = 16, bg = "rgba(255,255,255,0.4)", color = "var(--bca-white)", style = {} }) {
  return (
    <a
      href={href}
      aria-label={name}
      style={{
        width: size,
        height: size,
        borderRadius: "var(--radius-pill)",
        background: bg,
        color,
        display: "inline-flex",
        alignItems: "center",
        justifyContent: "center",
        flexShrink: 0,
        ...style,
      }}
    >
      <Icon name={name} size={glyph} />
    </a>
  );
}
