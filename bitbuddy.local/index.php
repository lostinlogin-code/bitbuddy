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
<title>BitBuddy - The Ethereal Exchange</title>
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
                        "headline": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
<script src="theme.js"></script>
<script src="smoke-bg.js"></script>
<style>
        /* Component styles moved to theme.css */
    </style>
</head>
<body class="bg-background text-on-background font-body overflow-x-hidden relative min-h-screen flex flex-col">
<!-- Ambient Orbs -->
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
<div class="ambient-orb w-[800px] h-[800px] top-[-200px] left-[-200px]"></div>
<div class="ambient-orb w-[600px] h-[600px] top-[40%] right-[-100px] opacity-60"></div>
<div class="ambient-orb w-[1000px] h-[1000px] bottom-[-300px] left-[20%] opacity-40"></div>
</div>
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<div class="hidden md:flex items-center space-x-8">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="services.php">Услуги</a>
<a class="text-[#69daff] font-semibold border-b-2 border-[#69daff] pb-1" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</div>
<div class="flex items-center space-x-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 focus:outline-none" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 1;">dark_mode</span>
</button>
<?php if ($is_logged_in): ?>
<a href="profile.php" class="hidden md:inline text-primary hover:opacity-80 transition-all duration-300 active:scale-95 transform font-semibold focus:outline-none"><?php echo htmlspecialchars($username); ?></a>
<?php else: ?>
<a href="login.php" class="hidden md:inline text-primary hover:opacity-80 transition-all duration-300 active:scale-95 transform font-semibold focus:outline-none">Войти</a>
<?php endif; ?>
<button onclick="MobileMenu.toggle()" class="md:hidden text-on-surface-variant hover:text-on-surface transition-colors duration-300 focus:outline-none">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</div>
</div>
</nav>
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
<!-- Main Content -->
<main class="relative z-10 pt-20 flex-grow">
<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center relative px-8 py-20 overflow-hidden">
<!-- Smoke WebGL Background (only for hero) -->
<canvas id="smoke-canvas" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index:0;"></canvas>
<div class="max-w-5xl mx-auto flex flex-col items-center text-center space-y-8 relative" style="z-index:2;">
<div class="glass-panel px-6 py-2 rounded-full inline-flex items-center space-x-2 text-sm font-medium tracking-wide">
<span class="text-primary">✦</span>
<span class="text-on-surface-variant">Премиум цифровые услуги</span>
</div>
<h1 class="text-[64px] font-headline font-extrabold leading-[1.1] tracking-[-0.02em] text-on-surface max-w-4xl">
                    Ваши цифровые проблемы, решаемые <span class="text-gradient">быстро</span>
</h1>
<p class="text-lg md:text-xl text-on-surface-variant max-w-2xl font-medium">
                    Эксклюзивный доступ к передовым решениям. Мы не просто пишем код, мы создаем цифровой опыт будущего.
                </p>
<div class="pt-8">
<a href="services.php" class="glow-button bg-primary text-on-primary-fixed font-bold text-lg px-10 py-4 rounded-xl inline-flex items-center space-x-3">
<span>Начать проект</span>
<span class="material-symbols-outlined font-bold">arrow_forward</span>
</a>
</div>
</div>
</section>
<!-- Stats Section -->
<section class="py-24 px-8 relative z-10 bg-surface-container-low/50">
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
<!-- Stat 1 -->
<div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-0">
<span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="500">0+</span>
<span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Довольных клиентов</span>
</div>
<!-- Stat 2 -->
<div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-12">
<span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="99.9" data-suffix="%" data-decimal="1">0%</span>
<span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Uptime систем</span>
</div>
<!-- Stat 3 -->
<div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-24">
<span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="12">0</span>
<span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Наград за дизайн</span>
</div>
</div>
</section>
<!-- Services Section -->
<section class="py-32 px-8 relative z-10">
<div class="max-w-7xl mx-auto">
<div class="mb-20 pl-4 md:pl-16 border-l-2 border-primary/30">
<h2 class="text-4xl font-headline font-bold text-on-surface mb-4 tracking-tight">Наши Решения</h2>
<p class="text-on-surface-variant max-w-xl text-lg">Комплексный подход к вашему цифровому присутствию.</p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Service Card 1 -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-high/80 hover:scale-[1.02] transition-transform duration-300 group flex flex-col h-[400px]">
<div class="mb-auto">
<span class="material-symbols-outlined text-4xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;">code</span>
<h3 class="text-2xl font-bold text-on-surface mb-3">Веб-разработка</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
                                Высокопроизводительные приложения на современных стеках. От архитектуры до деплоя.
                            </p>
</div>
<div class="flex justify-between items-end mt-8">
<a href="services.php" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1">
                                Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
