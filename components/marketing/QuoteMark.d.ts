import * as React from "react";
/** Oversized decorative quotation glyph for testimonials/quotes. */
export interface QuoteMarkProps {
  /** @default blue */
  color?: string;
  /** @default 96 */
  size?: number;
  style?: React.CSSProperties;
}
export declare function QuoteMark(props: QuoteMarkProps): JSX.Element;
