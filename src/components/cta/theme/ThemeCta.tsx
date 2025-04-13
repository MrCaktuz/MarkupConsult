"use client";
import React from "react";
import styles from "./themeCta.module.scss";
import { THEMES_AVAILABLE } from "@/utils/const";

type ThemeCtaProps = {
  helper?: string;
};

export default function ThemeCta({ helper }: ThemeCtaProps) {
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
      className={styles.themeCta}
      onClick={onToggle}
      title={helper}
      aria-label={helper}
    >
      <span className="srOnly">{helper}</span>
      <svg
        className={`${styles.icon} icon--5`}
        width="140"
        height="140"
        viewBox="0 0 140 140"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          className={styles.icon__rays}
          d="M98.8675 20L88.3013 1.69873L70 12.265L51.6987 1.69873L41.1325 20H20V41.1325L1.69873 51.6987L12.265 70L1.69873 88.3013L20 98.8675V120H41.1325L51.6987 138.301L70 127.735L88.3013 138.301L98.8675 120H120V98.8675L138.301 88.3013L127.735 70L138.301 51.6987L120 41.1325V20H98.8675Z"
          fill="#E0E6EB"
        />
        <path
          className={styles.icon__moon}
          d="M0 70C0 31.3401 31.3401 0 70 0C108.66 0 140 31.3401 140 70C140 108.66 108.66 140 70 140C31.3401 140 0 108.66 0 70Z"
          fill="#E0E6EB"
        />
        <rect
          className={styles.icon__shadow}
          x="25"
          y="25"
          width="90"
          height="90"
          rx="45"
          fill="#29363D"
        />
        <path
          className={styles.icon__center}
          d="M35 70C35 50.67 50.67 35 70 35C89.33 35 105 50.67 105 70C105 89.33 89.33 105 70 105C50.67 105 35 89.33 35 70Z"
          fill="#E0E6EB"
        />
      </svg>

      {/* <svg
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
          className={styles.icon__moon}
          d="M5 75C5 36.3401 36.3401 5 75 5C113.66 5 145 36.3401 145 75C145 113.66 113.66 145 75 145C36.3401 145 5 113.66 5 75Z"
          fill="none"
        />
        <rect
          className={styles.icon__shadow}
          x="30"
          y="30"
          width="90"
          height="90"
          rx="45"
          fill="none"
        />
        <path
          className={styles.icon__center}
          d="M20 75C20 55.67 35.67 40 55 40C74.33 40 90 55.67 90 75C90 94.33 74.33 110 55 110C35.67 110 20 94.33 20 75Z"
          fill="none"
        />
      </svg> */}
    </button>
  );
}
