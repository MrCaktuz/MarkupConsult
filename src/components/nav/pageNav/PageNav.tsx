import React from 'react';
import { slugify } from '@/utils/function';
import styles from './pageNav.module.scss';

const PageNav = ({ sections }: { sections: string[] }) => {
  return (
    <nav className={styles.pageNav} aria-labelledby="mainmenulabel">
      <h2 id="mainmenulabel" className="srOnly">
        Navigation
      </h2>
      <ul className={styles.list}>
        {sections &&
          sections.map(
            (sectionTitle) =>
              sectionTitle && (
                <li
                  key={sectionTitle}
                  className={`${styles.item} scrollObservedLeft`}
                >
                  <a
                    href={`#section_${slugify(sectionTitle)}`}
                    className={styles.link}
                  >
                    {sectionTitle}
                  </a>
                </li>
              )
          )}
      </ul>
    </nav>
  );
};

export default PageNav;
