import React from "react";
import "@/style/global.scss";
import { Lato } from "next/font/google";
import { cookies } from "next/headers";
import Header from "@/components/header/Header";
import Footer from "@/components/footer/Footer";

const lato = Lato({
  weight: ["300", "400", "700"],
  style: ["normal", "italic"],
  subsets: ["latin"],
});

export const metadata = {
  author: "MrCaktuz",
  title: "Markup Consult",
  description:
    "Web developers, UI/UX and accessibility experts. Markup Consult will guide you every step of the way",
  appleWebApp: {
    title: "Markup Consult",
  },
  manifest: "/site.webmanifest",
};

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const cookieStore = await cookies();
  const theme = cookieStore.get("theme")?.value || "default";

  return (
    <html lang="en" data-theme={theme}>
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
      <body className={lato.className}>
        <Header />
        <main>{children}</main>
        <Footer />
      </body>
    </html>
  );
}
