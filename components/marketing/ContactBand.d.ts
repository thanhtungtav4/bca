import * as React from "react";
/**
 * Full-width blue call-to-action band over a photo, with a headline and a filled button.
 * @startingPoint section="Marketing" subtitle="Blue CTA band over imagery" viewport="1440x162"
 */
export interface ContactBandProps {
  /** Background photo (80% blue overlay is applied automatically). */
  image?: string;
  headline?: string;
  cta?: string;
  style?: React.CSSProperties;
}
export declare function ContactBand(props: ContactBandProps): JSX.Element;
