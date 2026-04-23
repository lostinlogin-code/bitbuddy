<?php
/**
 * Minimal header for auth / 404 pages — only brand + theme toggle.
 */
?>
<header class="fixed top-0 w-full z-50 flex justify-between items-center px-6 md:px-8 h-20">
    <a class="text-2xl font-extrabold tracking-tighter text-on-surface hover:text-primary transition-colors duration-300" href="index.php">BitBuddy</a>
    <button type="button" onclick="ThemeManager.toggle()" aria-label="Переключить тему"
            class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-on-surface/5 transition-all duration-300 active:scale-90">
        <span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 1;">dark_mode</span>
    </button>
</header>
