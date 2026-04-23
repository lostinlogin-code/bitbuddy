<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

// Stats
$user_count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$order_count = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$msg_count = $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn();
$service_count = $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();

// Recent orders
$recent_orders = $pdo->query('SELECT o.*, u.username, s.title as service_title FROM orders o JOIN users u ON o.user_id = u.id JOIN services s ON o.service_id = s.id ORDER BY o.created_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

// Recent messages
$recent_messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

// Mark message as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $stmt = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $stmt->execute([$_GET['read']]);
    header('Location: admin.php');
    exit;
}
?><!DOCTYPE html>
<html lang="ru" data-theme="dark"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Админ-панель</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="theme.css?v=3"/>
<script>
tailwind.config={darkMode:"class",theme:{extend:{colors:{"tertiary-fixed-dim":"var(--color-tertiary-fixed-dim)","on-secondary-fixed":"var(--color-on-secondary-fixed)","surface-container-high":"rgb(var(--rgb-surface-container-high) / <alpha-value>)","on-tertiary":"var(--color-on-tertiary)","surface-container-low":"var(--color-surface-container-low)","primary-container":"var(--color-primary-container)","on-secondary-container":"var(--color-on-secondary-container)","on-primary-fixed":"var(--color-on-primary-fixed)","tertiary-container":"var(--color-tertiary-container)","tertiary-fixed":"var(--color-tertiary-fixed)","inverse-primary":"var(--color-inverse-primary)","on-primary-fixed-variant":"var(--color-on-primary-fixed-variant)","on-tertiary-fixed":"var(--color-on-tertiary-fixed)","error-container":"var(--color-error-container)","error-dim":"var(--color-error-dim)","background":"var(--color-background)","on-surface-variant":"var(--color-on-surface-variant)","inverse-surface":"var(--color-inverse-surface)","on-surface":"var(--color-on-surface)","surface-container-lowest":"var(--color-surface-container-lowest)","surface-container":"var(--color-surface-container)","surface-container-highest":"var(--color-surface-container-highest)","surface-variant":"rgb(var(--rgb-surface-variant) / <alpha-value>)","outline-variant":"rgb(var(--rgb-outline-variant) / <alpha-value>)","outline":"var(--color-outline)","secondary-dim":"rgb(var(--rgb-secondary-dim) / <alpha-value>)","on-background":"var(--color-on-background)","on-primary":"var(--color-on-primary)","primary":"rgb(var(--rgb-primary) / <alpha-value>)","surface":"var(--color-surface)","error":"var(--color-error)","on-error":"var(--color-on-error)","primary-dim":"var(--color-primary-dim)","primary-fixed":"var(--color-primary-fixed)","primary-fixed-dim":"var(--color-primary-fixed-dim)","tertiary-dim":"rgb(var(--rgb-tertiary-dim) / <alpha-value>)"},fontFamily:{"headline":["Inter","sans-serif"],"body":["Inter","sans-serif"],"icon":["Material Symbols Outlined"],"label":["Inter","sans-serif"]}}}}
</script>
<script src="theme.js"></script>
<style>body{font-family:'Inter',sans-serif;}</style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col overflow-x-hidden relative">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<div class="hidden md:flex items-center space-x-8">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">На сайт</a>
<a class="text-[#69daff] font-semibold border-b-2 border-[#69daff] pb-1" href="admin.php">Админ-панель</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="profile.php">Профиль</a>
</div>
<div class="flex items-center space-x-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 focus:outline-none" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon>dark_mode</span>
</button>
<a href="logout.php" class="hidden md:inline text-primary hover:opacity-80 transition-all font-semibold">Выйти</a>
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
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="index.php" onclick="MobileMenu.close()">На сайт</a>
<a class="text-primary font-semibold py-2" href="admin.php" onclick="MobileMenu.close()">Админ-панель</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="profile.php" onclick="MobileMenu.close()">Профиль</a>
<div class="mt-auto pt-6 border-t border-outline-variant/15">
<a href="logout.php" class="text-error-dim block py-2">Выйти</a>
</div>
</div>
<!-- Main Content -->
<main class="relative z-10 pt-28 pb-16 px-6 md:px-12 lg:px-24 max-w-7xl mx-auto w-full flex-grow">
<!-- Header -->
<div class="mb-12">
<h1 class="text-4xl font-bold text-on-surface tracking-tight mb-2">Админ-панель</h1>
<p class="text-on-surface-variant">Добро пожаловать, <span class="text-primary font-semibold"><?php echo htmlspecialchars($username); ?></span></p>
</div>
<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
<div class="stat-card bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 p-6 rounded-2xl flex flex-col items-center justify-center text-center">
<span class="stat-value text-[36px] font-black text-primary mb-1" data-target="<?php echo $user_count; ?>">0</span>
<span class="text-on-surface-variant text-sm">Пользователей</span>
</div>
<div class="stat-card bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 p-6 rounded-2xl flex flex-col items-center justify-center text-center">
<span class="stat-value text-[36px] font-black text-primary mb-1" data-target="<?php echo $order_count; ?>">0</span>
<span class="text-on-surface-variant text-sm">Заказов</span>
</div>
<div class="stat-card bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 p-6 rounded-2xl flex flex-col items-center justify-center text-center">
<span class="stat-value text-[36px] font-black text-primary mb-1" data-target="<?php echo $msg_count; ?>">0</span>
<span class="text-on-surface-variant text-sm">Новых сообщений</span>
</div>
<div class="stat-card bg-surface-container-high/60 backdrop-blur-[25px] border border-outline-variant/15 p-6 rounded-2xl flex flex-col items-center justify-center text-center">
<span class="stat-value text-[36px] font-black text-primary mb-1" data-target="<?php echo $service_count; ?>">0</span>
<span class="text-on-surface-variant text-sm">Услуг</span>
</div>
</div>
<!-- Recent Orders -->
<div class="bg-surface-container-low/80 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-8 mb-8">
<div class="flex items-center justify-between mb-6">
<h2 class="text-2xl font-bold text-on-surface">Последние заказы</h2>
<span class="material-symbols-outlined text-primary text-2xl">receipt_long</span>
</div>
<?php if (empty($recent_orders)): ?>
<p class="text-on-surface-variant text-center py-8">Заказов пока нет</p>
<?php else: ?>
<div class="overflow-x-auto">
<table class="w-full text-sm">
<thead>
<tr class="border-b border-outline-variant/20 text-on-surface-variant">
<th class="pb-3 text-left font-medium">Код</th>
<th class="pb-3 text-left font-medium">Клиент</th>
<th class="pb-3 text-left font-medium">Услуга</th>
<th class="pb-3 text-left font-medium">Цена</th>
<th class="pb-3 text-left font-medium">Статус</th>
<th class="pb-3 text-left font-medium">Дата</th>
</tr>
</thead>
<tbody>
<?php foreach ($recent_orders as $o): ?>
<tr class="border-b border-outline-variant/10 hover:bg-on-surface/5 transition-colors">
<td class="py-3 text-primary font-mono font-semibold"><?php echo htmlspecialchars($o['order_code']); ?></td>
<td class="py-3 text-on-surface"><?php echo htmlspecialchars($o['username']); ?></td>
<td class="py-3 text-on-surface-variant"><?php echo htmlspecialchars($o['service_title']); ?></td>
<td class="py-3 text-on-surface font-semibold">₽<?php echo number_format($o['price'], 0, '', ' '); ?></td>
<td class="py-3">
<?php
$status_colors = [
    'pending' => 'bg-primary/10 text-primary border-primary/20',
    'active' => 'bg-secondary-dim/10 text-secondary-dim border-secondary-dim/20',
    'completed' => 'bg-green-500/10 text-green-400 border-green-500/20',
    'cancelled' => 'bg-error/10 text-error-dim border-error/20'
];
$status_names = ['pending' => 'Ожидает', 'active' => 'Активен', 'completed' => 'Завершён', 'cancelled' => 'Отменён'];
$sc = $status_colors[$o['status']] ?? $status_colors['pending'];
$sn = $status_names[$o['status']] ?? $o['status'];
?>
<span class="px-2 py-1 rounded-full text-xs font-medium border <?php echo $sc; ?>"><?php echo $sn; ?></span>
</td>
<td class="py-3 text-on-surface-variant text-xs"><?php echo date('d.m.Y H:i', strtotime($o['created_at'])); ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endif; ?>
</div>
<!-- Recent Messages -->
<div class="bg-surface-container-low/80 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-8">
<div class="flex items-center justify-between mb-6">
<h2 class="text-2xl font-bold text-on-surface">Сообщения</h2>
<span class="material-symbols-outlined text-primary text-2xl">mail</span>
</div>
<?php if (empty($recent_messages)): ?>
<p class="text-on-surface-variant text-center py-8">Сообщений пока нет</p>
<?php else: ?>
<div class="space-y-4">
<?php foreach ($recent_messages as $m): ?>
<div class="p-4 rounded-xl bg-surface-container/50 border border-outline-variant/10 <?php echo !$m['is_read'] ? 'border-l-4 border-l-primary' : ''; ?>">
<div class="flex items-start justify-between gap-4">
<div class="flex-1 min-w-0">
<div class="flex items-center gap-2 mb-1">
<span class="font-semibold text-on-surface"><?php echo htmlspecialchars($m['name']); ?></span>
<span class="text-on-surface-variant text-xs"><?php echo htmlspecialchars($m['email']); ?></span>
<?php if (!$m['is_read']): ?>
<span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium border border-primary/20">Новое</span>
<?php endif; ?>
</div>
<p class="text-on-surface-variant text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
<span class="text-on-surface-variant/50 text-xs mt-2 block"><?php echo date('d.m.Y H:i', strtotime($m['created_at'])); ?></span>
</div>
<?php if (!$m['is_read']): ?>
<a href="admin.php?read=<?php echo $m['id']; ?>" class="shrink-0 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-medium border border-primary/20 hover:bg-primary/20 transition-colors">Прочитать</a>
<?php endif; ?>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
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
<script>
const MobileMenu = {
    toggle() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        if (!menu) return;
        const isOpen = !menu.classList.contains('translate-x-full');
        if (isOpen) { this.close(); } else {
            menu.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
        }
    },
    close() {
        const menu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-overlay');
        if (menu) menu.classList.add('translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    }
};
</script>
</body>
</html>
