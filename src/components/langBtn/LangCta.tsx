"use client";
import React from "react";
import styles from "./lang-cta.module.scss";
import { DropdownMenu } from "radix-ui";
import { useLang } from "@/context/LangContext";
import Icon from "../icon/Icon";
import { LANGS_AVAILABLE } from "@/utils/const";

type LangCtaProps = {
  trad: string;
};

export default function LangCta({ trad }: LangCtaProps) {
  const { lang, setLang } = useLang();

  const onChangeLang = (newLang: string) => {
    document.cookie = `lang=${newLang}`;
    document.documentElement.setAttribute("lang", newLang);
    setLang(newLang);
  };

  return (
    <DropdownMenu.Root>
      <DropdownMenu.Trigger asChild>
        <button className={styles.langCta} title={trad} aria-label={trad}>
          <Icon className={`${styles.langCta__icon} icon--4 icon--globe`} />
          <span>{lang}</span>
        </button>
      </DropdownMenu.Trigger>

      <DropdownMenu.Portal>
        <DropdownMenu.Content className={styles.langCta__list} sideOffset={10}>
          {Object.values(LANGS_AVAILABLE).map((langChoice) => {
            if (lang !== langChoice) {
              return (
                <DropdownMenu.Item
                  key={langChoice}
                  className={styles.langCta__item}
                  onClick={() => onChangeLang(langChoice)}
                >
                  {langChoice}
                </DropdownMenu.Item>
              );
            }
          })}
        </DropdownMenu.Content>
      </DropdownMenu.Portal>
    </DropdownMenu.Root>
  );
}
