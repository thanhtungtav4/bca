import * as React from "react";
/** Figma "Heading project" — navy 40px bold heading with tight tracking. */
export interface HeadingProjectProps {
  children?: React.ReactNode;
  /** @default 40 */
  size?: number;
  color?: string;
  style?: React.CSSProperties;
}
export declare function HeadingProject(props: HeadingProjectProps): JSX.Element;
