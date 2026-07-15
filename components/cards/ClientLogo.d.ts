import * as React from "react";
/** White partner-logo tile with a 1px inset hairline border. */
export interface ClientLogoProps {
  /** Logo image URL. Falls back to `name` in muted type. */
  logo?: string;
  name?: string;
  /** @default 176 */
  width?: number;
  /** @default 112 */
  height?: number;
  style?: React.CSSProperties;
}
export declare function ClientLogo(props: ClientLogoProps): JSX.Element;
