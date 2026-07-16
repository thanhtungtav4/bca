'use strict';

window.underscoresChild = window.underscoresChild || {};

/* Mobile nav drawer toggle. CSS owns the animation (.is-open on nav,
   .bca-menu-open on body for scroll lock). JS only flips state + a11y. */
(function () {
    var toggle = document.querySelector('.bca-navbar-toggle');
    var nav = document.getElementById('bca-primary-nav');
    if (!toggle || !nav) { return; }

    function setOpen(open) {
        nav.classList.toggle('is-open', open);
        document.body.classList.toggle('bca-menu-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    }

    toggle.addEventListener('click', function () {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    // close on link tap, Escape, or resize back to desktop
    nav.addEventListener('click', function (e) {
        if (e.target.closest('a')) { setOpen(false); }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { setOpen(false); }
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) { setOpen(false); }
    });
})();
