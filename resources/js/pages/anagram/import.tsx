import { ImportPageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Import({ isImported }: ImportPageProps) {
  const [isImportedProp, setIsImportedProp] = useState(isImported);
  const [loading, setLoading] = useState(false);
  const [importResponse, setImportResponse] = useState(
    isImported ? 'Words have already been imported' : 'You can import!',
  );

  const importWords = async () => {
    setLoading(true);
    setImportResponse('Importing...');

    const res = await fetch('/api/import-words', { method: 'POST' });

    if (!res.ok) {
      setLoading(false);
      setImportResponse('Problem with importing the words');
      return;
    }

    setLoading(false);
    setIsImportedProp(true);
    return setImportResponse('Words have been imported successfully!');
  };

  return (
    <>
      <Head title="Import words" />
      <div className="flex min-h-screen bg-slate-800">
        <div className="grid w-full gap-y-10 text-gray-100">
          <div className="flex h-full flex-col items-center text-4xl font-semibold">
            <p className="my-auto text-center">{importResponse}</p>
            <button
              onClick={importWords}
              className="cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 disabled:cursor-not-allowed disabled:border-none disabled:bg-gray-500 disabled:text-gray-100/60"
              disabled={isImportedProp || loading}
            >
              Import
            </button>
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
