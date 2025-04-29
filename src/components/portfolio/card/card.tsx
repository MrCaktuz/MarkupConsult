import React from 'react';
import Icon from '@/components/icon/Icon';
import styles from './card.module.scss';

export type CardProps = {
  title: string;
  from?: string;
  to?: string;
  description: string;
  tags?: string;
  link?: string;
};

export default function Card({
  title,
  from,
  to,
  description,
  tags,
  link,
}: CardProps) {
  const CardTag = link ? 'a' : 'div';
  const CardAttr = link
    ? {
        href: link,
        target: '_blanc',
      }
    : {};

  return (
    <CardTag {...CardAttr} className={styles.container}>
      <div className={`${styles.card} scrollObservedRight`}>
        <h3 className={styles.cardTitle}>{title}</h3>
        <Icon className={styles.linkIcon} size="4" iconName="externalLink" />
        {(to || from) && (
          <p className={styles.legendContainer}>
            {to && <span className={styles.legend}>{to}</span>}
            {from && <span className={styles.legend}>{from}</span>}
          </p>
        )}
        {description && (
          <div className={styles.cardDescription}>{description}</div>
        )}

        {tags && (
          <p className={styles.tagContainer}>
            {tags.split(';').map((tag, index) => {
              if (tag) {
                return (
                  <span key={`key_${index}`} className={styles.tag}>
                    {tag}
                  </span>
                );
              }
            })}
          </p>
        )}
      </div>
    </CardTag>
  );
}
