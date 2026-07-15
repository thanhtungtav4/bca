import * as React from "react";
/** Figma "_Nav group" — a row/column of NavItems. */
export interface NavGroupProps {
  links?: string[];
  active?: string;
  /** Render white for dark backgrounds. @default false */
  onDark?: boolean;
  /** @default "row" */
  direction?: "row" | "column";
  gap?: number;
  style?: React.CSSProperties;
}
export declare function NavGroup(props: NavGroupProps): JSX.Element;
