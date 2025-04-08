"use client";

import React from "react";
import styles from "./link.module.scss";
import NextLink from "next/link";
import { useLang } from "@/context/LangContext";

export default function Link({
  children,
  to = "",
  className,
  href,
}: {
  children: React.ReactNode | string;
  to?: string;
  className: string;
  href?: string;
}) {
  const { lang } = useLang();

  if (href) {
    return (
      <a
        className={`${styles.link} ${className}`}
        href={href}
        target="_blank"
        rel="noreferrer"
      >
        {children}
      </a>
    );
  }

  return (
    <NextLink className={`${styles.link} ${className}`} href={`/${lang}/${to}`}>
      {children}
    </NextLink>
  );
}
