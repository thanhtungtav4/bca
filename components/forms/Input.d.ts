import * as React from "react";
/**
 * Labelled text field for the contact form. Set `multiline` for a textarea.
 * Intentional addition: friendlier alias of the kit's `form-group` (see FormGroup).
 * @startingPoint section="Forms" subtitle="Labelled input / textarea" viewport="400x96"
 */
export interface InputProps {
  label?: string;
  placeholder?: string;
  /** Render a textarea. @default false */
  multiline?: boolean;
  rows?: number;
  type?: string;
  name?: string;
  value?: string;
  defaultValue?: string;
  onChange?: (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => void;
  style?: React.CSSProperties;
}
export declare function Input(props: InputProps): JSX.Element;
