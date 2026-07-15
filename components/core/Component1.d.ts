import * as React from "react";
/** Figma "Component 1" — the inline arrow-link button (maps onto Button's variants). */
export interface Component1Props {
  children?: React.ReactNode;
  /** @default "ghost" */
  type?: "ghost" | "line" | "filled";
  /** @default true */
  icon?: boolean;
  tone?: "dark" | "light";
}
export declare function Component1(props: Component1Props): JSX.Element;
