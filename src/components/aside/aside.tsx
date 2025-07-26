import React, { useEffect, useState } from 'react';
import { useLang } from '@/context/LangContext';
import { AsideDataType, fetchAsideData } from '@/services/aside.service';
import styles from './aside.module.scss';

const Aside = () => {
  const { lang } = useLang();
  const [data, setData] = useState<AsideDataType | null>(null);

  useEffect(() => {
    fetchAsideData({ lang }).then(setData);
  }, [lang]);

  return (
    <>
      {data?.contactInfo && (
        <div className={styles.asideContainer}>
          <h2 className={styles.infoTitle}>Contact</h2>
          <ul className={styles.list}>
            {data?.contactInfo.map(
              (info) =>
                info.content && (
                  <li key={info.title} className={styles.item}>
                    <h3 className={styles.title}>{info.title}</h3>
                    <p className={styles.content}>{info.content}</p>
                  </li>
                )
            )}
          </ul>
        </div>
      )}
    </>
  );
};

export default Aside;
