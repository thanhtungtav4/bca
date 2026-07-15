import * as React from "react";
/** Figma "Store badge"/"_App badges" — app-store download badge (supply the official PNG via `src`; not redrawn). */
export interface StoreBadgeProps {
  /** Official badge image URL (App Store / Google Play). */
  src?: string;
  /** @default "appstore" */
  store?: "appstore" | "googleplay";
  href?: string;
  /** @default 56 */
  height?: number;
  style?: React.CSSProperties;
}
export declare function StoreBadge(props: StoreBadgeProps): JSX.Element;
