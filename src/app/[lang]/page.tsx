"use client";

import React, { useEffect, useState } from "react";
import styles from "./page.module.scss";
import { useLang } from "@/context/LangContext";
import { fetchHomeData, HomeData } from "@/services/home.service";
// import Image from "next/image";

export default function Home() {
  const { lang } = useLang();
  const [data, setData] = useState<HomeData | null>(null);
  const [headerHeight, setHeaderHeight] = useState<number>(80);

  useEffect(() => {
    const $header = document.getElementById("main-header");
    if ($header) {
      setHeaderHeight($header.offsetHeight);
    }
  }, []);

  useEffect(() => {
    fetchHomeData({ lang }).then(setData);
  }, [lang]);

  const sectionStyle = {
    height: `calc(100dvh - ${headerHeight}px)`,
  };

  return (
    <div className={styles.page}>
      <section className={styles.page__section} style={sectionStyle}>
        <div className="container">
          <div className={styles.page__row}>
            <div className={styles.page__col}>
              <p className={`${styles.page__title} h3 scrollObserved`}>
                {data?.intro}
              </p>
              {/* <h2
                className={`${styles.page__subtitle} h5 scrollObserved--left`}
              >
                {data?.sectionTitleServices}
              </h2> */}
            </div>
            {/* <div className={`${styles.page__col} ${styles.page__logo}`}>
              <Image src="/icons/logo.svg" alt="" width="200" height="200" />
            </div> */}
          </div>
        </div>
      </section>
      {data?.services.map((service) => {
        return (
          <section key={service.title} className={styles.page__section}>
            <div className="container">
              <div className={styles.page__row}>
                <div className={styles.page__col}>
                  <h3
                    className={`${styles["page__section--title"]} scrollObserved`}
                  >
                    {service.title}
                  </h3>
                </div>
                <div
                  className={`${styles.page__col} ${styles["page__section--description"]} scrollObserved`}
                >
                  <p>{service.description}</p>
                </div>
              </div>
            </div>
          </section>
        );
      })}
    </div>
  );
}
