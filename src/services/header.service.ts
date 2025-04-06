"use client";

type fetchHeaderDataProps = {
  lang: string;
};

export type HeaderDataType = {
  brand?: string;
  portfolio?: string;
  langHelper?: string;
  themeHelper?: string;
};

export const fetchHeaderData = async ({ lang }: fetchHeaderDataProps) => {
  const response = await fetch(`/${lang}/api/content?file=header`);
  const data: HeaderDataType = await response.json();
  return data;
};
