import * as React from "react";
/**
 * A service tile — full-bleed photo with a frosted caption bar. Used in the homepage services grid.
 * @startingPoint section="Cards" subtitle="Photo tile with frosted caption" viewport="384x257"
 */
export interface ServiceCardProps {
  /** Background photo URL. */
  image?: string;
  /** @default "Strategy" */
  title?: string;
  /** @default 257 */
  height?: number;
  href?: string;
  style?: React.CSSProperties;
}
export declare function ServiceCard(props: ServiceCardProps): JSX.Element;
