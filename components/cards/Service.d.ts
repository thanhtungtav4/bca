import * as React from "react";
/** Figma "Service" tile — photo with a solid frosted caption bar (22px bold title). */
export interface ServiceProps {
  image?: string;
  /** @default "Mergers & Acquisitions (M&As)" */
  title?: string;
  /** @default 256 */
  height?: number;
  href?: string;
  style?: React.CSSProperties;
}
export declare function Service(props: ServiceProps): JSX.Element;
