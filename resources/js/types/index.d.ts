export type FindPageProps = { isImported: boolean };
export type ImportPageProps = { isImported: boolean };

export type ButtonComponentProps = {
  customStyling?: string;
  disabled?: boolean;
  size: 'lg' | 'sm';
  text: string;
  type?: 'button' | 'submit' | 'reset';
};
export type FindResponseCompProps = { status: string };
export type ImportResponseCompProps = { status: string };
