import React from 'react';
import { slugify } from '@/utils/function';
import Card, { CardProps } from '../card/card';
import styles from './section.module.scss';

export default function Section({
  title,
  items,
}: {
  title: string;
  items: CardProps[];
}) {
  return (
    <div
      id={`section_${slugify(title)}`}
      className={`${styles.container} scrollObserved`}
    >
      <h2 className={styles.title}>{title}</h2>
      <div className={styles.content}>
        {items && items.map((item, index) => <Card key={index} {...item} />)}
      </div>
    </div>
  );
}
