import { FindPageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

export default function Find({ isImported }: FindPageProps) {
  const [anagrams, setAnagrams] = useState('');
  const [searchWord, setSearchWord] = useState('');
  const [loading, setLoading] = useState(false);
  const [responseText, setResponseText] = useState(
    isImported ? '' : 'Import words before searching',
  );

  async function findAnagrams(e: FormEvent) {
    setAnagrams('');
    setResponseText('Searching...');
    setLoading(true);
    e.preventDefault();

    const res = await fetch(`/api/find-anagrams/${searchWord}`);

    if (res.status === 400) {
      setLoading(false);
      setResponseText('Invalid word');
      return;
    } else if (res.status === 204) {
      setLoading(false);
      setResponseText('No anagram found for that word');
      return;
    }

    setLoading(false);

    const resAnagrams: string[] = await res.json();
    setAnagrams(resAnagrams.join(', '));
    setResponseText(`Anagrams for '${searchWord}':`);
    return;
  }

  return (
    <>
      <Head title="Find anagram" />
      <div className="flex min-h-screen bg-slate-800">
        <div className="grid w-full gap-y-10 text-gray-100">
          <div className="flex h-full flex-col items-center text-4xl font-semibold">
            <div className="my-auto max-w-200 text-center">
              {responseText !== '' && (
                <p className="pb-10 text-3xl">{responseText}</p>
              )}
              <p>{anagrams}</p>
            </div>
            <form
              className="flex flex-col"
              method="post"
              onSubmit={findAnagrams}
            >
              <label className="flex w-100 flex-col pb-5 text-left text-xl text-gray-500">
                Searchable word:
                <input
                  name="searchWord"
                  className="rounded-full border border-gray-50 px-3 py-2 text-gray-100"
                  type="text"
                  onChange={(e) => setSearchWord(e.target.value)}
                  disabled={!isImported || loading}
                />
              </label>
              <button
                className="mx-auto cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-gray-100/60"
                type="submit"
                disabled={!isImported || loading}
              >
                Search
              </button>
            </form>
          </div>
          <div className="flex items-start justify-center text-2xl">
            <Link
              href={'/'}
              className="rounded-2xl border-2 border-gray-100 px-4 py-2 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
              viewTransition
            >
              Back
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
