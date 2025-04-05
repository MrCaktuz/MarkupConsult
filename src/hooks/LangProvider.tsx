"use client";

import React, { useState, ReactNode } from "react";
import { LangContext } from "@/context/LangContext";

type contextProps = { defaultLang: string; children: ReactNode };

export const LangProvider = ({ defaultLang, children }: contextProps) => {
  const [lang, setLang] = useState(defaultLang);

  return (
    <LangContext.Provider value={{ lang, setLang }}>
      {children}
    </LangContext.Provider>
  );
};
