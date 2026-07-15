import React from "react";
import { Input } from "./Input.jsx";

/**
 * Figma "form-group": a labelled form control. This is the source name for the
 * contact-form field; `Input` is the alias used elsewhere in this system.
 */
export function FormGroup(props) {
  return <Input {...props} />;
}
