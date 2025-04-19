'use client';

import React from 'react';
import NextLink from 'next/link';
import { useLang } from '@/context/LangContext';
import styles from './link.module.scss';

export default function Link({
  children,
  to = '',
  className,
  href,
  title = '',
  ariaLabel = '',
  target = '_blanc',
}: {
  children: React.ReactNode | string;
  to?: string;
  className: string;
  href?: string;
  title?: string;
  ariaLabel?: string;
  target?: string;
}) {
  const { lang } = useLang();

  if (href) {
    return (
      <a
        className={`${styles.link} ${className}`}
        href={href}
        title={title}
        aria-label={ariaLabel}
        target={target}
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
