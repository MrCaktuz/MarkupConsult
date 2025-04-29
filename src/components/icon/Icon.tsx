import React from 'react';
import './icon.scss';

type iconProps = {
  className?: string;
  size?: string;
  iconName: string;
};

export default function Icon({ className, size = '5', iconName }: iconProps) {
  if (iconName) {
    return <i className={`icon size${size} ${iconName} ${className ?? ''}`} />;
  }
  return null;
}
