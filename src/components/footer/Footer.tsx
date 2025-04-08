import React from "react";
import styles from "./footer.module.scss";
import Link from "../cta/link/Link";
import Icon from "../icon/Icon";

function Footer() {
  return (
    <footer className={styles.footer}>
      <div className="container">
        <div className={styles.footer__content}>
          <Link
            className={styles.footer__link}
            href="https://www.linkedin.com/in/mathieuclaessens/"
          >
            <Icon className="icon--4 icon--linkedin" />
            <span className="srOnly">LinkedIn</span>
          </Link>
          <Link
            className={styles.footer__link}
            href="https://github.com/MrCaktuz"
          >
            <Icon className="icon--4 icon--github" />
            <span className="srOnly">Github</span>
          </Link>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
