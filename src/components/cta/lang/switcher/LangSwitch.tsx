"use client";
import React from "react";
import styles from "./langSwitch.module.scss";
import { LANGS_AVAILABLE } from "@/utils/const";
import { useRouter, usePathname } from "next/navigation";
import { useLang } from "@/context/LangContext";

type LangSwitchProps = {
  helper?: string;
};

export default function LangSwitch({
  helper = "change lang",
}: LangSwitchProps) {
  const { lang } = useLang();
  const router = useRouter();
  const pathname = usePathname();
  const langOptions = Object.values(LANGS_AVAILABLE);

  const onChangeLang = () => {
    const nextLang =
      langOptions.find((langOption) => langOption !== lang) || "";
    const newPath = pathname.replace(`/${lang}`, `/${nextLang}`);
    document.cookie = `lang=${nextLang}`;
    document.documentElement.setAttribute("lang", nextLang);
    router.push(newPath);
  };

  return (
    <button
      className={`${styles.LangSwitch} lang--${langOptions.indexOf(lang)}`}
      title={helper}
      aria-label={helper}
      onClick={onChangeLang}
    >
      {langOptions &&
        langOptions.map((langOption) => {
          return (
            <span
              key={langOption}
              className={
                langOption === lang
                  ? styles.LangSwitch__currentLang
                  : styles.LangSwitch__newLang
              }
            >
              {langOption}
            </span>
          );
        })}
    </button>
  );
}
