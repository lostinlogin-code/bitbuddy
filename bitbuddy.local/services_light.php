<?php
require 'db_connect.php';

// Получаем все категории
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получаем выбранную категорию из URL
$slug = isset($_GET['category']) ? $_GET['category'] : 'all';

// Получаем услуги
if ($slug !== 'all') {
    $stmt = $pdo->prepare("SELECT s.*, c.name as cat_name, c.slug as cat_slug, c.icon as cat_icon FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE c.slug = ?");
    $stmt->execute([$slug]);
} else {
    $stmt = $pdo->query("SELECT s.*, c.name as cat_name, c.slug as cat_slug, c.icon as cat_icon FROM services s 
        JOIN categories c ON s.category_id = c.id");
}
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Маппинг категорий на цвет иконки
$catIconColor = [
    'design' => 'primary',
    'development' => 'secondary-dim',
    'it-support' => 'tertiary-dim',
    'video' => 'primary',
];
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Каталог Услуг</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="theme.css?v=3"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            safelist: [
                "text-primary","text-secondary-dim","text-tertiary-dim",
                "bg-primary/10","bg-primary/20","border-primary/20","bg-primary",
                "bg-surface-container-high/80","bg-surface-variant/50","border-outline-variant/15","border-outline-variant/10","border-outline-variant/20","border-outline-variant/30",
                "text-on-surface","text-on-surface-variant","text-on-primary","bg-surface-variant/30",
                "bg-surface/70","hover:bg-surface-variant","hover:text-on-surface","hover:border-outline-variant/30"
            ],
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
<body class="bg-background text-on-background font-body min-h-screen relative overflow-x-hidden selection:bg-primary/20 selection:text-on-primary-container">
<!-- Glowing Orbs (Ambient Background) -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
<div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/8 blur-[120px] mix-blend-multiply"></div>
<div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-dim/5 blur-[150px] mix-blend-multiply"></div>
</div>
<!-- TopNavBar Shared Component -->
<nav class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-[25px] border-b border-outline-variant/15 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] font-inter tracking-tight antialiased transition-all duration-300">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none mx-auto relative z-10">
<!-- Brand -->
<a class="text-2xl font-extrabold tracking-tighter text-on-surface hover:opacity-80 transition-all duration-300" href="#">BitBuddy</a>
<!-- Navigation Links (Web) -->
<div class="hidden md:flex items-center space-x-8">
<a class="text-primary font-semibold border-b-2 border-primary pb-1 hover:opacity-80 transition-all duration-300" href="services_light.php">Услуги</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 hover:opacity-80 transition-all duration-300" href="index_light.php">О нас</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 hover:opacity-80 transition-all duration-300" href="contacts_light.php">Контакты</a>
</div>
<!-- Actions -->
<div class="flex items-center space-x-6">
<button onclick="location.href='services.php'" aria-label="Переключить на темную тему" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 hover:opacity-80 transition-all duration-300 active:scale-95 transform transition-transform">
<span class="material-symbols-outlined text-xl">light_mode</span>
</button>
<a href="login_light.php" class="hidden md:flex items-center justify-center px-6 py-2 rounded-full bg-surface-variant backdrop-blur-[25px] border border-outline-variant/15 text-primary hover:bg-primary hover:text-on-primary hover:shadow-[0_0_20px_rgba(14,165,233,0.3)] transition-all duration-300 active:scale-95 transform font-medium">
                Войти
            </a>
<!-- Mobile Menu Toggle -->
<button class="md:hidden text-on-surface hover:text-primary transition-colors duration-300">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</div>
</div>
</nav>
<!-- Main Content Canvas -->
<main class="relative z-10 pt-32 pb-24 px-6 md:px-12 lg:px-24 max-w-[1600px] mx-auto min-h-screen flex flex-col gap-16">
<!-- Hero Header -->
<header class="flex flex-col items-center text-center max-w-3xl mx-auto space-y-6">
<h1 class="text-5xl md:text-6xl font-headline font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-container leading-tight">
                Каталог Услуг
            </h1>
<p class="text-lg md:text-xl text-on-surface-variant font-light max-w-2xl leading-relaxed">
                Выбирайте из кураторского списка высококачественных цифровых решений, созданных для ускорения вашего бизнеса.
            </p>
