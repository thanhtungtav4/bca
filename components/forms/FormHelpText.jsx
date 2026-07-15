import React from "react";

/** Figma "form-help-text": helper/error text below a field (14px). */
export function FormHelpText({ children = "We'll never share your details.", error = false, style = {} }) {
  return (
    <span style={{ fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "20px", color: error ? "#c0392b" : "var(--bca-muted)", ...style }}>
      {children}
    </span>
  );
}
