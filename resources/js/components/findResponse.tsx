import { FindResponseCompProps } from '@/types';
import { useTranslation } from 'react-i18next';

export default function FindResponse({ status }: FindResponseCompProps) {
  const [t] = useTranslation();

  let response: string;

  switch (status) {
    case 'importBefore':
      response = t('import_words_before_searching');
      break;
    case 'searching':
      response = t('searching');
      break;
    case 'invalidWord':
      response = t('invalid_word');
      break;
    case 'noneFound':
      response = t('no_anagram_found_for_that_word');
      break;
    default:
      const searchWord = status.split(' ')[1];
      response = t('anagrams_for', { word: searchWord });
  }

  return `${response} `;
}
