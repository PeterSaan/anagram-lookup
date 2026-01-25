import Button from '@/components/button';
import ImportResponse from '@/components/importResponse';
import TranslateButton from '@/components/translateButton';
import { Head, Link } from '@inertiajs/react';
import { FormEvent, useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function Import() {
  const [t] = useTranslation();
  const [batchId, setBatchId] = useState(localStorage.getItem('batchId'));
  const [inputDisabled, setInputDisabled] = useState(batchId !== null);
  const [importUrl, setImportUrl] = useState('');
  const [importStatus, setImportStatus] = useState(
    batchId ? 'importing' : 'enterToImport',
  );

  useEffect(() => {
    if (!batchId) return;

    const interval = setInterval(async () => {
      const res = await fetch(`/api/job/batch-progress/${batchId}`);
      const resText = await res.text();

      if (!res.ok) {
        setImportStatus('batchProblem');
        setInputDisabled(false);
        localStorage.removeItem('batchId');
        clearInterval(interval);
        return;
      } else if (res.status === 201) {
        setImportStatus('finished');
        setInputDisabled(false);
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

    if (res.status === 404) {
      setInputDisabled(false);
      setImportStatus('noWords');
      return;
    } else if (res.status === 400 || res.status === 500) {
      setInputDisabled(false);
      setImportStatus('tryAgain');
      return;
    } else if (res.status === 200) {
      setInputDisabled(false);
      setImportStatus('alreadyImported');
      return;
    }

    localStorage.setItem('batchId', resText);
    setBatchId(resText);
  }

  return (
    <>
      <Head title={t('import_words')} />
      <div className="relative flex min-h-screen bg-slate-800 px-3">
        <div className="absolute top-3 right-3">
          <TranslateButton />
        </div>
        <div className="grid w-full gap-y-7 text-gray-100 sm:gap-y-10">
          <div className="flex h-full flex-col items-center font-semibold">
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
              <label className="flex w-80 flex-col pb-5 text-left text-lg text-gray-500 sm:w-100 sm:text-xl">
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
              <Button
                size="lg"
                type={'submit'}
                disabled={inputDisabled}
                text={t('import_btn')}
                customStyling="mx-auto"
              />
            </form>
          </div>
          <div className="flex items-start justify-center">
            <Link href={'/'} viewTransition>
              <Button text={t('back')} size="sm" />
            </Link>
          </div>
        </div>
      </div>
    </>
  );
}
