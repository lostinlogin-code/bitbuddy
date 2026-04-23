<?php
session_start();
require 'db_connect.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Получаем данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Получаем заказы пользователя
$stmt = $pdo->prepare("SELECT o.id, o.status, o.created_at, s.title, s.price 
    FROM orders o 
    JOIN services s ON o.service_id = s.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Функция для получения текста и цвета статуса
function getStatusInfo($status) {
    $statuses = [
        'pending' => ['text' => 'Ожидает', 'color' => '#f59e0b'],
        'active' => ['text' => 'Активен', 'color' => '#0ea5e9'],
        'completed' => ['text' => 'Выполнен', 'color' => '#10b981'],
        'cancelled' => ['text' => 'Отменён', 'color' => '#ef4444']
    ];
    return $statuses[$status] ?? ['text' => $status, 'color' => '#666'];
}
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Dashboard</title>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link rel="stylesheet" href="theme.css?v=3"/>
<!-- Tailwind Configuration -->
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
<body class="bg-background text-on-surface min-h-screen flex flex-col font-body antialiased overflow-x-hidden selection:bg-primary/20 selection:text-primary">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-slate-200/50 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] bg-white/70 backdrop-blur-[25px] flex justify-between items-center px-8 h-20 max-w-none">
<!-- Brand -->
<div class="text-2xl font-extrabold tracking-tighter text-slate-900 font-inter tracking-tight antialiased">
            BitBuddy
        </div>
<!-- Navigation Links (Hidden on Mobile) -->
<ul class="hidden md:flex space-x-8">
<li><a class="text-slate-500 hover:text-slate-900 transition-colors duration-300 font-inter tracking-tight antialiased hover:opacity-80 active:scale-95 transform" href="#">Услуги</a></li>
<li><a class="text-slate-500 hover:text-slate-900 transition-colors duration-300 font-inter tracking-tight antialiased hover:opacity-80 active:scale-95 transform" href="#">О нас</a></li>
<li><a class="text-slate-500 hover:text-slate-900 transition-colors duration-300 font-inter tracking-tight antialiased hover:opacity-80 active:scale-95 transform" href="#">Контакты</a></li>
</ul>
<!-- Trailing Actions -->
<div class="flex items-center gap-6">
<button onclick="location.href='profile.php'" class="text-slate-500 hover:text-primary transition-colors duration-300 hover:opacity-80 active:scale-95 transform" title="Переключить на темную тему">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">light_mode</span>
</button>
<a href="logout.php" class="bg-primary/10 text-primary border border-primary/30 px-6 py-2 rounded-full font-semibold tracking-wide hover:bg-primary/20 hover:shadow-[0_0_20px_rgba(14,165,233,0.3)] transition-all duration-300 active:scale-95 font-inter">
                Выйти
            </a>
</div>
</nav>
<!-- Main Layout Grid -->
<main class="flex-1 flex w-full pt-28 pb-12 px-6 lg:px-12 max-w-[1600px] mx-auto gap-8 relative">
<!-- Background Orbs -->
<div class="absolute top-20 -left-40 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
<div class="absolute bottom-40 -right-20 w-[400px] h-[400px] bg-secondary-dim/5 rounded-full blur-[100px] pointer-events-none"></div>
<!-- Sidebar (Glass Panel) -->
<aside class="hidden lg:flex flex-col w-72 bg-white/50 backdrop-blur-[25px] border border-slate-200/50 rounded-3xl p-6 h-[calc(100vh-160px)] sticky top-28 shrink-0">
<!-- User Avatar & Info -->
<div class="flex flex-col items-center mb-10 mt-4">
<div class="relative group">
<div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary-dim rounded-full blur opacity-75 group-hover:opacity-100 transition duration-500"></div>
<img alt="User Avatar" class="relative w-24 h-24 rounded-full object-cover border-2 border-white bg-white" data-alt="portrait of a young man with glasses and neutral expression in soft studio lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCuTrtH64bzmZuzgAM032eu0LTmQP6XFRuNWS59826hBiXN01Y18jrDHBKasdb3KlTLKYBGj2uQhaNg-iqaHV_jTX6lsoiDhCG4jV6GB_Xd-HbjLvWddoofSmC0pwwX-n7lUUgMfiEitPkex9PmOKev8-vdfGWz_7WeAsTFZwW6KLDkRuSTRuRnJVtM2G4WBSBwYel0tWdScSfz6_cVAiihvd0J4w49-GG9VkKL7hW-CO6Sh7YuYNqVNhiTHDZ57iUCkLsHFv4Nw9Z"/>
</div>
<h3 class="mt-4 font-bold text-lg text-slate-900"><?= htmlspecialchars($_SESSION['username']) ?></h3>
<p class="text-sm text-on-surface-variant"><?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></p>
</div>
<!-- Navigation -->
<nav class="flex-1 space-y-2">
<a class="flex items-center gap-4 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20 font-medium transition-all" href="#">
<span class="material-symbols-outlined">dashboard</span>
                    Дашборд
                </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-slate-900 hover:bg-slate-100 transition-all group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">history</span>
                    История заказов
                </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-slate-900 hover:bg-slate-100 transition-all group" href="#">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                    Настройки
                </a>
