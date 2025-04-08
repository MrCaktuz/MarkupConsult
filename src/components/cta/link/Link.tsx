"use client";

import React from "react";
import styles from "./link.module.scss";
import NextLink from "next/link";
import { useLang } from "@/context/LangContext";

export default function Link({
  children,
  to = "",
  className,
}: {
  children: React.ReactNode | string;
  to: string;
  className: string;
}) {
  const { lang } = useLang();

  return (
    <NextLink className={`${styles.link} ${className}`} href={`/${lang}/${to}`}>
      {children}
    </NextLink>
  );
}
