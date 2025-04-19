'use client';

import React, { useEffect, useState } from 'react';
import Image from 'next/image';
import ThemeCta from '@/components/cta/theme/ThemeCta';
import { useLang } from '@/context/LangContext';
import { HeaderDataType, fetchHeaderData } from '@/services/header.service';
import Link from '../cta/link/Link';
import styles from './header.module.scss';

export default function Header() {
  const { lang } = useLang();
  const [data, setData] = useState<HeaderDataType | null>(null);

  useEffect(() => {
    fetchHeaderData({ lang }).then(setData);
  }, [lang]);

  return (
    <header id="main-header" className={styles.header}>
      <div className="container">
        <div className={styles.content}>
          <Link className={`${styles.subGroup} ${styles.homeLink}`}>
            <Image
              className={styles.logo}
              priority
              src="/icons/logo.svg"
              width={500}
              height={500}
              alt=""
            />
            <h1 className={`${styles.brand} h5`}>{data && data.brand}</h1>
          </Link>
          <div className={styles.subGroup}>
            <ThemeCta helper={data?.themeHelper} />
          </div>
        </div>
      </div>
    </header>
  );
}
