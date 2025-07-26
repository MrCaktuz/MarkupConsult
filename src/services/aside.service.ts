'use client';

type fetchAsideDataProps = {
  lang: string;
};

type ContactInfoType = {
  title: string;
  content: string;
};

export type AsideDataType = {
  contactInfo: ContactInfoType[];
};

export const fetchAsideData = async ({ lang }: fetchAsideDataProps) => {
  const response = await fetch(`/${lang}/api/content?file=aside`);
  const data: AsideDataType = await response.json();
  return data;
};
