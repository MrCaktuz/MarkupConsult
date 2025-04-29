'use client';

import React, { useEffect, useState } from 'react';
import { useLang } from '@/context/LangContext';
import { HomeData, fetchHomeData } from '@/services/home.service';
import styles from './home.module.scss';

// import Image from "next/image";

export default function Home() {
  const { lang } = useLang();
  const [data, setData] = useState<HomeData | null>(null);
  const [headerHeight, setHeaderHeight] = useState<number>(80);

  useEffect(() => {
    const $header = document.getElementById('main-header');
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
      <section className={styles.section} style={sectionStyle}>
        <div className="container">
          <div className={styles.row}>
            <div className={styles.col}>
              <p className={`${styles.title} h3 scrollObserved`}>
                {data?.intro}
              </p>
              <h2 className={`${styles.subtitle} h5 scrollObserved`}>
                {data?.sectionTitleServices}
              </h2>
            </div>
          </div>
        </div>
      </section>
      {data?.services.map((service) => {
        return (
          <section key={service.title} className={styles.section}>
            <div className="container">
              <div className={styles.row}>
                <div className={styles.col}>
                  <h3 className={`${styles.sectionTitle} scrollObserved`}>
                    {service.title}
                  </h3>
                </div>
                <div
                  className={`${styles.col} ${styles.sectionDescription} scrollObserved`}
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
