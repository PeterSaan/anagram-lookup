import TranslateIcon from '@/assets/icons/translate';
import { useEffect, useState } from 'react';
import { useTranslation } from 'react-i18next';

export default function TranslateButton() {
  const [_, i18n] = useTranslation();
  const [lang, setLang] = useState(localStorage.getItem('anagram-lang'));

  useEffect(() => {
    if (lang !== null) i18n.changeLanguage(lang);
  }, []);

  function handleTranslate() {
    if (lang !== 'et') {
      i18n.changeLanguage('et');
      localStorage.setItem('anagram-lang', 'et');
      setLang('et');
      return;
    }

    i18n.changeLanguage('en');
    localStorage.setItem('anagram-lang', 'en');
    setLang('en');
  }

  return (
    <button
      className="cursor-pointer rounded-2xl border-2 border-gray-100 px-4 py-3 text-gray-100 hover:border-transparent hover:bg-gray-100 hover:text-slate-800 active:bg-gray-100/75"
      onClick={handleTranslate}
    >
      <TranslateIcon />
    </button>
  );
}
