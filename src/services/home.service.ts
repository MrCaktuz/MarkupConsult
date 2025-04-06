"use client";

type fetchHomeDataProps = {
  lang: string;
};

export type HomeData = {
  intro?: string;
  wip?: string;
};

export const fetchHomeData = async ({ lang }: fetchHomeDataProps) => {
  const response = await fetch(`/${lang}/api/content?file=home`);
  const data: HomeData = await response.json();
  return data;
};
