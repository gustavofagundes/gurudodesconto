/**
 * Landing page — navegação, âncoras e CTA fixo mobile.
 */
(function () {
  'use strict';

  if (!document.body.classList.contains('guru-front-landing')) {
    return;
  }

  var header = document.querySelector('.site-header');
  var sectionNav = document.querySelector('.landing-section-nav');
  var stickyCta = document.querySelector('[data-landing-sticky]');
  var hero = document.querySelector('.hero');
  var navLinks = document.querySelectorAll('[data-landing-section]');
  var sections = [];

  function getScrollOffset() {
    var h = header ? header.offsetHeight : 76;
    var n = sectionNav ? sectionNav.offsetHeight : 0;
    return h + n + 12;
  }

  function scrollToSection(id) {
    var target = document.getElementById(id);
    if (!target) {
      return;
    }
    var top = target.getBoundingClientRect().top + window.pageYOffset - getScrollOffset();
    window.scrollTo({ top: top, behavior: 'smooth' });
  }

  document.addEventListener('click', function (event) {
    var link = event.target.closest('a[href^="#"]');
    if (!link) {
      return;
    }
    var hash = link.getAttribute('href');
    if (!hash || hash === '#') {
      return;
    }
    var id = hash.slice(1);
    var target = document.getElementById(id);
    if (!target) {
      return;
    }
    event.preventDefault();
    scrollToSection(id);
    history.replaceState(null, '', '#' + id);

    var nav = document.querySelector('.main-nav');
    var toggle = document.querySelector('.menu-toggle');
    if (nav && nav.classList.contains('open')) {
      nav.classList.remove('open');
      if (toggle) {
        toggle.setAttribute('aria-expanded', 'false');
      }
    }
  });

  navLinks.forEach(function (link) {
    var id = link.getAttribute('data-landing-section');
    var el = document.getElementById(id);
    if (el) {
      sections.push({ id: id, el: el, link: link });
    }
  });

  function updateActiveSection() {
    var offset = getScrollOffset() + 40;
    var current = sections[0];

    sections.forEach(function (section) {
      if (section.el.getBoundingClientRect().top - offset <= 0) {
        current = section;
      }
    });

    navLinks.forEach(function (link) {
      link.classList.remove('is-active');
    });

    if (current && current.link) {
      current.link.classList.add('is-active');
    }
  }

  function updateStickyCta() {
    if (!stickyCta || !hero) {
      return;
    }
    var isMobile = window.matchMedia('(max-width: 900px)').matches;
    var show = isMobile && hero.getBoundingClientRect().bottom < getScrollOffset();
    stickyCta.hidden = !show;
    document.body.classList.toggle('landing-sticky-visible', show);
  }

  function onScroll() {
    updateActiveSection();
    updateStickyCta();
  }

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onScroll, { passive: true });
  onScroll();

  if (window.location.hash) {
    var initialId = window.location.hash.slice(1);
    window.setTimeout(function () {
      scrollToSection(initialId);
    }, 100);
  }
})();
