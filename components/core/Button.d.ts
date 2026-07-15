import * as React from "react";
/**
 * BCA primary/secondary button. Filled navy is the main CTA; line for use over
 * imagery; ghost is an inline arrow link.
 * @startingPoint section="Core" subtitle="Navy CTA + line + ghost link" viewport="360x120"
 */
export interface ButtonProps {
  children?: React.ReactNode;
  /** Visual style. @default "filled" */
  variant?: "filled" | "line" | "ghost";
  /** @default "lg" */
  size?: "sm" | "md" | "lg";
  /** Show trailing arrow. @default false */
  icon?: boolean;
  /** Outline/label color for the line variant. @default "dark" */
  tone?: "dark" | "light";
  /** Render element. @default "button" */
  as?: "button" | "a";
  className?: string;
  style?: React.CSSProperties;
}
export declare function Button(props: ButtonProps): JSX.Element;
