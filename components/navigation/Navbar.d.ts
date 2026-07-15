import * as React from "react";
/**
 * Sticky top navigation bar with the BCA wordmark and page links.
 * @startingPoint section="Navigation" subtitle="White sticky top bar" viewport="1440x80"
 */
export interface NavbarProps {
  /** Path to the BCA logo PNG. @default "assets/logo-bca.png" */
  logoSrc?: string;
  links?: string[];
  /** Highlighted link label. @default "Home" */
  active?: string;
  style?: React.CSSProperties;
}
export declare function Navbar(props: NavbarProps): JSX.Element;
