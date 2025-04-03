import React from "react";
import styles from "./footer.module.scss";

function Footer() {
  return (
    <footer className={styles.footer}>
      <div className="container">
        <div className={styles.footer__content}>Socials</div>
      </div>
    </footer>
  );
}

export default Footer;
