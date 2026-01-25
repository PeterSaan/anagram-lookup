import Button from '@/components/button';
import TranslateButton from '@/components/translateButton';
import { Head, Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';

export default function Welcome() {
  const [t] = useTranslation();

  return (
    <>
      <Head title={t('home')} />
      <div className="relative flex min-h-screen bg-slate-800 px-3">
        <div className="absolute top-3 right-3">
          <TranslateButton />
        </div>
        <div className="inline-flex w-full items-center justify-center gap-5 font-semibold text-gray-100 sm:gap-10 md:gap-20">
          <Link href={'/anagram/import'} viewTransition>
            <Button text={t('import_words')} size="lg" />
          </Link>
          <Link href={'/anagram/find'} viewTransition>
            <Button text={t('find_anagram')} size="lg" />
          </Link>
        </div>
      </div>
    </>
  );
}
