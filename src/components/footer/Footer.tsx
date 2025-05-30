import React from 'react';
import LangSwitch from '../cta/lang/switcher/LangSwitch';
import Link from '../cta/link/Link';
import ThemeCta from '../cta/theme/ThemeCta';
import Icon from '../icon/Icon';
import styles from './footer.module.scss';

function Footer() {
  return (
    <footer className={styles.footer}>
      <div className="container">
        <div className={styles.content}>
          <div className={styles.subContent}>
            <Link
              className={styles.link}
              href="mailto:contact@markupconsult.com"
              title="Email"
              ariaLabel="Email"
              target="_self"
            >
              <Icon iconName="mail" />
              <span className="srOnly">contact@markupconsult.com</span>
            </Link>
            <Link
              className={styles.link}
              href="tel:+32476524285"
              title="Phone"
              ariaLabel="Phone"
              target="_self"
            >
              <Icon iconName="phone" />
              <span className="srOnly">+32476524285</span>
            </Link>
            <Link
              className={styles.link}
              href="https://www.linkedin.com/in/mathieuclaessens/"
              title="LinkedIn"
              ariaLabel="LinkedIn"
            >
              <Icon iconName="linkedin" />
              <span className="srOnly">LinkedIn</span>
            </Link>
            <Link
              className={styles.link}
              href="https://github.com/MrCaktuz"
              title="Github"
              ariaLabel="Github"
            >
              <Icon iconName="github" />
              <span className="srOnly">Github</span>
            </Link>
          </div>
          <div className={styles.subContent}>
            <LangSwitch />
            <ThemeCta />
          </div>
        </div>
      </div>
    </footer>
  );
}

export default Footer;
