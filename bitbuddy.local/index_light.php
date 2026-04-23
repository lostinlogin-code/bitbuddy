<?php
require 'db_connect.php';

// Получаем популярные услуги
$stmt = $pdo->prepare("SELECT * FROM services WHERE is_popular = 1 LIMIT 3");
$stmt->execute();
$popularServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
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
</head>
<body class="bg-surface text-on-surface font-body overflow-x-hidden relative min-h-screen">
<!-- Ambient Orbs -->
<div class="ambient-orb w-[800px] h-[800px] top-[-200px] left-[-200px]"></div>
<div class="ambient-orb w-[600px] h-[600px] top-[40%] right-[-100px] opacity-60"></div>
<div class="ambient-orb w-[1000px] h-[1000px] bottom-[-300px] left-[20%] opacity-40"></div>
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-[25px] border-b border-slate-200/50 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<div class="text-2xl font-extrabold tracking-tighter text-slate-900">
                BitBuddy
            </div>
<div class="hidden md:flex items-center space-x-8">
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="services_light.php">Услуги</a>
<a class="text-primary font-semibold border-b-2 border-primary pb-1" href="index_light.php">О нас</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="contacts_light.php">Контакты</a>
</div>
<div class="flex items-center space-x-6">
<button onclick="location.href='index.php'" class="text-slate-500 hover:text-slate-900 transition-colors duration-300 focus:outline-none" title="Переключить на темную тему">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">light_mode</span>
</button>
<a href="login_light.php" class="text-primary hover:opacity-80 transition-all duration-300 active:scale-95 transform font-semibold focus:outline-none">
                Войти
            </a>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="relative z-10 pt-20">
<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center relative px-8 py-20 overflow-hidden">
<!-- Background Video -->
<video autoplay muted loop playsinline style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;opacity:0.4;">
<source src="images/hero-bg.mp4" type="video/mp4">
</video>
<div class="max-w-5xl mx-auto flex flex-col items-center text-center space-y-8 z-10 relative">
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
<button class="glow-button bg-primary text-white font-bold text-lg px-10 py-4 rounded-xl inline-flex items-center space-x-3">
<span>Начать проект</span>
<span class="material-symbols-outlined font-bold">arrow_forward</span>
</button>
</div>
</div>
</section>
<!-- Stats Section -->
<section class="py-24 px-8 relative z-10 bg-surface-container-low/50">
<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Stat 1 -->
<div class="glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center transform translate-y-0">
<span class="text-[48px] font-black text-primary mb-2 tracking-tighter">500+</span>
<span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Довольных клиентов</span>
</div>
<!-- Stat 2 -->
<div class="glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center transform md:translate-y-8">
<span class="text-[48px] font-black text-primary mb-2 tracking-tighter">99.9%</span>
<span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Uptime систем</span>
</div>
<!-- Stat 3 -->
<div class="glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center transform md:translate-y-16">
<span class="text-[48px] font-black text-primary mb-2 tracking-tighter">12</span>
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
<?php foreach ($popularServices as $service): ?>
<!-- Service Card -->
<div class="glass-panel p-8 rounded-2xl bg-surface-container-high/80 hover:scale-[1.02] transition-transform duration-300 group flex flex-col h-[400px]">
<div class="mb-auto">
<span class="material-symbols-outlined text-4xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;">code</span>
<h3 class="text-2xl font-bold text-on-surface mb-3"><?= htmlspecialchars($service['title']) ?></h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
<?= htmlspecialchars($service['description']) ?>
</p>
</div>
<div class="flex justify-between items-end mt-8">
<a href="service.php?id=<?= $service['id'] ?>" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1 cursor-pointer">
Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
<span class="text-[28px] font-bold text-primary"><?= number_format($service['price'], 0, ',', ' ') ?> ₽</span>
</div>
</div>
<?php endforeach; ?>
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
<div class="glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-[0_20px_40px_rgba(14,165,233,0.06)] transform md:-translate-x-8 z-20">
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
<div class="glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-[0_20px_40px_rgba(14,165,233,0.06)] transform md:translate-x-12 z-10">
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
<footer class="w-full relative bottom-0 bg-white border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] font-inter text-sm tracking-wide z-50">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="text-xl font-black text-slate-900 tracking-tighter">
                BitBuddy
            </div>
<div class="flex flex-wrap justify-center gap-6">
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Услуги</a>
<a class="text-primary underline underline-offset-4 hover:text-slate-900 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">О нас</a>
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Контакты</a>
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Политика конфиденциальности</a>
</div>
<div class="text-slate-500 text-xs">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
</body></html>
