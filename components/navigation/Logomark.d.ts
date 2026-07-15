import * as React from "react";
/** Figma "Logomark" — compact BCA lockup in type (no glyph mark exists in the source). */
export interface LogomarkProps {
  color?: string;
  /** "BCA" cap height in px. @default 40 */
  size?: number;
  style?: React.CSSProperties;
}
export declare function Logomark(props: LogomarkProps): JSX.Element;