<span class="text-[28px] font-bold text-primary">от $1k</span>
</div>
</div>
<!-- Service Card 2 -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-high/80 hover:scale-[1.02] transition-transform duration-300 group flex flex-col h-[400px]">
<div class="mb-auto">
<span class="material-symbols-outlined text-4xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;">design_services</span>
<h3 class="text-2xl font-bold text-on-surface mb-3">UI/UX Дизайн</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
                                Интерфейсы, которые продают. Глубокая аналитика и премиальный визуальный стиль.
                            </p>
</div>
<div class="flex justify-between items-end mt-8">
<a href="services.php" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1">
                                Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
<span class="text-[28px] font-bold text-primary">от $800</span>
</div>
</div>
<!-- Service Card 3 -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-high/80 hover:scale-[1.02] transition-transform duration-300 group flex flex-col h-[400px]">
<div class="mb-auto">
<span class="material-symbols-outlined text-4xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;">security</span>
<h3 class="text-2xl font-bold text-on-surface mb-3">Кибербезопасность</h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
                                Аудит и защита вашей инфраструктуры по высочайшим стандартам безопасности.
                            </p>
</div>
<div class="flex justify-between items-end mt-8">
<a href="services.php" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1">
                                Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
<span class="text-[28px] font-bold text-primary">от $2k</span>
</div>
</div>
</div>
</div>
</section>
<!-- Testimonials Section -->
<section class="py-32 px-8 relative z-10 bg-surface-container-low/50">
<div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16">
<div class="w-full md:w-1/3 flex flex-col items-start pr-8">
<h2 class="text-4xl font-headline font-bold text-on-surface mb-6 tracking-tight">Что говорят<br/>партнеры</h2>
<p class="text-on-surface-variant text-lg">Доверие, подкрепленное результатами.</p>
</div>
<div class="w-full md:w-2/3 flex flex-col space-y-8 relative">
<!-- Testimonial 1 -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-[0_40px_80px_rgba(105,218,255,0.08)] transform md:-translate-x-8 z-20">
<div class="flex text-primary mb-4">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface font-medium text-lg mb-6 italic">"Команда BitBuddy полностью переосмыслила нашу платформу. Конверсия выросла на 40% за первый месяц."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-surface-container overflow-hidden ">
<img alt="Avatar" class="w-full h-full object-cover" data-alt="close up professional headshot of a smiling middle aged man in a dark modern office setting soft side lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8zETM7H8WLjpa1Crg-9f7dKVmjtPR0rFXIkw2pdknajrRn5hd40S_7K0eyDuWSORuDzjTiqOOvfxe3GvfTex0gIJGqYcuDLcfcuJ3wFdMseRP4gXEIFnU-s5llDs2s-Do4q3X5XNU7oD8nZw-6_O7UOIZpIRq7a2uYrsgzphN_KyGlRNGUKCcjZBR3zrtQ3s-UG6nPRqHur9EXKYWTHN8L0bvwWAIoIudDnpcVAWb7DsEUkqIZU-uAYiWlkswNi1KCWKcWCWbC_Rx"/>
</div>
<div>
<h4 class="text-on-surface font-bold">Алексей Смирнов</h4>
<span class="text-on-surface-variant text-sm">CEO, TechNova</span>
</div>
</div>
</div>
<!-- Testimonial 2 -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-[0_40px_80px_rgba(105,218,255,0.08)] transform md:translate-x-12 z-10">
<div class="flex text-primary mb-4">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface font-medium text-lg mb-6 italic">"Их подход к дизайну не имеет равных. Интерфейс выглядит невероятно премиально и работает безупречно."</p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-surface-container overflow-hidden ">
<img alt="Avatar" class="w-full h-full object-cover" data-alt="professional portrait of a confident young woman with short hair soft elegant lighting muted dark background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCH_Vb0wdmdie1NnohgPOsnkxKG-v7Q6aCClS_kljLyAL7st8aXUvBhknJawvP3hLvAhpbR_6qXOF9u78oBUaBklFh__wYJBR5BPh5V5150W7u21n8rVOIry-tvmENbTpkFF0SZay6tomaVRXEERMxCcUhrGv3IRBU5vePOTgKNGguk1qz4G7dYX5I4QHbwyqI7jzL6mva4wW-oJbkvl5QbLKV53t_1msoKiLDZ1ZftUBwu18GsjJt0BcJ6fcWSI4478MH_9ppWm6Cy"/>
</div>
<div>
<h4 class="text-on-surface font-bold">Елена Волкова</h4>
<span class="text-on-surface-variant text-sm">Creative Director, ArtSpace</span>
</div>
</div>
</div>
</div>
</div>
</section>
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
<a class="text-[#69daff] underline underline-offset-4 hover:text-white hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="index.php">О нас</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="contacts.php">Контакты</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
</body></html>