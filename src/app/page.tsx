import React from "react";
import styles from "./page.module.scss";
import Image from "next/image";

export default async function Home() {
  return (
    <div className={styles.page}>
      <div className={styles.page__logo}>
        <Image src="/favicon/favicon.svg" alt="MC" width={150} height={150} />
      </div>
      <p className="h6">Coming soon...</p>
    </div>
  );
}
