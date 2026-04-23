/**
 * BitBuddy — Theme Manager
 * Handles theme switching via [data-theme] with localStorage persistence
 * and a View-Transitions API fade when supported.
 */
(function () {
    'use strict';

    var THEME_KEY = 'bitbuddy-theme';
    var ATTR = 'data-theme';
    var DARK = 'dark';
    var LIGHT = 'light';

    function getSaved() {
        try { return localStorage.getItem(THEME_KEY); } catch (e) { return null; }
    }

    function saveTheme(t) {
        try { localStorage.setItem(THEME_KEY, t); } catch (e) { /* ignore */ }
    }

    function updateIcons(theme) {
        var icons = document.querySelectorAll('[data-theme-icon]');
        icons.forEach(function (icon) {
            icon.textContent = theme === DARK ? 'dark_mode' : 'light_mode';
        });
    }

    function apply(theme) {
        document.documentElement.setAttribute(ATTR, theme);
        document.documentElement.style.colorScheme = theme;
        if (document.body) document.body.setAttribute(ATTR, theme);
        updateIcons(theme);
    }

    function current() {
        return document.documentElement.getAttribute(ATTR) || DARK;
    }

    function toggle() {
        var next = current() === DARK ? LIGHT : DARK;
        set(next);
    }

    function set(theme) {
        if (theme !== DARK && theme !== LIGHT) return;
        saveTheme(theme);

        // Use View Transitions API when available for a smooth cross-fade.
        if (document.startViewTransition) {
            document.startViewTransition(function () { apply(theme); });
        } else {
            apply(theme);
        }

        window.dispatchEvent(new CustomEvent('bitbuddy:themechange', { detail: { theme: theme } }));
    }

    // Initial application (redundant with inline head script but keeps icons in sync)
    function init() {
        var saved = getSaved();
        var prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
        var theme = saved || (prefersLight ? LIGHT : DARK);
        apply(theme);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Follow system-level changes when user hasn't explicitly chosen a theme
    if (window.matchMedia) {
        var mq = window.matchMedia('(prefers-color-scheme: light)');
        var listener = function (e) {
            if (!getSaved()) apply(e.matches ? LIGHT : DARK);
        };
        if (mq.addEventListener) mq.addEventListener('change', listener);
        else if (mq.addListener) mq.addListener(listener);
    }

    window.ThemeManager = { toggle: toggle, set: set, get: current, DARK: DARK, LIGHT: LIGHT };
})();


/**
 * BitBuddy — Mobile menu
 */
(function () {
    'use strict';

    function toggleMenu() {
        var menu = document.getElementById('mobile-menu');
        var overlay = document.getElementById('mobile-overlay');
        if (!menu) return;
        var isOpen = !menu.classList.contains('translate-x-full');
        if (isOpen) close();
        else {
            menu.classList.remove('translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function close() {
        var menu = document.getElementById('mobile-menu');
        var overlay = document.getElementById('mobile-overlay');
        if (menu) menu.classList.add('translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) close();
    });

    window.MobileMenu = { toggle: toggleMenu, close: close };
})();


/**
 * BitBuddy — Scroll behaviour (nav state + reveal animations + counters)
 */
(function () {
    'use strict';

    function bindNavScroll() {
        var nav = document.getElementById('top-nav');
        if (!nav) return;
        var last = -1;
        var update = function () {
            var s = window.scrollY > 8;
            if (s !== last) {
                nav.classList.toggle('is-scrolled', s);
                last = s;
            }
        };
        update();
        window.addEventListener('scroll', update, { passive: true });
    }

    function animateCounter(el) {
        var target = parseFloat(el.dataset.target);
        if (!isFinite(target)) return;
        var suffix = el.dataset.suffix || '';
        var decimal = parseInt(el.dataset.decimal, 10) || 0;
        var prefix = el.dataset.prefix || '';
        var duration = 1500;
        var start = performance.now();

        function frame(now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var value = target * eased;
            if (decimal > 0) {
                el.textContent = prefix + value.toFixed(decimal) + suffix;
            } else {
                el.textContent = prefix + Math.floor(value) + (target >= 100 ? '+' : suffix);
            }
            if (progress < 1) requestAnimationFrame(frame);
            else {
                el.textContent = decimal > 0
                    ? prefix + target.toFixed(decimal) + suffix
                    : prefix + target + (target >= 100 ? '+' : suffix);
            }
        }
        requestAnimationFrame(frame);
    }

    function bindScrollAnimations() {
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.card-animate').forEach(function (el) { el.classList.add('visible'); });
            document.querySelectorAll('.stat-value').forEach(animateCounter);
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                if (entry.target.classList.contains('stat-value')) animateCounter(entry.target);
                if (entry.target.classList.contains('card-animate')) entry.target.classList.add('visible');
                io.unobserve(entry.target);
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.stat-value, .card-animate').forEach(function (el) { io.observe(el); });
    }

    function init() {
        bindNavScroll();
        bindScrollAnimations();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
