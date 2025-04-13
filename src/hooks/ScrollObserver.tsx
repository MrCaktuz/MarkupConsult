"use client";

import { useEffect } from "react";

export const ScrollObserver = () => {
  useEffect(() => {
    const observeScroll = (elms: Element[]) => {
      const intObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("onScreen");
          } else {
            entry.target.classList.remove("onScreen");
          }
        });
      });

      elms.forEach((elm) => {
        if (!elm.classList.contains("scrollObserved--bound")) {
          elm.classList.add("scrollObserved--bound");
          intObserver.observe(elm);
        }
      });
    };

    const runObserver = () => {
      const observedElements = document.querySelectorAll(
        '*[class*="scrollObserved"]',
      );
      observeScroll(Array.from(observedElements));
    };

    runObserver(); // Initial run

    const mutationObserver = new MutationObserver(() => {
      runObserver(); // Rerun when DOM changes
    });

    mutationObserver.observe(document.body, {
      childList: true,
      subtree: true,
    });

    return () => mutationObserver.disconnect();
  }, []);

  return null;
};
