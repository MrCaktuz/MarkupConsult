'use client';

import { createContext, useContext } from 'react';

interface LangContextType {
  lang: string;
  setLang: (lang: string) => void;
}

export const LangContext = createContext<LangContextType | undefined>(
  undefined
);

export const useLang = () => {
  const context = useContext(LangContext);
  if (!context) {
    throw new Error('useLang must be used within a LangProvider');
  }
  return context;
};
