"use client";
import React from "react";
import styles from "./theme-toggle-btn.module.scss";
import { THEMES_AVAILABLE } from "@/utils/const";

export default function ThemeToggleBtn() {
  const getCurrentTheme = () => {
    const $html = document.documentElement;
    const dataTheme = $html.getAttribute("data-theme");
    const systemPrefersDark = window.matchMedia(
      "(prefers-color-scheme: dark)",
    ).matches;

    if (!dataTheme || dataTheme === THEMES_AVAILABLE.DEFAULT) {
      return systemPrefersDark ? THEMES_AVAILABLE.DARK : THEMES_AVAILABLE.LIGHT;
    }
    return dataTheme;
  };

  const onToggle = async () => {
    const $html = document.documentElement;
    const currentTheme = getCurrentTheme();
    const newTheme =
      currentTheme === THEMES_AVAILABLE.DARK
        ? THEMES_AVAILABLE.LIGHT
        : THEMES_AVAILABLE.DARK;

    document.cookie = `theme=${newTheme}`;
    $html.setAttribute("data-theme", newTheme);
  };

  return (
    <button
      id="themeToggleBtn"
      className={styles.themeToggleBtn}
      onClick={onToggle}
    >
      <span className="srOnly">Switch theme</span>
      <svg
        className={styles.icon}
        width="150"
        height="150"
        viewBox="0 0 150 150"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          className={styles.icon__rays}
          d="M104.029 26.0866L93.5866 8L75.5 18.4423L57.4134 8L46.9711 26.0866H26.0866V46.9711L8 57.4134L18.4423 75.5L8 93.5866L26.0866 104.029V124.913H46.9711L57.4134 143L75.5 132.558L93.5866 143L104.029 124.913H124.913V104.029L143 93.5866L132.558 75.5L143 57.4134L124.913 46.9711V26.0866H104.029Z"
          fill="none"
        />
        <path
          className={styles.icon__darkBg}
          d="M5 75C5 36.3401 36.3401 5 75 5C113.66 5 145 36.3401 145 75C145 113.66 113.66 145 75 145C36.3401 145 5 113.66 5 75Z"
          fill="none"
        />
        <rect
          className={styles.icon__moon}
          x="30"
          y="30"
          width="90"
          height="90"
          rx="45"
          fill="none"
        />
        <path
          className={styles.icon__shadow}
          d="M20 75C20 55.67 35.67 40 55 40C74.33 40 90 55.67 90 75C90 94.33 74.33 110 55 110C35.67 110 20 94.33 20 75Z"
          fill="none"
        />
      </svg>
    </button>
  );
}
