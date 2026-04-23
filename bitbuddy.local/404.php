<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';
?><!DOCTYPE html>

<html lang="ru" data-theme="dark"><head>
    <!-- Theme initialization - prevents flash of unstyled content -->
    <script>
        (function() {
            const saved = localStorage.getItem('bitbuddy-theme');
            const prefersLight = window.matchMedia('(prefers-color-scheme: light)').matches;
            const theme = saved || (prefersLight ? 'light' : 'dark');
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        })();
    </script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - 404</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="theme.css?v=3"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "var(--color-tertiary-fixed-dim)",
                        "on-secondary-fixed": "var(--color-on-secondary-fixed)",
                        "surface-container-high": "rgb(var(--rgb-surface-container-high) / <alpha-value>)",
                        "on-tertiary": "var(--color-on-tertiary)",
                        "surface-container-low": "var(--color-surface-container-low)",
                        "primary-container": "var(--color-primary-container)",
                        "on-secondary-container": "var(--color-on-secondary-container)",
                        "on-primary-fixed": "var(--color-on-primary-fixed)",
                        "tertiary-container": "var(--color-tertiary-container)",
                        "tertiary-fixed": "var(--color-tertiary-fixed)",
                        "inverse-primary": "var(--color-inverse-primary)",
                        "on-primary-fixed-variant": "var(--color-on-primary-fixed-variant)",
                        "on-tertiary-fixed": "var(--color-on-tertiary-fixed)",
                        "error-container": "var(--color-error-container)",
                        "error-dim": "var(--color-error-dim)",
                        "background": "var(--color-background)",
                        "on-surface-variant": "var(--color-on-surface-variant)",
                        "inverse-surface": "var(--color-inverse-surface)",
                        "error": "var(--color-error)",
                        "surface-container-highest": "var(--color-surface-container-highest)",
                        "on-secondary": "var(--color-on-secondary)",
                        "surface-variant": "rgb(var(--rgb-surface-variant) / <alpha-value>)",
                        "surface-container-lowest": "var(--color-surface-container-lowest)",
                        "on-error": "var(--color-on-error)",
                        "secondary-container": "var(--color-secondary-container)",
                        "tertiary-dim": "rgb(var(--rgb-tertiary-dim) / <alpha-value>)",
                        "on-background": "var(--color-on-background)",
                        "outline": "var(--color-outline)",
                        "on-tertiary-container": "var(--color-on-tertiary-container)",
                        "primary": "rgb(var(--rgb-primary) / <alpha-value>)",
                        "primary-dim": "var(--color-primary-dim)",
                        "on-surface": "var(--color-on-surface)",
                        "primary-fixed": "var(--color-primary-fixed)",
                        "on-primary-container": "var(--color-on-primary-container)",
                        "secondary-dim": "rgb(var(--rgb-secondary-dim) / <alpha-value>)",
                        "secondary-fixed": "var(--color-secondary-fixed)",
                        "inverse-on-surface": "var(--color-inverse-on-surface)",
                        "surface-tint": "var(--color-surface-tint)",
                        "on-error-container": "var(--color-on-error-container)",
                        "primary-fixed-dim": "var(--color-primary-fixed-dim)",
                        "surface-container": "var(--color-surface-container)",
                        "surface-bright": "var(--color-surface-bright)",
                        "secondary": "var(--color-secondary)",
                        "on-secondary-fixed-variant": "var(--color-on-secondary-fixed-variant)",
                        "on-tertiary-fixed-variant": "var(--color-on-tertiary-fixed-variant)",
                        "outline-variant": "rgb(var(--rgb-outline-variant) / <alpha-value>)",
                        "secondary-fixed-dim": "var(--color-secondary-fixed-dim)",
                        "tertiary": "var(--color-tertiary)",
                        "surface": "var(--color-surface)",
                        "on-primary": "var(--color-on-primary)",
                        "surface-dim": "var(--color-surface-dim)"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