</header>
<!-- Filters (Pills) -->
<section class="flex flex-wrap justify-center gap-4 w-full">
<a href="services_light.php" class="px-6 py-2 rounded-full <?= $slug === 'all' ? 'bg-primary text-on-primary shadow-[0_4px_15px_rgba(14,165,233,0.3)] border border-primary' : 'bg-surface-variant/50 backdrop-blur-md text-on-surface-variant border border-outline-variant/15 hover:bg-surface-variant hover:text-on-surface hover:border-outline-variant/30' ?> font-medium tracking-wide transition-all duration-300 hover:scale-105 active:scale-95">Все</a>
<?php foreach ($categories as $cat): ?>
<a href="services_light.php?category=<?= $cat['slug'] ?>" class="px-6 py-2 rounded-full <?= $slug === $cat['slug'] ? 'bg-primary text-on-primary shadow-[0_4px_15px_rgba(14,165,233,0.3)] border border-primary' : 'bg-surface-variant/50 backdrop-blur-md text-on-surface-variant border border-outline-variant/15 hover:bg-surface-variant hover:text-on-surface hover:border-outline-variant/30' ?> font-medium tracking-wide transition-all duration-300 hover:scale-105 active:scale-95">
<?= htmlspecialchars($cat['name']) ?>
</a>
<?php endforeach; ?>
</section>
<!-- Services Grid -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-8 relative w-full">
<?php if (empty($services)): ?>
<div class="col-span-full text-center py-16 text-on-surface-variant">
<p class="text-lg">В данной категории пока нет услуг</p>
</div>
<?php else: ?>
<?php foreach ($services as $service): ?>
<?php
$catSlug = $service['cat_slug'] ?? '';
$icon = $service['cat_icon'] ?? 'design_services';
$iconText = 'text-' . ($catIconColor[$catSlug] ?? 'primary');
?>
<div class="glass-panel relative p-8 rounded-2xl bg-surface-container-high/80 hover:scale-[1.02] transition-transform duration-300 group flex flex-col h-[400px]">
<?php if ($service['is_popular'] == 1): ?>
<div class="absolute top-6 right-6 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 flex items-center gap-1.5 shadow-[0_0_15px_rgba(14,165,233,0.1)]">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
<span class="text-xs font-semibold text-primary uppercase tracking-wider">Популярное</span>
</div>
<?php endif; ?>
<div class="mb-auto">
<span class="material-symbols-outlined text-4xl <?= $iconText ?> mb-6 block" style="font-variation-settings: 'FILL' 1;"><?= htmlspecialchars($icon) ?></span>
<h3 class="text-2xl font-bold text-on-surface mb-3"><?= htmlspecialchars($service['title']) ?></h3>
<p class="text-on-surface-variant text-sm leading-relaxed">
<?= htmlspecialchars($service['description']) ?>
</p>
</div>
<div class="flex justify-between items-end mt-8">
<a href="service_light.php?id=<?= $service['id'] ?>" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-1 cursor-pointer">
Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
<span class="text-[28px] font-bold text-primary"><?= number_format($service['price'], 0, ',', ' ') ?> ₽</span>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</section>
<!-- Load More / CTA -->
<div class="flex justify-center pt-8">
<button class="flex items-center gap-2 px-8 py-4 rounded-full bg-surface-variant/30 backdrop-blur-[25px] border border-outline-variant/20 text-on-surface hover:text-primary hover:border-primary/50 transition-all duration-300 hover:shadow-[0_0_30px_rgba(14,165,233,0.08)]">
<span class="font-medium tracking-wide">Загрузить еще</span>
<span class="material-symbols-outlined text-xl">expand_more</span>
</button>
</div>
</main>
<!-- Footer Shared Component -->
<footer class="w-full relative bottom-0 bg-surface border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] font-inter text-sm tracking-wide z-20">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="flex flex-col items-center md:items-start gap-4">
<span class="text-xl font-black text-on-surface tracking-tighter">BitBuddy</span>
<span class="text-on-surface-variant">© 2024 BitBuddy. The Ethereal Exchange.</span>
</div>
<nav class="flex flex-wrap justify-center md:justify-end gap-x-8 gap-y-4">
<a class="text-primary underline underline-offset-4 hover:text-on-surface hover:drop-shadow-[0_0_8px_#0ea5e9] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Услуги</a>
<a class="text-on-surface-variant hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">О нас</a>
<a class="text-on-surface-variant hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Контакты</a>
<a class="text-on-surface-variant hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
</body></html>
