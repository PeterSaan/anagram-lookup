import ImportResponse from '@/components/importResponse';
import TranslateButton from '@/components/translateButton';
import { ImportPageProps } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function Import({ isImported }: ImportPageProps) {
  const [t] = useTranslation();
  const [batchId, setBatchId] = useState(localStorage.getItem('batchId'));
  const [inputDisabled, setInputDisabled] = useState(
    isImported || batchId !== null,
  );
  const [importUrl, setImportUrl] = useState('');
  const [importStatus, setImportStatus] = useState(
    batchId ? 'importing' : isImported ? 'alreadyImported' : 'enterToImport',
  );

  useEffect(() => {
    if (!batchId) return;

    const interval = setInterval(async () => {
      const res = await fetch(`/api/job/batch-progress/${batchId}`);
      const resText = await res.text();

      if (!res.ok) {
        setImportStatus('enterToImport');
        setInputDisabled(false);
        localStorage.removeItem('batchId');
        clearInterval(interval);
        return;
      } else if (res.status === 201) {
        setImportStatus('finished');
        localStorage.removeItem('batchId');
        clearInterval(interval);
        return;
      }

      setImportStatus(`progress ${resText}`);
    }, 3000);
  }, [batchId]);

  async function importWords(e: FormEvent) {
    e.preventDefault();
    setInputDisabled(true);
    setImportStatus('importing');

    const res = await fetch('/api/import-words', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: importUrl }),
    });
    const resText = await res.text();

    if (!res.ok) {
      setInputDisabled(false);
      setImportStatus('tryAgain');
      return;
    }

    localStorage.setItem('batchId', resText);
    setBatchId(resText);
  }

  return (
    <>
      <Head title={t('import_words')} />
      <div className="relative flex min-h-screen bg-slate-800">
        <div className="absolute top-5 right-5">
          <TranslateButton />
        </div>
        <div className="grid w-full gap-y-10 text-gray-100">
          <div className="flex h-full flex-col items-center text-4xl font-semibold">
            <div className="my-auto max-w-200 text-center">
              <p className="pb-10 text-3xl">
                <ImportResponse status={importStatus} />
              </p>
            </div>
            <form
              className="flex flex-col"
              method="post"
              onSubmit={importWords}
            >
              <label className="flex w-100 flex-col pb-5 text-left text-xl text-gray-500">
                {t('full_url')}
                <input
                  className="rounded-full border border-gray-50 px-3 py-2 text-gray-100"
                  name="url"
                  type="url"
                  onChange={(e) => setImportUrl(e.target.value)}
                  required
                  disabled={inputDisabled}
                />
              </label>
              <button
                className="mx-auto cursor-pointer rounded-2xl border-2 border-gray-100 px-5 py-3 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75 disabled:cursor-not-allowed disabled:border-transparent disabled:bg-gray-500 disabled:text-gray-100/60"
                type="submit"
                disabled={inputDisabled}
              >
                {t('import_btn')}
              </button>
            </form>
          </div>
          <div className="flex items-start justify-center text-2xl">
            <Link
              href={'/'}
              className="rounded-2xl border-2 border-gray-100 px-4 py-2 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
              viewTransition
            >
              {t('back')}
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
