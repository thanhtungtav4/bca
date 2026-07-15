import * as React from "react";
/**
 * Photo card with a dark frosted-glass panel carrying a headline, copy and a ghost link.
 * Intentional addition: BCA-appropriate name for the source's glass "_Feature Item" composition.
 * @startingPoint section="Cards" subtitle="Photo + frosted glass panel" viewport="680x452"
 */
export interface FeatureCardProps {
  image?: string;
  headline?: string;
  description?: string;
  /** Ghost link label; set "" to hide. @default "Explore" */
  cta?: string;
  /** @default 452 */
  height?: number;
  style?: React.CSSProperties;
}
export declare function FeatureCard(props: FeatureCardProps): JSX.Element;
