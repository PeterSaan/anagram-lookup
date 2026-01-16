import { ImportPageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

export default function Import({ isImported, isImporting }: ImportPageProps) {
  const initialStatusText = isImporting
    ? 'Importing...'
    : isImported
      ? 'Words have already been imported'
      : 'Enter a URL to import from';

  const [isBtnDisabled, setIsBtnDisabled] = useState(isImported || isImporting);
  const [importResponse, setImportResponse] = useState(initialStatusText);
  const [importUrl, setImportUrl] = useState('');

  async function importWords(e: FormEvent) {
    e.preventDefault();
    setIsBtnDisabled(true);
    setImportResponse('Importing...');

    const res = await fetch(`/api/import-words`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: importUrl }),
    });

    if (!res.ok) {
      setIsBtnDisabled(false);
      setImportResponse(await res.text());
      return;
    }

    setImportResponse(await res.text());
  }

  return (
    <>
      <Head title="Import words" />
      <div className="flex min-h-screen bg-slate-800">
        <div className="grid w-full gap-y-10 text-gray-100">
          <div className="flex h-full flex-col items-center text-4xl font-semibold">
            <div className="my-auto max-w-200 text-center">
              <p className="pb-10 text-3xl">{importResponse}</p>
            </div>
            <form
              className="flex flex-col"
              method="post"
              onSubmit={importWords}
            >
              <label className="flex w-100 flex-col pb-5 text-left text-xl text-gray-500">
                Full URL:
                <input
                  className="rounded-full border border-gray-50 px-3 py-2 text-gray-100"
                  type="url"
                  onChange={(e) => setImportUrl(e.target.value)}
                  required
                />
              </label>
              <button
                className="mx-auto cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-gray-100/60"
                type="submit"
                disabled={isBtnDisabled}
              >
                Import
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
