import * as React from "react";
/** BCA logo (Figma "Logo"/"Logomark"). Image mark on light, type wordmark on dark. */
export interface LogoProps {
  /** Logo PNG path (image variant). @default "assets/logo-bca.png" */
  src?: string;
  /** @default "image" */
  variant?: "image" | "wordmark";
  /** Render height in px. @default 46 */
  height?: number;
  style?: React.CSSProperties;
}
export declare function Logo(props: LogoProps): JSX.Element;
