import * as React from "react";
/** Figma "_Feature Item" — a plain rounded photo block; the atomic image unit. */
export interface FeatureItemProps {
  image?: string;
  width?: number | string;
  /** @default 280 */
  height?: number;
  /** @default 4 */
  radius?: number;
  children?: React.ReactNode;
  style?: React.CSSProperties;
}
export declare function FeatureItem(props: FeatureItemProps): JSX.Element;
