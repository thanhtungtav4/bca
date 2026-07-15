import * as React from "react";
/** Figma "form-help-text" — helper or error text beneath a field. */
export interface FormHelpTextProps {
  children?: React.ReactNode;
  /** Render in error red. @default false */
  error?: boolean;
  style?: React.CSSProperties;
}
export declare function FormHelpText(props: FormHelpTextProps): JSX.Element;
