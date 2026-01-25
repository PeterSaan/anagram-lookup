import Button from '@/components/button';
import FindResponse from '@/components/findResponse';
import TranslateButton from '@/components/translateButton';
import { Head, Link } from '@inertiajs/react';
import { FormEvent, useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function Find() {
  const [t] = useTranslation();
  const [anagrams, setAnagrams] = useState('');
  const [searchWord, setSearchWord] = useState('');
  const [loading, setLoading] = useState(false);
  const [findStatus, setFindStatus] = useState('enterToSearch');

  async function findAnagrams(e: FormEvent) {
    setAnagrams('');
    setFindStatus('searching');
    setLoading(true);
    e.preventDefault();

    const res = await fetch(`/api/find-anagrams/${searchWord}`);

    if (res.status === 400) {
      setLoading(false);
      setFindStatus('invalidWord');
      return;
    } else if (res.status === 204) {
      setLoading(false);
      setFindStatus('noneFound');
      return;
    }

    setLoading(false);

    const resAnagrams: string[] = await res.json();
    setAnagrams(resAnagrams.join(', '));
    setFindStatus(`anagramsFor ${searchWord}`);
    return;
  }

  return (
    <>
      <Head title={t('find_anagram')} />
      <div className="relative flex min-h-screen bg-slate-800 px-3">
        <div className="absolute top-3 right-3">
          <TranslateButton />
        </div>
        <div className="grid w-full gap-y-7 text-gray-100 sm:gap-y-10">
          <div className="flex h-full flex-col items-center font-semibold">
            <div className="my-auto max-w-200 text-center">
              {findStatus !== '' && (
                <p className="pb-10 text-3xl">
                  <FindResponse status={findStatus} />
                </p>
              )}
              <p className='text-4xl'>{anagrams}</p>
            </div>
            <form
              className="flex flex-col"
              method="post"
              onSubmit={findAnagrams}
            >
              <label className="flex w-80 flex-col pb-5 text-left text-lg text-gray-500 sm:w-100 sm:text-xl">
                {t('searchable_word')}
                <input
                  name="searchWord"
                  className="rounded-full border border-gray-50 px-3 py-2 text-gray-100"
                  type="text"
                  onChange={(e) => setSearchWord(e.target.value)}
                  disabled={loading}
                  required
                />
              </label>
              <Button
                size="lg"
                type="submit"
                disabled={loading}
                text={t('search')}
                customStyling="mx-auto"
              />
            </form>
          </div>
          <div className="flex items-start justify-center">
            <Link href={'/'} viewTransition>
              <Button size="sm" text={t('back')} />
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
