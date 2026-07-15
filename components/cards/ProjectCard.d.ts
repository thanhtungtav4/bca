import * as React from "react";
/**
 * Case-study / insight card: photo, navy eyebrow, headline, optional client line, description.
 * @startingPoint section="Cards" subtitle="Project case-study card" viewport="384x458"
 */
export interface ProjectCardProps {
  image?: string;
  /** Uppercase navy category label. @default "FINTECH PROJECTS" */
  eyebrow?: string;
  title?: string;
  /** Optional client / company name shown under the headline. */
  client?: string;
  description?: string;
  style?: React.CSSProperties;
}
export declare function ProjectCard(props: ProjectCardProps): JSX.Element;
