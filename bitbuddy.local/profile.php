<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Пользователь';

// Fetch user orders with service details
$orders = [];
try {
    $stmt = $pdo->prepare('SELECT o.*, s.title as service_name, s.price FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC');
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    // Table might not exist yet or user has no orders
}

$total_spent = array_sum(array_column($orders, 'price'));
$active_orders = count(array_filter($orders, fn($o) => $o['status'] === 'active'));
$total_orders = count($orders);
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
<title>BitBuddy - Dashboard</title>
<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="theme.css?v=3"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
<style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col font-body antialiased overflow-x-hidden selection:bg-primary/30 selection:text-primary">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] bg-neutral-950/60 backdrop-blur-[25px] flex justify-between items-center px-8 h-20 max-w-none">
<!-- Brand -->
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<!-- Navigation Links (Hidden on Mobile) -->
<div class="hidden md:flex items-center space-x-8">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</div>
<!-- Trailing Actions -->
<div class="flex items-center gap-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-primary transition-colors duration-300" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 1;">dark_mode</span>
</button>
<a href="logout.php" class="hidden md:inline bg-primary/10 text-primary border border-primary/30 px-6 py-2 rounded-full font-semibold tracking-wide hover:bg-primary/20 hover:shadow-[0_0_20px_rgba(105,218,255,0.4)] transition-all duration-300 font-inter">
                Выйти
            </a>
<button onclick="MobileMenu.toggle()" class="md:hidden text-on-surface-variant hover:text-on-surface transition-colors duration-300">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
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
<a href="profile.php" class="text-primary font-semibold block py-2" onclick="MobileMenu.close()"><?php echo htmlspecialchars($username); ?></a>
<a href="logout.php" class="text-error-dim block py-2">Выйти</a>
</div>
</div>
<!-- Main Layout Grid -->
<main class="flex-1 flex w-full pt-28 pb-12 px-6 lg:px-12 max-w-[1600px] mx-auto gap-8 relative">
<!-- Background Orbs -->
<div class="absolute top-20 -left-40 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
<div class="absolute bottom-40 -right-20 w-[400px] h-[400px] bg-secondary-dim/5 rounded-full blur-[100px] pointer-events-none"></div>
<!-- Sidebar (Glass Panel) -->
<aside class="hidden lg:flex flex-col w-72 bg-surface-variant/30 backdrop-blur-[25px] border border-outline-variant/15 rounded-3xl p-6 h-[calc(100vh-160px)] sticky top-28 shrink-0">
<!-- User Avatar & Info -->
<div class="flex flex-col items-center mb-10 mt-4">
<div class="relative group">
<div class="absolute -inset-1 bg-gradient-to-r from-primary to-secondary-dim rounded-full blur opacity-75 group-hover:opacity-100 transition duration-500"></div>
<img alt="User Avatar" class="relative w-24 h-24 rounded-full object-cover border-2 border-surface bg-surface" data-alt="portrait of a young man with glasses and neutral expression in soft studio lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCuTrtH64bzmZuzgAM032eu0LTmQP6XFRuNWS59826hBiXN01Y18jrDHBKasdb3KlTLKYBGj2uQhaNg-iqaHV_jTX6lsoiDhCG4jV6GB_Xd-HbjLvWddoofSmC0pwwX-n7lUUgMfiEitPkex9PmOKev8-vdfGWz_7WeAsTFZwW6KLDkRuSTRuRnJVtM2G4WBSBwYel0tWdScSfz6_cVAiihvd0J4w49-GG9VkKL7hW-CO6Sh7YuYNqVNhiTHDZ57iUCkLsHFv4Nw9Z"/>
</div>
<h3 class="mt-4 font-bold text-lg text-on-surface"><?php echo htmlspecialchars($username); ?></h3>
<p class="text-sm text-on-surface-variant">Premium Member</p>
</div>
<!-- Navigation -->
<nav class="flex-1 space-y-2">
<a class="flex items-center gap-4 px-4 py-3 rounded-xl bg-primary/10 text-primary border border-primary/20 font-medium transition-all" href="profile.php">
<span class="material-symbols-outlined">dashboard</span>
                    Дашборд
                </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all group" href="profile.php#orders">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">history</span>
                    История заказов
                </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all group" href="profile.php#settings">
<span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                    Настройки
                </a>
</nav>
<!-- Bottom Action -->
<div class="mt-auto">
<?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-primary hover:bg-primary/10 transition-all" href="admin.php">
<span class="material-symbols-outlined">admin_panel_settings</span>
                    Админ-панель
                </a>
