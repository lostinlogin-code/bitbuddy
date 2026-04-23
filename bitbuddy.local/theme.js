/**
 * BitBuddy Theme Manager
 * Handles theme switching via data-theme attribute with localStorage persistence
 */

(function() {
    'use strict';

    const THEME_KEY = 'bitbuddy-theme';
    const THEME_ATTRIBUTE = 'data-theme';
    const DARK_THEME = 'dark';
    const LIGHT_THEME = 'light';

    /**
     * Get initial theme based on localStorage or system preference
     */
    function getInitialTheme() {
        // Check localStorage first
        const savedTheme = localStorage.getItem(THEME_KEY);
        if (savedTheme && (savedTheme === DARK_THEME || savedTheme === LIGHT_THEME)) {
            return savedTheme;
        }

        // Check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
            return LIGHT_THEME;
        }

        // Default to dark
        return DARK_THEME;
    }

    /**
     * Apply theme to document
     */
    function applyTheme(theme) {
        document.documentElement.setAttribute(THEME_ATTRIBUTE, theme);
        document.documentElement.style.colorScheme = theme;
        document.body?.setAttribute(THEME_ATTRIBUTE, theme);
        
        // Update icon if exists
        updateThemeIcon(theme);
    }

    /**
     * Update theme toggle icon based on current theme
     */
    function updateThemeIcon(theme) {
        const icons = document.querySelectorAll('[data-theme-icon]');
        icons.forEach(icon => {
            icon.textContent = theme === DARK_THEME ? 'dark_mode' : 'light_mode';
            icon.setAttribute('data-icon', theme === DARK_THEME ? 'dark_mode' : 'light_mode');
        });
    }

    /**
     * Toggle between dark and light themes
     */
    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute(THEME_ATTRIBUTE) || DARK_THEME;
        const newTheme = currentTheme === DARK_THEME ? LIGHT_THEME : DARK_THEME;
        
        applyTheme(newTheme);
        localStorage.setItem(THEME_KEY, newTheme);
        
        // Dispatch custom event for other components
        window.dispatchEvent(new CustomEvent('themechange', { 
            detail: { theme: newTheme, previousTheme: currentTheme } 
        }));
    }

    /**
     * Set specific theme
     */
    function setTheme(theme) {
        if (theme !== DARK_THEME && theme !== LIGHT_THEME) {
            console.warn('Invalid theme:', theme);
            return;
        }
        applyTheme(theme);
        localStorage.setItem(THEME_KEY, theme);
    }

    /**
     * Get current theme
     */
    function getCurrentTheme() {
        return document.documentElement.getAttribute(THEME_ATTRIBUTE) || DARK_THEME;
    }

    /**
     * Initialize theme on page load
     */
    function initTheme() {
        const theme = getInitialTheme();
        applyTheme(theme);
    }

    // Initialize immediately to prevent flash
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }

    // Also run on load to be safe
    window.addEventListener('load', initTheme);

    // Listen for system theme changes
    if (window.matchMedia) {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: light)');
        mediaQuery.addEventListener('change', (e) => {
            // Only apply if user hasn't manually set a preference
            if (!localStorage.getItem(THEME_KEY)) {
                const newTheme = e.matches ? LIGHT_THEME : DARK_THEME;
                applyTheme(newTheme);
            }
        });
    }

    // Expose API globally
    window.ThemeManager = {
        toggle: toggleTheme,
        set: setTheme,
        get: getCurrentTheme,
        DARK: DARK_THEME,
        LIGHT: LIGHT_THEME
    };

})();

/**
 * BitBuddy Mobile Menu Manager
 */
(function() {
    'use strict';

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        if (!menu) return;

        const isOpen = !menu.classList.contains('translate-x-full');
        if (isOpen) {
            menu.classList.add('translate-x-full');
            overlay?.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            menu.classList.remove('translate-x-full');
            overlay?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        if (menu) menu.classList.add('translate-x-full');
        overlay?.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close on resize to desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) closeMobileMenu();
    });

    window.MobileMenu = {
        toggle: toggleMobileMenu,
        close: closeMobileMenu
    };
})();

/**
 * BitBuddy Scroll Animations & Counter
 */
(function() {
    'use strict';

    // Counter animation for stat values
    function animateCounter(el) {
        const target = parseFloat(el.dataset.target);
        const suffix = el.dataset.suffix || '';
        const decimal = parseInt(el.dataset.decimal) || 0;
        const prefix = el.dataset.prefix || '';
        const duration = 1500;
        const start = performance.now();

        function update(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = target * eased;

            if (decimal > 0) {
                el.textContent = prefix + current.toFixed(decimal) + suffix;
            } else {
                el.textContent = prefix + Math.floor(current) + (target >= 100 ? '+' : suffix);
            }

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                if (decimal > 0) {
                    el.textContent = prefix + target.toFixed(decimal) + suffix;
                } else {
                    el.textContent = prefix + target + (target >= 100 ? '+' : suffix);
                }
            }
        }

        requestAnimationFrame(update);
    }

    // Intersection Observer for scroll animations
    function initScrollAnimations() {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    // Counter animation
                    if (entry.target.classList.contains('stat-value')) {
                        animateCounter(entry.target);
                    }
                    // Card entrance
                    if (entry.target.classList.contains('card-animate')) {
                        entry.target.classList.add('visible');
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        document.querySelectorAll('.stat-value, .card-animate').forEach(function(el) {
            observer.observe(el);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollAnimations);
    } else {
        initScrollAnimations();
    }
})();
