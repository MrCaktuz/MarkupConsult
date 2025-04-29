'use client';

import React, { useEffect, useState } from 'react';
import Image from 'next/image';
import Section from '@/components/portfolio/section/section';
import { useLang } from '@/context/LangContext';
import {
  PortfolioData,
  fetchPortfolioData,
} from '@/services/portfolio.service';
import styles from './portfolio.module.scss';

export default function Portfolio() {
  const { lang } = useLang();
  const [data, setData] = useState<PortfolioData | null>(null);

  useEffect(() => {
    fetchPortfolioData({ lang }).then(setData);
  }, [lang]);

  return (
    <div className="container">
      <div className={styles.content}>
        <div className={`${styles.photoContainer} scrollObservedLeft`}>
          <Image
            className={styles.photo}
            priority
            src="/img/portrait.png"
            width={500}
            height={500}
            alt=""
          />
        </div>
        <div className={`${styles.titleContainer} scrollObservedRight`}>
          <h1 className={styles.title}>{data?.title}</h1>
          <p className={styles.subtitle}>{data?.subtitle}</p>
        </div>
        {/* <div className={styles.pageNavContainer}>Page Nav</div> */}
        <div className={styles.sectionContainer}>
          {data?.career && (
            <Section
              key={data.career.title}
              title={data.career.title}
              items={data.career.items}
            />
          )}
          {data?.education && (
            <Section
              key={data.education.title}
              title={data.education.title}
              items={data.education.items}
            />
          )}
        </div>
      </div>
    </div>
  );
}