<?php endif; ?>
<a class="flex items-center gap-4 px-4 py-3 rounded-xl text-error-dim hover:text-error hover:bg-error/10 transition-all" href="logout.php">
<span class="material-symbols-outlined">logout</span>
                    Выйти
                </a>
</div>
</aside>
<!-- Main Content Area -->
<div class="flex-1 flex flex-col gap-10 min-w-0">
<!-- Header -->
<header>
<h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-on-surface to-on-surface-variant">
                    Добро пожаловать, <?php echo htmlspecialchars($username); ?>
                </h1>
<p class="mt-2 text-on-surface-variant text-lg">Обзор вашей активности в The Ethereal Exchange.</p>
</header>
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Stat Card 1 -->
<div class="bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-surface-container-highest transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-primary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Всего потрачено</div>
<span class="material-symbols-outlined text-primary">account_balance_wallet</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-on-surface"><?php echo '$' . number_format($total_spent, 2); ?></span>
</div>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-surface-container-highest transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-secondary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-secondary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Активные заказы</div>
<span class="material-symbols-outlined text-secondary">autorenew</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-on-surface"><?php echo $active_orders; ?></span>
</div>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-6 relative overflow-hidden group hover:scale-[1.02] hover:bg-surface-container-highest transition-all duration-300">
<div class="absolute top-0 right-0 w-32 h-32 bg-tertiary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-tertiary/20 transition-colors"></div>
<div class="flex justify-between items-start relative z-10">
<div class="text-on-surface-variant font-medium text-sm">Всего заказов</div>
<span class="material-symbols-outlined text-tertiary">inventory_2</span>
</div>
<div class="mt-4 flex items-baseline gap-2 relative z-10">
<span class="text-3xl font-bold text-on-surface"><?php echo $total_orders; ?></span>
</div>
</div>
</div>
<!-- Orders Table Section -->
<section class="flex flex-col gap-6">
<div class="flex justify-between items-center">
<h2 class="text-2xl font-bold text-on-surface tracking-tight">Недавние транзакции</h2>
<button class="text-primary text-sm font-medium hover:text-on-surface transition-colors">Смотреть все</button>
</div>
<div class="bg-surface-container-low/80 backdrop-blur-[25px] border border-outline-variant/15 rounded-3xl overflow-hidden shadow-[0_20px_40px_rgba(0,0,0,0.4)]">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-highest/50 border-b border-outline-variant/20">
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">ID Заказа</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Услуга</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Цена</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Статус</th>
<th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Дата</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
<?php if (empty($orders)): ?>
<tr>
<td class="px-6 py-8 text-sm text-on-surface-variant text-center" colspan="5">У вас пока нет заказов</td>
</tr>
<?php else: ?>
<?php foreach ($orders as $order): ?>
<tr class="hover:bg-surface-variant/20 transition-colors duration-200 group">
<td class="px-6 py-5 text-sm font-medium text-on-surface"><?php echo htmlspecialchars($order['order_code'] ?? '#' . $order['id']); ?></td>
<td class="px-6 py-5 text-sm text-on-surface-variant group-hover:text-on-surface transition-colors"><?php echo htmlspecialchars($order['service_name']); ?></td>
<td class="px-6 py-5 text-sm font-bold text-primary">$<?php echo number_format($order['price'], 2); ?></td>
<td class="px-6 py-5">
<?php
    $status = $order['status'];
    $status_config = [
        'pending'  => ['bg' => 'bg-tertiary-container/20', 'text' => 'text-tertiary', 'border' => 'border-tertiary/20', 'dot' => 'bg-tertiary animate-pulse', 'label' => 'Ожидает'],
        'active'   => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'border' => 'border-primary/20', 'dot' => 'bg-primary shadow-[0_0_8px_var(--color-primary)]', 'label' => 'Активен'],
        'completed' => ['bg' => 'bg-surface-variant/50', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30', 'dot' => 'bg-on-surface-variant', 'label' => 'Завершен'],
        'cancelled' => ['bg' => 'bg-error/10', 'text' => 'text-error', 'border' => 'border-error/20', 'dot' => 'bg-error', 'label' => 'Отменён'],
    ];
    $cfg = $status_config[$status] ?? $status_config['pending'];
?>
<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium <?php echo $cfg['bg']; ?> <?php echo $cfg['text']; ?> border <?php echo $cfg['border']; ?>">
<span class="w-1.5 h-1.5 rounded-full <?php echo $cfg['dot']; ?>"></span>
                                            <?php echo $cfg['label']; ?>
                                        </span>
</td>
<td class="px-6 py-5 text-sm text-on-surface-variant text-right"><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
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