<!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
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
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-hidden">
<!-- Ambient Orbs -->
<div class="ambient-orb w-[60vw] h-[60vw] top-[-20%] left-[-10%]"></div>
<div class="ambient-orb w-[50vw] h-[50vw] bottom-[-10%] right-[-20%]"></div>
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-[25px] border-b border-slate-200/50 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-slate-900 hover:opacity-80 transition-all duration-300" href="/">BitBuddy</a>
<!-- Hide nav links on intent: 404 dead end -> keep simple header or suppress entirely. -->
<nav class="hidden md:flex gap-8 items-center">
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="#">Услуги</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="#">О нас</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="#">Контакты</a>
</nav>
<div class="flex items-center gap-4">
<button class="hidden md:block px-6 py-2 bg-primary/10 text-primary border border-primary/20 rounded-full font-semibold hover:bg-primary/20 transition-all duration-300 active:scale-95 transform">Войти</button>
<button class="text-slate-500 hover:text-slate-900 transition-colors duration-300 active:scale-95 transform flex items-center justify-center w-10 h-10 rounded-full hover:bg-slate-100">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">light_mode</span>
</button>
</div>
</div>
</header>
<!-- Main Canvas -->
<main class="flex-grow flex items-center justify-center relative z-10 px-4 pt-24 pb-20">
<!-- Center Glass Card -->
<div class="relative max-w-2xl w-full">
<!-- Lifted surface -->
<div class="bg-white/70 backdrop-blur-[25px] border border-slate-200/50 rounded-xl p-12 md:p-16 text-center shadow-[0_20px_60px_rgba(14,165,233,0.06)] transition-all duration-500 hover:shadow-[0_30px_80px_rgba(14,165,233,0.1)] hover:scale-[1.01]">
<!-- Inner soft orb for text glow -->
<div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-10">
<div class="w-64 h-64 bg-primary rounded-full blur-[100px]"></div>
</div>
<div class="relative z-10 flex flex-col items-center">
<!-- 404 Display -->
<h1 class="text-[120px] md:text-[180px] font-black leading-none tracking-tighter bg-gradient-to-br from-primary to-primary-container bg-clip-text text-transparent drop-shadow-[0_0_30px_rgba(14,165,233,0.2)] mb-4">
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
<a class="group relative inline-flex items-center justify-center px-8 py-4 bg-primary text-white font-bold text-lg rounded-full overflow-hidden transition-all duration-300 glow-hover active:scale-95" href="/">
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
<footer class="w-full relative bottom-0 bg-white border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] font-inter text-sm tracking-wide z-10">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="text-xl font-black text-slate-900 tracking-tighter">
                BitBuddy
            </div>
<nav class="flex flex-wrap justify-center gap-6">
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded px-1" href="#">Услуги</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded px-1" href="#">О нас</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded px-1" href="#">Контакты</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded px-1" href="#">Политика конфиденциальности</a>
</nav>
<div class="text-slate-400 text-xs">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
</body></html>
