import React from 'react';
import { Lato } from 'next/font/google';
import { cookies } from 'next/headers';
import Footer from '@/components/footer/Footer';
import Header from '@/components/header/Header';
import { LangProvider } from '@/hooks/LangProvider';
import { MouseObserver } from '@/hooks/MouseObserver';
import { ScrollObserver } from '@/hooks/ScrollObserver';
import '@/style/global.scss';

export const lato = Lato({
  weight: ['300', '400', '700'],
  style: ['normal', 'italic'],
  subsets: ['latin'],
});

export const metadata = {
  author: 'MrCaktuz',
  title: 'Markup Consult',
  description:
    'Web developers, UI/UX and accessibility experts. Markup Consult will guide you every step of the way',
  appleWebApp: {
    title: 'Markup Consult',
  },
  manifest: '/favicon/site.webmanifest',
};

export default async function RootLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ lang: string }>;
}) {
  const cookieStore = await cookies();
  const theme = cookieStore.get('theme')?.value || 'default';
  const { lang } = await params;

  return (
    <html lang={lang} data-theme={theme}>
      <head>
        <meta charSet="UTF-8" />
        <meta httpEquiv="X-UA-Compatible" content="IE=edge" />

        <link
          rel="icon"
          type="image/png"
          href="/favicon/favicon-96x96.png"
          sizes="96x96"
        />
        <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
        <link rel="shortcut icon" href="/favicon/favicon.ico" />
        <link
          rel="apple-touch-icon"
          sizes="180x180"
          href="/favicon/apple-touch-icon.png"
        />
      </head>
      <body>
        <ScrollObserver />
        <MouseObserver />
        <LangProvider defaultLang={lang}>
          <Header />
          <main>{children}</main>
          <Footer />
        </LangProvider>
      </body>
    </html>
  );
}
