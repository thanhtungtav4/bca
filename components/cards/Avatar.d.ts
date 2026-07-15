import * as React from "react";
/** Figma "Avatar" — a portrait image block (square or circle). */
export interface AvatarProps {
  photo?: string;
  name?: string;
  /** @default 96 */
  size?: number;
  /** @default "square" */
  shape?: "square" | "circle";
  style?: React.CSSProperties;
}
export declare function Avatar(props: AvatarProps): JSX.Element;
