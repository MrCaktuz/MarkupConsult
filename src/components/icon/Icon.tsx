import React from 'react';
import './icon.scss';

type iconProps = {
  className: string;
};

export default function Icon({ className }: iconProps) {
  return <i className={`icon ${className}`} />;
}
