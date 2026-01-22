import TranslateButton from '@/components/translateButton';
import { Head, Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Welcome() {
  const [t] = useTranslation();

  return (
    <>
      <Head title={t('home')} />
      <div className="relative flex min-h-screen bg-slate-800">
        <div className="absolute top-5 right-5">
          <TranslateButton />
        </div>
        <div className="inline-flex w-full items-center justify-center gap-20 text-4xl font-semibold text-gray-100">
          <Link
            href={'/anagram/import'}
            className="cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
            viewTransition
          >
            {t('import_words')}
          </Link>
          <Link
            href={'/anagram/find'}
            className="cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
            viewTransition
          >
            {t('find_anagram')}
          </Link>
        </div>
      </div>
    </>
  );
}
