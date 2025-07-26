'use client';

import React, { useEffect, useState } from 'react';
import Image from 'next/image';
import Aside from '@/components/aside/aside';
import PageNav from '@/components/nav/pageNav/PageNav';
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
  const [navSections, setNavSections] = useState<string[] | []>([
    data ? data.career.title : '',
    data ? data.education.title : '',
  ]);

  useEffect(() => {
    fetchPortfolioData({ lang }).then(setData);
  }, [lang]);

  useEffect(() => {
    setNavSections([
      data ? data.career.title : '',
      data ? data.education.title : '',
    ]);
  }, [data]);

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
        <div className={styles.titleContainer}>
          <h1 className={`${styles.title} scrollObservedRight`}>
            {data?.title}
          </h1>
          <p className={`${styles.subtitle} scrollObservedRight`}>
            {data?.subtitle}
          </p>
        </div>
        <div className={styles.pageNavContainer}>
          <PageNav sections={navSections} />
          <Aside />
        </div>
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
