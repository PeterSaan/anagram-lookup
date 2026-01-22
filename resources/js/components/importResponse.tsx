import { ImportResponseCompProps } from '@/types';
import { useTranslation } from 'react-i18next';

export default function ImportResponse({ status }: ImportResponseCompProps) {
  const [t] = useTranslation();

  let response: string;

  switch (status) {
    case 'importing':
      response = t('importing');
      break;
    case 'alreadyImported':
      response = t('words_have_already_been_imported');
      break;
    case 'enterToImport':
      response = t('import_words');
      break;
    case 'finished':
      response = t('import_finished');
      break;
    case 'tryAgain':
      response = t('problem_with_importing_try_again');
      break;
    default:
      const perc = status.split(' ')[1];
      response = t('import_progress', { perc });
  }

  return response;
}
