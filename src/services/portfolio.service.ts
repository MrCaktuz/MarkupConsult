'use client';

import { CardProps } from '@/components/portfolio/card/card';

type fetchPortfolioDataProps = {
  lang: string;
};

export type PortfolioData = {
  title: string;
  subtitle: string;
  career: {
    title: string;
    items: CardProps[];
  };
  education: {
    title: string;
    items: CardProps[];
  };
};

export const fetchPortfolioData = async ({ lang }: fetchPortfolioDataProps) => {
  const response = await fetch(`/${lang}/api/content?file=portfolio`);
  const data: PortfolioData = await response.json();
  return data;
};
