"use client";

import React, { useEffect, useState } from "react";
import styles from "./header.module.scss";
import ThemeCta from "@/components/cta/theme/ThemeCta";
import { useLang } from "@/context/LangContext";
import LangCta from "../cta/lang/LangCta";
import { fetchHeaderData, HeaderDataType } from "@/services/header.service";
import Image from "next/image";
import Link from "../cta/link/Link";

export default function Header() {
  const { lang } = useLang();
  const [data, setData] = useState<HeaderDataType | null>(null);

  useEffect(() => {
    fetchHeaderData({ lang }).then(setData);
  }, [lang]);

  return (
    <header id="main-header" className={styles.header}>
      <div className="container">
        <div className={styles.header__content}>
          <Link
            className={`${styles.header__subGroup} ${styles.header__homeLink}`}
          >
            <Image
              className={styles.header__logo}
              src="/icons/logo.svg"
              width={500}
              height={500}
              alt=""
            />
            <h1 className={`${styles.header__brand} h5`}>
              {data && data.brand}
            </h1>
          </Link>
          <div className={styles.header__subGroup}>
            <LangCta helper={data?.langHelper} />
            <ThemeCta helper={data?.themeHelper} />
          </div>
        </div>
      </div>
    </header>
  );
}
