<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? '';
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service = null;

if ($service_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.id = ?');
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();
    } catch (Exception $e) {}
}

if (!$service) {
    header('Location: services.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = trim($_POST['notes'] ?? '');
    try {
        $order_code = 'BB-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, service_id, order_code, status, price, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $service_id, $order_code, 'pending', $service['price'], $notes]);
        $success = 'Заказ успешно создан! Код заказа: ' . $order_code;
    } catch (Exception $e) {
        $error = 'Ошибка при создании заказа. Попробуйте позже.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru" data-theme="dark"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/><meta content="width=device-width,initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Оформление заказа</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="theme.css?v=3"/>
<script>
tailwind.config={darkMode:"class",theme:{extend:{colors:{"tertiary-fixed-dim":"var(--color-tertiary-fixed-dim)","on-secondary-fixed":"var(--color-on-secondary-fixed)","surface-container-high":"rgb(var(--rgb-surface-container-high) / <alpha-value>)","on-tertiary":"var(--color-on-tertiary)","surface-container-low":"var(--color-surface-container-low)","primary-container":"var(--color-primary-container)","on-secondary-container":"var(--color-on-secondary-container)","on-primary-fixed":"var(--color-on-primary-fixed)","tertiary-container":"var(--color-tertiary-container)","tertiary-fixed":"var(--color-tertiary-fixed)","inverse-primary":"var(--color-inverse-primary)","on-primary-fixed-variant":"var(--color-on-primary-fixed-variant)","on-tertiary-fixed":"var(--color-on-tertiary-fixed)","error-container":"var(--color-error-container)","error-dim":"var(--color-error-dim)","background":"var(--color-background)","on-surface-variant":"var(--color-on-surface-variant)","inverse-surface":"var(--color-inverse-surface)","error":"var(--color-error)","surface-container-highest":"var(--color-surface-container-highest)","on-secondary":"var(--color-on-secondary)","surface-variant":"rgb(var(--rgb-surface-variant) / <alpha-value>)","surface-container-lowest":"var(--color-surface-container-lowest)","on-error":"var(--color-on-error)","secondary-container":"var(--color-secondary-container)","tertiary-dim":"rgb(var(--rgb-tertiary-dim) / <alpha-value>)","on-background":"var(--color-on-background)","outline":"var(--color-outline)","on-tertiary-container":"var(--color-on-tertiary-container)","primary":"rgb(var(--rgb-primary) / <alpha-value>)","primary-dim":"var(--color-primary-dim)","on-surface":"var(--color-on-surface)","primary-fixed":"var(--color-primary-fixed)","on-primary-container":"var(--color-on-primary-container)","secondary-dim":"rgb(var(--rgb-secondary-dim) / <alpha-value>)","secondary-fixed":"var(--color-secondary-fixed)","inverse-on-surface":"var(--color-inverse-on-surface)","surface-tint":"var(--color-surface-tint)","on-error-container":"var(--color-on-error-container)","primary-fixed-dim":"var(--color-primary-fixed-dim)","surface-container":"var(--color-surface-container)","surface-bright":"var(--color-surface-bright)","secondary":"var(--color-secondary)","on-secondary-fixed-variant":"var(--color-on-secondary-fixed-variant)","on-tertiary-fixed-variant":"var(--color-on-tertiary-fixed-variant)","outline-variant":"rgb(var(--rgb-outline-variant) / <alpha-value>)","secondary-fixed-dim":"var(--color-secondary-fixed-dim)","tertiary":"var(--color-tertiary)","surface":"var(--color-surface)","on-primary":"var(--color-on-primary)","surface-dim":"var(--color-surface-dim)"},borderRadius:{DEFAULT:"0.25rem",lg:"0.5rem",xl:"0.75rem",full:"9999px"},fontFamily:{headline:["Inter"],body:["Inter"],label:["Inter"]}}}}
</script>
<script src="theme.js"></script>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-x-hidden">
<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
<div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/5 blur-[120px] mix-blend-screen"></div>
<div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-dim/5 blur-[150px] mix-blend-screen"></div>
</div>
<header class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<nav class="hidden md:flex gap-8">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</nav>
<div class="flex items-center gap-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings:'FILL' 1;">dark_mode</span>
</button>
<a href="profile.php" class="text-primary font-semibold hover:opacity-80 transition-all"><?php echo htmlspecialchars($username); ?></a>
</div>
</div>
</header>
<main class="flex-grow flex items-center justify-center p-6 relative z-10 pt-28">
<div class="w-full max-w-lg">
<?php if ($success): ?>
<div class="bg-surface-container-highest/60 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-8 text-center">
<span class="material-symbols-outlined text-6xl text-primary mb-4 block" style="font-variation-settings:'FILL' 1;">check_circle</span>
<h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Заказ оформлен!</h1>
<p class="text-on-surface-variant mb-6"><?php echo htmlspecialchars($success); ?></p>
<div class="flex flex-col sm:flex-row gap-4 justify-center">
<a href="profile.php" class="px-6 py-3 bg-primary text-on-primary font-bold rounded-xl hover:opacity-90 transition-all">Мои заказы</a>
<a href="services.php" class="px-6 py-3 bg-surface-variant text-on-surface font-medium rounded-xl border border-outline-variant/15 hover:bg-surface-container-high transition-all">Каталог услуг</a>
</div>
</div>
<?php else: ?>
<div class="bg-surface-container-highest/60 backdrop-blur-[25px] border border-outline-variant/15 rounded-2xl p-8 relative overflow-hidden">
<div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-2 bg-gradient-to-r from-transparent via-primary/30 to-transparent blur-sm"></div>
<h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Оформление заказа</h1>
<p class="text-on-surface-variant text-sm mb-8">Подтвердите детали вашего заказа.</p>
<?php if ($error): ?>
<div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-sm flex items-center gap-2">
<span class="material-symbols-outlined text-base">error</span>
<?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>
<div class="mb-8 p-6 rounded-xl bg-surface-container-low/50 border border-outline-variant/10">
<div class="flex items-start gap-4">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center border border-primary/20 shrink-0">
<span class="material-symbols-outlined text-primary">shopping_cart</span>
</div>
<div class="flex-1">
<h3 class="text-lg font-bold text-on-surface"><?php echo htmlspecialchars($service['title']); ?></h3>
<p class="text-on-surface-variant text-sm mt-1"><?php echo htmlspecialchars($service['category_name']); ?></p>
</div>
<div class="text-right">
<span class="text-2xl font-bold text-primary">₽<?php echo number_format($service['price'], 0, '', ' '); ?></span>
</div>
</div>
<div class="mt-4 pt-4 border-t border-outline-variant/10 grid grid-cols-2 gap-4 text-sm">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-base">schedule</span>Срок: от <?php echo $service['delivery_days']; ?> дн.
</div>
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-base">support_agent</span>Поддержка: <?php echo $service['support_days']; ?> дн.
</div>
</div>
</div>
<form method="POST" action="order.php?id=<?php echo $service_id; ?>">
<div class="mb-6">
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="notes">Комментарий к заказу</label>
<textarea class="w-full bg-surface-container-lowest text-on-surface p-4 rounded-xl border-b border-outline-variant/20 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50 resize-none min-h-[100px]" id="notes" name="notes" placeholder="Опишите ваши пожелания или требования к заказу..."></textarea>
</div>
<button class="w-full py-3.5 px-6 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-[0_0_20px_rgba(105,218,255,0.3)] hover:shadow-[0_0_30px_rgba(105,218,255,0.6)] transition-all duration-300 transform hover:-translate-y-0.5" type="submit">
Подтвердить заказ
</button>
</form>
<div class="mt-6 text-center">
<a class="text-on-surface-variant hover:text-primary text-sm transition-colors" href="service.php?id=<?php echo $service_id; ?>">← Вернуться к услуге</a>
</div>
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
</body></html>
