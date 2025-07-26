'use client';

import React, { useEffect, useState } from 'react';
import Image from 'next/image';
import { usePathname } from 'next/navigation';
import { useLang } from '@/context/LangContext';
import { HeaderDataType, fetchHeaderData } from '@/services/header.service';
import Link from '../cta/link/Link';
import Icon from '../icon/Icon';
import styles from './header.module.scss';

export default function Header() {
  const { lang } = useLang();
  const [data, setData] = useState<HeaderDataType | null>(null);
  const pathname = usePathname();

  useEffect(() => {
    fetchHeaderData({ lang }).then(setData);
  }, [lang]);

  const onPrint = async () => {
    const res = await fetch(`/${lang}/api/pdf`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url: window.location.href }),
    });

    if (!res.ok) {
      let err;
      try {
        err = await res.json();
      } catch (e) {
        err = { error: `Erreur inconnue dans la réponse serveur: ${e}` };
      }
      console.error('PDF generation failed:', err.error);
      return;
    }

    const blob = await res.blob();
    const link = document.createElement('a');
    link.href = window.URL.createObjectURL(blob);
    link.download = 'cv_mathieu_claessens.pdf';
    document.body.appendChild(link);
    link.click();
    link.remove();
  };

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
            {pathname === `/${lang}/portfolio` ? (
              <button className={styles.button} onClick={onPrint}>
                {data?.print}
                <Icon iconName="download" size="4" />
              </button>
            ) : (
              data?.portfolio && (
                <Link to="portfolio" className={styles.button}>
                  {data?.portfolio}
                </Link>
              )
            )}
          </div>
        </div>
      </div>
    </header>
  );
}
