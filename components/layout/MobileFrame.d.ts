import * as React from "react";
/** Figma "Mobile" — a phone device frame (iPhone 13 mini, 375pt) wrapping mobile screens. */
export interface MobileFrameProps {
  /** @default 375 */
  width?: number;
  /** @default 812 */
  height?: number;
  children?: React.ReactNode;
  style?: React.CSSProperties;
}
export declare function MobileFrame(props: MobileFrameProps): JSX.Element;
