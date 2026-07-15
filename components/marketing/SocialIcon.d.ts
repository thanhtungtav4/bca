import * as React from "react";
import type { IconName } from "../../assets/icons/Icon";
/** Round social chip (footer LinkedIn treatment). */
export interface SocialIconProps {
  /** Icon name from the materialized set. @default "SocialIconSocialLinkedInStyleWhiteType" */
  name?: IconName;
  href?: string;
  /** Chip diameter. @default 40 */
  size?: number;
  /** Glyph size. @default 16 */
  glyph?: number;
  bg?: string;
  color?: string;
  style?: React.CSSProperties;
}
export declare function SocialIcon(props: SocialIconProps): JSX.Element;