<script src="theme.js"></script>
<style>
        .glowing-orb-1 {
            background: radial-gradient(circle, rgba(105,218,255,0.15) 0%, rgba(14,14,14,0) 70%);
        }
        [data-theme="light"] .glowing-orb-1 {
            background: radial-gradient(circle, rgba(14,165,233,0.1) 0%, rgba(248,250,252,0) 70%);
        }
        .glowing-orb-2 {
            background: radial-gradient(circle, rgba(0,178,236,0.1) 0%, rgba(14,14,14,0) 70%);
        }
        [data-theme="light"] .glowing-orb-2 {
            background: radial-gradient(circle, rgba(2,132,199,0.08) 0%, rgba(248,250,252,0) 70%);
        }
        .btn-neon-hover:hover {
            box-shadow: 0 0 20px rgba(105,218,255,0.6);
        }
        [data-theme="light"] .btn-neon-hover:hover {
            box-shadow: 0 0 20px rgba(14,165,233,0.4);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-hidden">
<!-- Ambient Orbs -->
<div class="absolute top-[-20%] left-[-10%] w-[60vw] h-[60vw] glowing-orb-1 rounded-full pointer-events-none"></div>
<div class="absolute bottom-[-10%] right-[-20%] w-[50vw] h-[50vw] glowing-orb-2 rounded-full pointer-events-none"></div>
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all duration-300" href="index.php">BitBuddy</a>
<!-- Hide nav links on intent: 404 dead end -> keep simple header or suppress entirely? The prompt says "Glassmorphic nav/footer", so we render the shell but maybe suppress links. Let's render the JSON links as inactive to maintain the shell structure, but 404 is a dead end. Conflict: "suppress the navigation to prioritize the content canvas" vs "Glassmorphic nav/footer" in user prompt. User prompt overrides for "render it", but we will leave all inactive. -->
<nav class="hidden md:flex gap-8 items-center">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</nav>
<div class="flex items-center gap-4">
<?php if ($is_logged_in): ?>
<a href="profile.php" class="hidden md:block px-6 py-2 bg-primary/10 text-primary border border-primary/20 rounded-full font-semibold hover:bg-primary/20 transition-all duration-300"><?php echo htmlspecialchars($username); ?></a>
<?php else: ?>
<a href="login.php" class="hidden md:block px-6 py-2 bg-primary/10 text-primary border border-primary/20 rounded-full font-semibold hover:bg-primary/20 transition-all duration-300">Войти</a>
<?php endif; ?>
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 flex items-center justify-center w-10 h-10 rounded-full hover:bg-on-surface/5" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 0;">dark_mode</span>
</button>
<button onclick="MobileMenu.toggle()" class="md:hidden text-on-surface-variant hover:text-on-surface transition-colors duration-300">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</div>
</div>
</header>
<!-- Mobile Menu Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[60] md:hidden" onclick="MobileMenu.close()"></div>
<!-- Mobile Menu Drawer -->
<div id="mobile-menu" class="fixed top-0 right-0 h-full w-72 bg-surface-container backdrop-blur-[25px] border-l border-outline-variant/15 z-[70] transform translate-x-full transition-transform duration-300 md:hidden flex flex-col p-8 gap-6">
<div class="flex justify-between items-center mb-4">
<span class="text-xl font-black text-on-surface tracking-tighter">BitBuddy</span>
<button onclick="MobileMenu.close()" class="text-on-surface-variant hover:text-on-surface">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="index.php" onclick="MobileMenu.close()">О нас</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="services.php" onclick="MobileMenu.close()">Услуги</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="contacts.php" onclick="MobileMenu.close()">Контакты</a>
<div class="mt-auto pt-6 border-t border-outline-variant/15">
<?php if ($is_logged_in): ?>
<a href="profile.php" class="text-primary font-semibold block py-2" onclick="MobileMenu.close()"><?php echo htmlspecialchars($username); ?></a>
<a href="logout.php" class="text-error-dim block py-2">Выйти</a>
<?php else: ?>
<a href="login.php" class="text-primary font-semibold block py-2" onclick="MobileMenu.close()">Войти</a>
<?php endif; ?>
</div>
</div>
<!-- Main Canvas -->
<main class="flex-grow flex items-center justify-center relative z-10 px-4 pt-24 pb-20">
<!-- Center Glass Card -->
<div class="relative max-w-2xl w-full">
<!-- Lifted surface -->
<div class="bg-surface-variant/40 backdrop-blur-[25px] border border-outline-variant/15 rounded-xl p-12 md:p-16 text-center shadow-[0_0_80px_rgba(105,218,255,0.08)] transition-all duration-500 hover:shadow-[0_0_100px_rgba(105,218,255,0.12)] hover:scale-[1.01]">
<!-- Inner soft orb for text glow -->
<div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
<div class="w-64 h-64 bg-primary rounded-full blur-[100px]"></div>
</div>
<div class="relative z-10 flex flex-col items-center">
<!-- 404 Display -->
<h1 class="text-[120px] md:text-[180px] font-black leading-none tracking-tighter bg-gradient-to-br from-primary to-primary-container bg-clip-text text-transparent drop-shadow-[0_0_30px_rgba(105,218,255,0.4)] mb-4">
                        404
                    </h1>
<!-- Subtitle -->
<h2 class="text-3xl md:text-4xl font-bold text-on-surface mb-6 tracking-tight">
                        Страница не найдена
                    </h2>
<!-- Short text -->
<p class="text-on-surface-variant text-lg max-w-md mx-auto mb-10 leading-relaxed">
                        Похоже, вы забрели в неизведанную часть эфира. Запрашиваемая страница была перемещена или больше не существует.
                    </p>
<!-- Neon Button -->
<a class="group relative inline-flex items-center justify-center px-8 py-4 bg-primary text-on-primary font-bold text-lg rounded-full overflow-hidden transition-all duration-300 btn-neon-hover active:scale-95" href="index.php">
<span class="relative z-10 flex items-center gap-2">
<span class="material-symbols-outlined">arrow_back</span>
                            Вернуться на главную
                        </span>
<!-- Subtle internal gradient overlay -->
<div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></div>
</a>
</div>
</div>
</div>
</main>
<!-- Footer -->
<footer class="w-full relative bottom-0 bg-[#0e0e0e] border-t border-[#69daff]/30 shadow-[0_-10px_40px_rgba(105,218,255,0.05)] font-inter text-sm tracking-wide z-20">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="flex flex-col items-center md:items-start gap-4">
<span class="text-xl font-black text-white tracking-tighter">BitBuddy</span>
<span class="text-neutral-500">© 2024 BitBuddy. The Ethereal Exchange.</span>
</div>
<nav class="flex flex-wrap justify-center md:justify-end gap-x-8 gap-y-4">
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="services.php">Услуги</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="index.php">О нас</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="contacts.php">Контакты</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
</body></html>