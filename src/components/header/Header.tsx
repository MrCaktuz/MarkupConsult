"use client";

import React from "react";
import styles from "./header.module.scss";
import ThemeCta from "@/components/themeCta/ThemeCta";
import LangCta from "../langBtn/LangCta";

export default function Header() {
  return (
    <header className={styles.header}>
      <div className="container">
        <div className={styles.header__content}>
          <h1 className={`${styles.header__brand} h5`}>Markup Consult</h1>
          <div className={styles.header__subGroup}>
            <LangCta trad={"Change Lang"} />
            <ThemeCta />
          </div>
        </div>
      </div>
    </header>
  );
}
