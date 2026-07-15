import * as React from "react";
/** Single navigation link (top bar or footer). */
export interface NavItemProps {
  children?: React.ReactNode;
  /** Current page. @default false */
  active?: boolean;
  /** Render white for dark backgrounds. @default false */
  onDark?: boolean;
  href?: string;
  style?: React.CSSProperties;
}
export declare function NavItem(props: NavItemProps): JSX.Element;
