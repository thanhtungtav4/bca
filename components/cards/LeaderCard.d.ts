import * as React from "react";
/**
 * Leadership / team member card — portrait over a grey plate, name and role beneath.
 * Intentional addition: composes the leadership frames (Avatar + name + role) into one card.
 * @startingPoint section="Cards" subtitle="Team member portrait card" viewport="384x436"
 */
export interface LeaderCardProps {
  /** Cut-out portrait URL. */
  photo?: string;
  name?: string;
  role?: string;
  style?: React.CSSProperties;
}
export declare function LeaderCard(props: LeaderCardProps): JSX.Element;
