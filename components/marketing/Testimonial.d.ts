import * as React from "react";
/**
 * Testimonial card — quote mark, quote body and attribution on a soft section-grey plate.
 * @startingPoint section="Marketing" subtitle="Client testimonial card" viewport="520x280"
 */
export interface TestimonialProps {
  quote?: string;
  name?: string;
  role?: string;
  style?: React.CSSProperties;
}
export declare function Testimonial(props: TestimonialProps): JSX.Element;
