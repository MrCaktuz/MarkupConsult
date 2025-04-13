"use client";
import React from "react";
import styles from "./langSelect.module.scss";
import { DropdownMenu } from "radix-ui";
import Icon from "../../../icon/Icon";
import { LANGS_AVAILABLE } from "@/utils/const";
import { useRouter, usePathname } from "next/navigation";
import { useLang } from "@/context/LangContext";

type LangSelectProps = {
  helper?: string;
};

export default function LangSelect({ helper }: LangSelectProps) {
  const { lang } = useLang();
  const router = useRouter();
  const pathname = usePathname();

  const onChangeLang = (newLang: string) => {
    const newPath = pathname.replace(`/${lang}`, `/${newLang}`);
    router.push(newPath);
    document.cookie = `lang=${newLang}`;
    document.documentElement.setAttribute("lang", newLang);
  };

  return (
    <DropdownMenu.Root>
      <DropdownMenu.Trigger asChild>
        <button
          className={styles.langSelect}
          title={helper}
          aria-label={helper}
        >
          <Icon className={`${styles.langSelect__icon} icon--4 icon--globe`} />
          <span>{lang}</span>
        </button>
      </DropdownMenu.Trigger>

      <DropdownMenu.Portal>
        <DropdownMenu.Content
          className={styles.langSelect__list}
          sideOffset={10}
        >
          {Object.values(LANGS_AVAILABLE).map((langChoice) => {
            if (lang !== langChoice) {
              return (
                <DropdownMenu.Item
                  key={langChoice}
                  className={styles.langSelect__item}
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
