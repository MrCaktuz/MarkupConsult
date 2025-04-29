'use client';

import React from 'react';
import { usePathname, useRouter } from 'next/navigation';
import { useLang } from '@/context/LangContext';
import { LANGS_AVAILABLE } from '@/utils/const';
import styles from './langSwitch.module.scss';

type LangSwitchProps = {
  helper?: string;
};

export default function LangSwitch({
  helper = 'change lang',
}: LangSwitchProps) {
  const { lang } = useLang();
  const router = useRouter();
  const pathname = usePathname();
  const langOptions = Object.values(LANGS_AVAILABLE);

  const onChangeLang = () => {
    const nextLang =
      langOptions.find((langOption) => langOption !== lang) || '';
    const newPath = pathname.replace(`/${lang}`, `/${nextLang}`);
    document.cookie = `lang=${nextLang}; path=/`;
    document.documentElement.setAttribute('lang', nextLang);
    router.push(newPath);
  };

  return (
    <button
      className={`${styles.langSwitch} lang${langOptions.indexOf(lang)}`}
      title={helper}
      aria-label={helper}
      onClick={onChangeLang}
    >
      {langOptions &&
        langOptions.map((langOption) => {
          return (
            <span key={langOption} className={styles.lang}>
              {langOption}
            </span>
          );
        })}
    </button>
  );
}
