"use client";

import React, { useEffect, useState } from "react";
import styles from "./page.module.scss";
import { useLang } from "@/context/LangContext";
import { fetchHomeData, HomeData } from "@/services/home.service";

export default function Home() {
  const { lang } = useLang();
  const [data, setData] = useState<HomeData | null>(null);

  useEffect(() => {
    fetchHomeData({ lang }).then(setData);
  }, [lang]);

  return (
    <div className={styles.page}>
      <p className="">{data?.intro}</p>
      <p className="h6">{data?.wip}</p>
    </div>
  );
}
