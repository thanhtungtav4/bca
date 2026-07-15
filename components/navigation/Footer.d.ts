import * as React from "react";
/**
 * Black site footer with the BCA wordmark, HCMC office address, links and a LinkedIn chip.
 * @startingPoint section="Navigation" subtitle="Black footer with address" viewport="1440x232"
 */
export interface FooterProps {
  /** Company column links. */
  links?: string[];
  /** Services column links. */
  services?: string[];
  /** Office address (newlines preserved). */
  address?: string;
  tel?: string;
  email?: string;
  style?: React.CSSProperties;
}
export declare function Footer(props: FooterProps): JSX.Element;
