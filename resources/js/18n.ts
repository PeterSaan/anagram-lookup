import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import * as enLoc from './locales/en.json';
import * as etLoc from './locales/et.json';

i18n.use(initReactI18next).init({
  resources: {
    en: { translation: enLoc },
    et: { translation: etLoc },
  },
  fallbackLng: 'en',
  interpolation: {
    escapeValue: false,
  },
});

export default i18n;
