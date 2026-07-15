import * as React from "react";
/** Figma "People" — a row of team-member `Avatar` portraits. */
export interface PeopleProps {
  /** Team members. */
  people?: { photo?: string; name?: string }[];
  /** Avatar size. @default 96 */
  size?: number;
  gap?: number;
  /** @default "square" */
  shape?: "square" | "circle";
  style?: React.CSSProperties;
}
export declare function People(props: PeopleProps): JSX.Element;
