import * as React from "react";
/**
 * Navy centered section title with optional lead paragraph.
 * Intentional addition: wraps the kit's `Heading project` with a lead paragraph.
 */
export interface SectionHeadingProps {
  children?: React.ReactNode;
  /** Supporting paragraph under the title. */
  lead?: string;
  /** @default "center" */
  align?: "center" | "left";
  /** Title color. @default navy */
  color?: string;
  style?: React.CSSProperties;
}
export declare function SectionHeading(props: SectionHeadingProps): JSX.Element;
