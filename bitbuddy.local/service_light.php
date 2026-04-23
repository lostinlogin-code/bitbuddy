<?php
session_start();
require 'db_connect.php';

// Получаем ID услуги
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    header("Location: 404.php");
    exit;
}

// Получаем данные услуги
$stmt = $pdo->prepare("SELECT s.*, c.name as cat_name, c.slug as cat_slug FROM services s 
    JOIN categories c ON s.category_id = c.id 
    WHERE s.id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header("Location: 404.php");
    exit;
}

$success = '';
$error = '';

// Обработка заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, service_id, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $id]);
        $success = 'Заказ успешно оформлен! Мы свяжемся с вами в ближайшее время.';
    } catch (PDOException $e) {
        $error = 'Ошибка при оформлении заказа. Пожалуйста, попробуйте позже.';
    }
}

// Получаем похожие услуги
$stmt = $pdo->prepare("SELECT s.*, c.name as cat_name FROM services s 
    JOIN categories c ON s.category_id = c.id 
    WHERE s.category_id = ? AND s.id != ? 
    LIMIT 3");
$stmt->execute([$service['category_id'], $id]);
$similarServices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Service Details</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&amp;display=swap" rel="stylesheet"/>
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
                    "spacing": {},
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
<script src="theme.js"></script>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col overflow-x-hidden relative">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-slate-200/50 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] bg-white/70 backdrop-blur-[25px]">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<div class="text-2xl font-extrabold tracking-tighter text-slate-900">
                BitBuddy
            </div>
<div class="hidden md:flex gap-8 items-center font-inter tracking-tight antialiased">
<a class="text-primary font-semibold border-b-2 border-primary pb-1" href="#">Услуги</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="#">О нас</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="#">Контакты</a>
</div>
<div class="flex items-center gap-6">
<button onclick="location.href='service.php?id=<?= $id ?>'" class="text-slate-500 hover:text-slate-900 transition-colors duration-300 active:scale-95 transform transition-transform" title="Переключить на темную тему">
<span class="material-symbols-outlined" data-icon="light_mode">light_mode</span>
</button>
<button class="text-primary font-semibold hover:opacity-80 transition-all duration-300 active:scale-95 transform transition-transform">
                    Войти
                </button>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="flex-grow pt-32 pb-24 relative z-10">
<!-- Ambient Background Orbs -->
<div class="ambient-glow bg-primary w-[600px] h-[600px] top-[-100px] left-[-200px]"></div>
<div class="ambient-glow bg-secondary-dim w-[800px] h-[800px] bottom-[200px] right-[-300px] opacity-[0.05]"></div>
<div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-24">
<!-- Hero / Details Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 mb-32 items-start">
<!-- Left: Title & Imagery -->
<div class="lg:col-span-7 flex flex-col gap-8">
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel ghost-border w-max">
<span class="material-symbols-outlined text-primary text-sm" data-icon="code">code</span>
<span class="text-on-surface-variant text-sm font-medium tracking-wide uppercase"><?= htmlspecialchars($service['cat_name']) ?></span>
</div>
<h1 class="text-5xl md:text-[64px] font-[800] leading-[1.1] tracking-[-0.02em] text-on-surface">
<?= htmlspecialchars($service['title']) ?>
</h1>
<div class="w-full h-[400px] rounded-xl overflow-hidden glass-panel ghost-border relative group mt-4">
<img alt="Service preview" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-700" data-alt="Abstract glowing code interface with deep neon blue and purple hues on a dark glass screen, highly detailed, cinematic lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAs_4xYdzFuN8kif3X-r-KbJTClkL1Miwp6U7Pcepji3TcqfPthPbq33XoEkK1rMrmLCRglexEIB-8G6KqzhdiTcOxVUsy-9Bq5kqBdEjrxa7JMdvwWmfFoks0tGT2lDKnhnOVHz0bPokjXQQDmdw7YeG8yJbbcfIhuzMUhMn_AVPDdnVUkaRqJ-Kf8hzT8fyjXTzSu8StXA3huoTQCyxQNaUKFFaIJmzra5ij_n7sJ_4eBTKMbYfHFa6E0FYfSR93atArXkO3peb6i"/>
<div class="absolute inset-0 bg-gradient-to-t from-white via-transparent to-transparent"></div>
</div>
</div>
<!-- Right: Booking Pane -->
<div class="lg:col-span-5 sticky top-32">
<div class="glass-panel ghost-border rounded-2xl p-8 md:p-10 flex flex-col gap-8 relative overflow-hidden shadow-[0_20px_60px_rgba(14,165,233,0.06)] transition-all duration-500 hover:shadow-[0_30px_80px_rgba(14,165,233,0.1)]">
<!-- Inner Orb for depth -->
<div class="absolute -top-20 -right-20 w-64 h-64 bg-primary rounded-full blur-[80px] opacity-10 pointer-events-none"></div>
<div>
<div class="text-primary text-[28px] font-bold tracking-tight mb-2">₽<?= number_format($service['price'], 0, ',', ' ') ?></div>
<p class="text-on-surface-variant text-sm leading-relaxed">
<?= htmlspecialchars($service['description']) ?>
</p>
</div>
<?php if ($success): ?>
<div class="p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-600 text-sm text-center">
<?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="p-4 bg-red-100 border border-red-300 rounded-xl text-red-600 text-sm text-center">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>
<div class="space-y-4">
<div class="flex justify-between items-center py-3 border-b border-slate-200">
<span class="text-on-surface-variant text-sm">Срок выполнения</span>
<span class="text-on-surface font-medium">от 14 дней</span>
</div>
<div class="flex justify-between items-center py-3 border-b border-slate-200">
<span class="text-on-surface-variant text-sm">Аудит включен</span>
<span class="material-symbols-outlined text-primary text-sm" data-icon="check_circle">check_circle</span>
</div>
<div class="flex justify-between items-center py-3 border-b border-slate-200">
<span class="text-on-surface-variant text-sm">Поддержка</span>
<span class="text-on-surface font-medium">30 дней</span>
</div>
</div>
<form method="POST" action="">
<button type="submit" class="w-full py-4 rounded-lg bg-primary text-white font-bold tracking-wide transition-all duration-300 glow-hover relative overflow-hidden group mt-4">
<span class="relative z-10 flex items-center justify-center gap-2">
                            Заказать сейчас
                            <span class="material-symbols-outlined text-xl" data-icon="arrow_forward">arrow_forward</span>
</span>
</button>
</form>
</div>
</div>
</div>
<!-- Similar Services -->
<?php if (!empty($similarServices)): ?>
<div class="pt-16 border-t border-slate-200">
<div class="flex justify-between items-end mb-12">
<h2 class="text-3xl font-bold tracking-tight text-on-surface">Похожие услуги</h2>
<a class="text-primary text-sm font-medium hover:text-slate-900 transition-colors duration-300 flex items-center gap-1" href="services_light.php?category=<?= $service['cat_slug'] ?>">
                Смотреть все
                <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<?php foreach ($similarServices as $similar): ?>
<a href="service.php?id=<?= $similar['id'] ?>" class="glass-panel ghost-border rounded-xl overflow-hidden group hover:scale-[1.02] transition-transform duration-500 cursor-pointer flex flex-col h-full bg-white/50">
<div class="h-48 relative overflow-hidden bg-slate-100">
<div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-secondary-dim/10"></div>
<div class="absolute inset-0 bg-gradient-to-t from-white/90 to-transparent"></div>
</div>
<div class="p-6 flex flex-col flex-grow relative">
<div class="text-primary text-xl font-bold mb-3 absolute -top-14 right-6 glass-panel px-3 py-1 rounded-md ghost-border shadow-lg">₽<?= number_format($similar['price'], 0, ',', ' ') ?></div>
<h3 class="text-lg font-bold text-on-surface mb-2 mt-2 leading-tight"><?= htmlspecialchars($similar['title']) ?></h3>
<p class="text-on-surface-variant text-sm mb-6 flex-grow"><?= htmlspecialchars(mb_substr($similar['description'], 0, 80)) ?>...</p>
<div class="flex items-center gap-2 text-xs text-on-surface-variant">
<span class="material-symbols-outlined text-[16px]" data-icon="schedule">schedule</span>
<?= htmlspecialchars($similar['cat_name']) ?>
</div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
<?php endif; ?>
</div>
</main>
<!-- Footer -->
<footer class="w-full relative bottom-0 border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] bg-white z-20">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8 font-inter text-sm tracking-wide">
<div class="text-xl font-black text-slate-900 tracking-tighter">
                BitBuddy
            </div>
<div class="flex flex-wrap justify-center gap-6 md:gap-8">
<a class="text-primary underline underline-offset-4 focus:outline-none focus:ring-2 focus:ring-primary" href="#">Услуги</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary" href="#">О нас</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary" href="#">Контакты</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary" href="#">Политика конфиденциальности</a>
</div>
<div class="text-slate-500">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
</body></html>
