import React from "react";

/** Figma "form-title/default": the label above a form field (14px semibold). */
export function FormTitle({ children = "Full name", required = false, htmlFor, style = {} }) {
  return (
    <label htmlFor={htmlFor} style={{ display: "block", fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 14, lineHeight: "20px", color: "var(--bca-ink-2)", ...style }}>
      {children}
      {required && <span style={{ color: "var(--bca-blue)", marginLeft: 2 }}>*</span>}
    </label>
  );
}
