import 'i18next';

import enLoc from '../locales/en.json';
import etLoc from '../locales/et.json';

declare module 'i18next' {
  interface CustomTypeOptions {
    defaultNS: 'en';
    resources: {
      en: typeof enLoc;
      et: typeof etLoc;
    };
  }
}
