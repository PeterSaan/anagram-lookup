import { ButtonComponentProps } from '@/types';

export default function Button({
  customStyling,
  disabled,
  size,
  text,
  type,
}: ButtonComponentProps) {
  return (
    <button
      className={
        size === 'lg'
          ? `cursor-pointer rounded-2xl border-2 border-gray-100 px-4 py-2 text-2xl hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-gray-100/60 sm:text-3xl md:px-5 md:py-3 md:text-4xl ` +
            customStyling
          : `cursor-pointer rounded-2xl border-2 border-gray-100 px-3 py-1 text-lg hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 sm:text-xl md:px-4 md:py-2 md:text-2xl ` +
            customStyling
      }
      type={type}
      disabled={disabled}
    >
      {text}
    </button>
  );
}
