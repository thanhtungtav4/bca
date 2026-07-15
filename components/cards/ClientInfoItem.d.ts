import * as React from "react";
/** Figma "_Client info item" — a partner logo tile with a name and optional detail line. */
export interface ClientInfoItemProps {
  logo?: string;
  name?: string;
  detail?: string;
  /** @default "center" */
  align?: "center" | "left";
  style?: React.CSSProperties;
}
export declare function ClientInfoItem(props: ClientInfoItemProps): JSX.Element;
