import { ImportResponseCompProps } from '@/types';
import { useTranslation } from 'react-i18next';

export default function ImportResponse({ status }: ImportResponseCompProps) {
  const [t] = useTranslation();

  let response: string;

  switch (status) {
    case '':
      return;
    case 'importing':
      response = t('importing');
      break;
    case 'alreadyImported':
      response = t('url_already_imported');
      break;
    case 'enterToImport':
      response = t('enter_a_url_to_import_from');
      break;
    case 'finished':
      response = t('import_finished');
      break;
    case 'noWords':
      response = t('no_words_were_found_from_url');
      break;
    case 'tryAgain':
      response = t('problem_with_importing_try_again');
      break;
    case 'batchProblem':
      response = t('stored_batch_not_found');
      break;
    default:
      response = t('import_progress', { perc: status.split(' ')[1] });
  }

  return response;
}
