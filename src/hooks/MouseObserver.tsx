'use client';

import { useEffect } from 'react';

export const MouseObserver = () => {
  useEffect(() => {
    window.addEventListener('mousemove', (oEvent) => {
      document.documentElement.style.setProperty(
        '--pointer-x',
        `${oEvent.clientX}px`
      );
      document.documentElement.style.setProperty(
        '--pointer-y',
        `${oEvent.clientY}px`
      );
    });
  }, []);

  return null;
};
