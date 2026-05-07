(function () {
  'use strict';

  /* ── Mouse tracker ─────────────────────────────────────────── */
  document.addEventListener('mousemove', function (e) {
    document.body.style.setProperty('--pointer-x', (e.clientX / window.innerWidth  * 100) + '%');
    document.body.style.setProperty('--pointer-y', (e.clientY / window.innerHeight * 100) + '%');
  });

  /* ── Mobile nav ────────────────────────────────────────────── */
  var toggle = document.getElementById('nav-toggle');
  var nav    = document.getElementById('site-nav');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      document.body.style.overflow = open ? 'hidden' : '';
    });
    nav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      });
    });
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
      }
    });
  }

  /* ── Lang switcher ─────────────────────────────────────────── */
  document.addEventListener('click', function (e) {
    var btn       = e.target.closest('.lang-current');
    var switcher  = e.target.closest('.lang-switcher');

    // Ouvrir/fermer le dropdown
    if (btn) {
      var sw = btn.closest('.lang-switcher');
      var open = sw.classList.toggle('is-open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      e.stopPropagation();
      return;
    }

    // Fermer si clic à l'extérieur
    document.querySelectorAll('.lang-switcher.is-open').forEach(function (sw) {
      if (!sw.contains(e.target)) {
        sw.classList.remove('is-open');
        var b = sw.querySelector('.lang-current');
        if (b) b.setAttribute('aria-expanded', 'false');
      }
    });
  });

  /* ── Scroll reveal ─────────────────────────────────────────── */
  function initReveal() {
    var items = document.querySelectorAll('.js-reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var delay = parseInt(entry.target.style.getPropertyValue('--delay')) || 0;
          setTimeout(function () { entry.target.classList.add('is-visible'); }, delay);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -32px 0px' });

    items.forEach(function (el) { observer.observe(el); });
  }

  /* ── Parcours nav active on scroll ────────────────────────── */
  function initParcoursNav() {
    var links    = document.querySelectorAll('.page-nav-link');
    var sections = Array.from(links).map(function (l) {
      return document.getElementById(l.getAttribute('href').replace('#', ''));
    }).filter(Boolean);

    if (!links.length || !sections.length) return;

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          links.forEach(function (l) { l.classList.remove('is-active'); });
          var a = document.querySelector('.page-nav-link[href="#' + entry.target.id + '"]');
          if (a) a.classList.add('is-active');
        }
      });
    }, { rootMargin: '-20% 0px -60% 0px' });

    sections.forEach(function (s) { observer.observe(s); });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initReveal();
    initParcoursNav();
  });

})();