</nav>
<!-- Bottom Action -->
<div class="mt-auto">
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-error-dim hover:text-error hover:bg-red-50 transition-all" href="logout.php">
<span class="material-symbols-outlined">logout</span>
                Выйти
            </a>
</div>
</aside>
<!-- Main Content Area -->
<div class="flex-1 flex flex-col gap-10 min-w-0">
<!-- Header -->
<header>
<h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-slate-900 to-slate-500">
                Добро пожаловать, <?= htmlspecialchars($_SESSION['username']) ?>
            </h1>
<p class="mt-2 text-on-surface-variant text-lg">Обзор вашей активности в The Ethereal Exchange.</p>
</header>
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Stat Card 1 -->
<div class="bg-white/60 backdrop-blur-[25px] border border-slate-200/50 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-white transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-primary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Всего потрачено</div>
<span class="material-symbols-outlined text-primary">account_balance_wallet</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-slate-900">$4,250</span>
<span class="text-primary text-sm font-medium">.00</span>
</div>
</div>
<!-- Stat Card 2 -->
<div class="bg-white/60 backdrop-blur-[25px] border border-slate-200/50 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-white transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-secondary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Активные заказы</div>
<span class="material-symbols-outlined text-secondary">autorenew</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-slate-900"><?= count(array_filter($orders, fn($o) => $o['status'] === 'active')) ?></span>
</div>
</div>
<!-- Stat Card 3 -->
<div class="bg-white/60 backdrop-blur-[25px] border border-slate-200/50 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-white transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-tertiary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-tertiary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Всего заказов</div>
<span class="material-symbols-outlined text-tertiary">inventory_2</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-slate-900"><?= count($orders) ?></span>
</div>
</div>
</div>
<!-- Orders Table Section -->
<section class="flex flex-col gap-6">
<div class="flex justify-between items-center">
<h2 class="text-2xl font-bold text-slate-900 tracking-tight">Недавние транзакции</h2>
<button class="text-primary text-sm font-medium hover:text-slate-900 transition-colors">Смотреть все</button>
</div>
<div class="bg-slate-50/80 backdrop-blur-[25px] border border-slate-200/50 rounded-3xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.08)]">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-slate-100/50 border-b border-slate-200">
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">ID Заказа</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Услуга</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Цена</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Статус</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Дата</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-200">
<?php if (empty($orders)): ?>
<tr>
<td colspan="5" class="px-6 py-8 text-sm text-on-surface-variant text-center">У вас пока нет заказов</td>
</tr>
<?php else: ?>
<?php foreach ($orders as $order): 
    $statusInfo = getStatusInfo($order['status']);
?>
<tr class="hover:bg-slate-100/50 transition-colors duration-200 group">
<td class="px-6 py-5 text-sm font-medium text-slate-900">#<?= htmlspecialchars($order['id']) ?></td>
<td class="px-6 py-5 text-sm text-on-surface-variant group-hover:text-slate-900 transition-colors"><?= htmlspecialchars($order['title']) ?></td>
<td class="px-6 py-5 text-sm font-bold text-primary"><?= number_format($order['price'], 0, ',', ' ') ?> ₽</td>
<td class="px-6 py-5">
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border" style="background-color: <?= $order['status'] === 'pending' ? 'rgba(245,158,11,0.1)' : ($order['status'] === 'active' ? 'rgba(14,165,233,0.1)' : ($order['status'] === 'completed' ? 'rgba(16,185,129,0.1)' : 'rgba(239,68,68,0.1)')) ?>; color: <?= $statusInfo['color'] ?>; border-color: <?= $statusInfo['color'] ?>30;">
<span class="w-1.5 h-1.5 rounded-full" style="background-color: <?= $statusInfo['color'] ?>; <?= $order['status'] === 'active' ? 'box-shadow: 0 0 8px ' . $statusInfo['color'] : '' ?>"></span>
<?= $statusInfo['text'] ?>
</span>
</td>
<td class="px-6 py-5 text-sm text-on-surface-variant text-right"><?= date('d.m.Y', strtotime($order['created_at'])) ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</section>
</div>
</main>
<!-- Footer -->
<footer class="w-full relative bottom-0 border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] bg-white max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8 font-inter text-sm tracking-wide z-10">
<!-- Brand / Copyright -->
<div class="flex flex-col md:flex-row items-center gap-6">
<span class="text-xl font-black text-slate-900 tracking-tighter">BitBuddy</span>
<span class="text-slate-400">© 2024 BitBuddy. The Ethereal Exchange.</span>
</div>
<!-- Links -->
<div class="flex flex-wrap justify-center gap-6">
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Услуги</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">О нас</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Контакты</a>
<a class="text-slate-500 hover:text-primary hover:drop-shadow-[0_0_8px_#0ea5e9] transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary rounded" href="#">Политика конфиденциальности</a>
</div>
</footer>
</body></html>
