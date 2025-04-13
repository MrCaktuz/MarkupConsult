import React from "react";
import styles from "./footer.module.scss";
import Link from "../cta/link/Link";
import Icon from "../icon/Icon";
import LangSwitch from "../cta/lang/switcher/LangSwitch";

function Footer() {
  return (
    <footer className={styles.footer}>
      <div className="container">
        <div className={styles.footer__content}>
          <div className={styles.footer__subContent}>
            <Link
              className={styles.footer__link}
              href="mailto:contact@markupconsult.com"
              title="Email"
              ariaLabel="Email"
              target="_self"
            >
              <Icon className="icon--5 icon--mail" />
              <span className="srOnly">contact@markupconsult.com</span>
            </Link>
            <Link
              className={styles.footer__link}
              href="tel:+32476524285"
              title="Phone"
              ariaLabel="Phone"
              target="_self"
            >
              <Icon className="icon--5 icon--phone" />
              <span className="srOnly">+32476524285</span>
            </Link>
            <Link
              className={styles.footer__link}
              href="https://www.linkedin.com/in/mathieuclaessens/"
              title="LinkedIn"
              ariaLabel="LinkedIn"
            >
              <Icon className="icon--5 icon--linkedin" />
              <span className="srOnly">LinkedIn</span>
            </Link>
            <Link
              className={styles.footer__link}
              href="https://github.com/MrCaktuz"
              title="Github"
              ariaLabel="Github"
            >
              <Icon className="icon--5 icon--github" />
              <span className="srOnly">Github</span>
            </Link>
          </div>
          <LangSwitch />
        </div>
      </div>
    </footer>
  );
}

export default Footer;
