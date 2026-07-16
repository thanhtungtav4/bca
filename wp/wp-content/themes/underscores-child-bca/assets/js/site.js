/**
 * BCA Partners — site interaction JS.
 * Mobile menu toggle: opens/closes the nav drawer and toggles aria-expanded.
 */
(function () {
  'use strict';

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    var toggle = document.querySelector('.bca-navbar-toggle, .hdr-burger');
    var nav = document.getElementById('bca-primary-nav');
    if (!toggle || !nav) return;

    function setOpen(open) {
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      nav.classList.toggle('is-open', open);
      document.body.classList.toggle('bca-menu-open', open);
    }

    toggle.addEventListener('click', function () {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // Close menu when a nav link is clicked
    nav.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (link) setOpen(false);
    });

    // Close menu on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
        toggle.focus();
      }
    });

    // Close menu on resize to desktop
    var mq = window.matchMedia('(min-width: 901px)');
    function handleMq(e) {
      if (e.matches && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false);
      }
    }
    if (mq.addEventListener) mq.addEventListener('change', handleMq);
    else if (mq.addListener) mq.addListener(handleMq);
  });
})();
