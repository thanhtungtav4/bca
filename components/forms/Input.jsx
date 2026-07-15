import React from "react";

/** Contact-form text field: label above a bordered input. Supports textarea. */
export function Input({ label, placeholder = "", multiline = false, rows = 4, value, defaultValue, onChange, name, type = "text", style = {}, ...rest }) {
  const field = {
    width: "100%",
    fontFamily: "var(--font-sans)",
    fontWeight: 400,
    fontSize: 16,
    lineHeight: "24px",
    color: "var(--bca-ink)",
    background: "var(--bca-white)",
    border: "1px solid var(--bca-border)",
    borderRadius: "var(--radius)",
    padding: "12px 16px",
    boxSizing: "border-box",
    outline: "none",
    transition: "var(--transition)",
    resize: multiline ? "vertical" : "none",
  };
  return (
    <label style={{ display: "flex", flexDirection: "column", gap: 8, ...style }}>
      {label && <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 14, lineHeight: "20px", color: "var(--bca-ink-2)" }}>{label}</span>}
      {multiline ? (
        <textarea rows={rows} placeholder={placeholder} name={name} value={value} defaultValue={defaultValue} onChange={onChange} style={field} {...rest} />
      ) : (
        <input type={type} placeholder={placeholder} name={name} value={value} defaultValue={defaultValue} onChange={onChange} style={field} {...rest} />
      )}
    </label>
  );
}
