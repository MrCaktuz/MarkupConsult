import React from 'react';
import styles from './btn.module.scss';

type btnProps = {
  className?: string;
  children: React.ReactNode;
  onClick?: React.MouseEventHandler | undefined;
  title?: string;
  ariaLabel?: string;
};

export default function Btn({
  className,
  children,
  onClick,
  title,
  ariaLabel,
}: btnProps): React.ReactNode {
  return (
    <button
      className={`${styles.btn} ${className}`}
      onClick={onClick}
      title={title}
      aria-label={ariaLabel}
    >
      {children}
    </button>
  );
}
