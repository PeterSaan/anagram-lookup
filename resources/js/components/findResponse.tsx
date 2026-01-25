import { FindResponseCompProps } from '@/types';
import { useTranslation } from 'react-i18next';

export default function FindResponse({ status }: FindResponseCompProps) {
  const [t] = useTranslation();

  let response: string;

  switch (status) {
    case '':
      return;
    case 'enterToSearch':
      response = t('enter_a_word_to_search_for');
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
      response = t('anagrams_for', { word: status.split(' ')[1] });
  }

  return `${response} `;
}
