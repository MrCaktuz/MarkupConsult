import React from "react";
import styles from "./header.module.scss";
import ThemeToggleBtn from "@/components/themeToggleBtn/ThemeToggleBtn";

function Header() {
  return (
    <header className={styles.header}>
      <div className="container">
        <div className={styles.header__content}>
          <h1 className={`${styles.header__brand} h5`}>Markup Consult</h1>
          <div className={styles.header__subGroup}>
            <ThemeToggleBtn />
          </div>
        </div>
      </div>
    </header>
  );
}

export default Header;
