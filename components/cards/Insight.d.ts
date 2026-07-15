import * as React from "react";
/** Figma "Insight" — the text content of a project card (eyebrow, headline, description). */
export interface InsightProps {
  eyebrow?: string;
  title?: string;
  description?: string;
  style?: React.CSSProperties;
}
export declare function Insight(props: InsightProps): JSX.Element;
