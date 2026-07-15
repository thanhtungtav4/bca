import * as React from "react";
/** Figma "form-title/default" — the label above a form field. */
export interface FormTitleProps {
  children?: React.ReactNode;
  required?: boolean;
  htmlFor?: string;
  style?: React.CSSProperties;
}
export declare function FormTitle(props: FormTitleProps): JSX.Element;
