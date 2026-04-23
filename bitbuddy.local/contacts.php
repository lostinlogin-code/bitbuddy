<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Заполните все поля.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email адрес.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $message]);
            $success = 'Сообщение отправлено! Мы ответим в кратчайшие сроки.';
        } catch (Exception $e) {
            $success = 'Сообщение получено! Мы свяжемся с вами в ближайшее время.';
        }
    }
}
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
<title>BitBuddy - Контакты</title>
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
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased selection:bg-primary/30 selection:text-primary">
<!-- Glowing Orb Background -->
<div class="fixed top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] pointer-events-none -z-10 translate-x-1/3 -translate-y-1/3"></div>
<div class="fixed bottom-0 left-0 w-[600px] h-[600px] bg-secondary-dim/5 rounded-full blur-[100px] pointer-events-none -z-10 -translate-x-1/3 translate-y-1/3"></div>
<!-- TopNavBar Component -->
<header class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<nav class="hidden md:flex gap-8">
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-[#69daff] font-semibold border-b-2 border-[#69daff] pb-1" href="contacts.php">Контакты</a>
</nav>
<div class="flex items-center gap-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 0;">dark_mode</span>
</button>
<?php if ($is_logged_in): ?>
<a href="profile.php" class="text-primary font-semibold hover:opacity-80 transition-all duration-300 hidden md:block"><?php echo htmlspecialchars($username); ?></a>
<?php else: ?>
<a href="login.php" class="text-primary font-semibold hover:opacity-80 transition-all duration-300 hidden md:block">Войти</a>
<?php endif; ?>
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
<!-- Main Content -->
<main class="flex-grow pt-32 pb-24 px-6 md:px-12 lg:px-24 max-w-none">
<!-- Hero Header -->
<div class="mb-16 md:mb-24 md:ml-12 lg:ml-24 max-w-3xl">
<h1 class="font-headline font-black text-5xl md:text-7xl tracking-tighter mb-6 bg-gradient-to-r from-primary to-primary-container bg-clip-text text-transparent pb-2">Свяжитесь с нами</h1>
<p class="font-body text-on-surface-variant text-lg md:text-xl leading-relaxed max-w-2xl">Готовы обсудить ваш следующий проект или нужна помощь? Оставьте сообщение, и мы ответим в кратчайшие сроки.</p>
</div>
<!-- 2-Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 relative z-10 max-w-[1600px] mx-auto">
<!-- Left: Glass Form -->
<div class="lg:col-span-7 xl:col-span-6">
<div class="glass-panel rounded-xl p-8 md:p-12 box-glow">
<?php if ($error): ?>
<div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-sm flex items-center gap-2">
<span class="material-symbols-outlined text-base">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
<?php endif; ?>
<?php if ($success): ?>
<div class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/30 text-primary text-sm flex items-center gap-2">
<span class="material-symbols-outlined text-base">check_circle</span>
                    <?php echo htmlspecialchars($success); ?>
                </div>
<?php endif; ?>
<form class="space-y-8 flex flex-col h-full" method="POST" action="contacts.php">
<div class="space-y-2">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="name">Имя</label>
<input class="w-full bg-surface-container-lowest border-b border-outline-variant/30 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-on-surface-variant/50" id="name" name="name" placeholder="Как к вам обращаться?" type="text" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"/>
</div>
<div class="space-y-2">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="email">Email</label>
<input class="w-full bg-surface-container-lowest border-b border-outline-variant/30 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-on-surface-variant/50" id="email" name="email" placeholder="Ваш электронный адрес" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
</div>
<div class="space-y-2 flex-grow">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="message">Сообщение</label>
<textarea class="w-full bg-surface-container-lowest border-b border-outline-variant/30 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-on-surface-variant/50 resize-none h-full min-h-[150px]" id="message" name="message" placeholder="Расскажите нам о вашей задаче..." rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
</div>
<!-- Captcha Placeholder -->
<div class="flex items-center gap-4 py-2 border-l-2 border-primary/30 pl-4 bg-surface-container-highest/20 rounded-r-lg">
<div class="w-6 h-6 border-2 border-outline-variant rounded flex items-center justify-center">
<span class="material-symbols-outlined text-primary text-sm opacity-0">check</span>
</div>
<span class="text-sm text-on-surface-variant font-body">Я не робот</span>
</div>
<button class="mt-8 bg-primary text-on-primary font-bold py-4 px-8 rounded-lg w-full flex items-center justify-center gap-3 hover-box-glow transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:-translate-y-1" type="submit">
<span>Отправить</span>
<span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">send</span>
</button>
</form>
</div>
</div>
<!-- Right: Info Cards & Socials -->
<div class="lg:col-span-5 xl:col-span-6 flex flex-col gap-8">
<!-- Info Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
<!-- Email Card -->
<div class="glass-panel p-8 rounded-xl flex items-start gap-6 hover:scale-[1.02] transition-transform duration-300">
<div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0 border border-outline-variant/20 shadow-[0_0_15px_rgba(105,218,255,0.1)]">
<span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 0;">mail</span>
</div>
<div>
<h3 class="font-headline font-bold text-lg text-on-surface mb-2">Написать нам</h3>
<a class="text-primary hover:text-primary-dim transition-colors break-all" href="mailto:hello@bitbuddy.exchange">hello@bitbuddy.exchange</a>
<p class="text-on-surface-variant text-sm mt-2">Отвечаем в течение 24 часов.</p>
</div>
</div>
<!-- Phone Card -->
<div class="glass-panel p-8 rounded-xl flex items-start gap-6 hover:scale-[1.02] transition-transform duration-300">
<div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0 border border-outline-variant/20 shadow-[0_0_15px_rgba(105,218,255,0.1)]">
<span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 0;">call</span>
</div>
<div>
<h3 class="font-headline font-bold text-lg text-on-surface mb-2">Позвонить</h3>
<a class="text-primary hover:text-primary-dim transition-colors block text-xl font-medium tracking-tight" href="tel:+1234567890">+1 (234) 567-890</a>
<p class="text-on-surface-variant text-sm mt-2">Пн-Пт с 9:00 до 18:00 (UTC+3)</p>
</div>
</div>
<!-- Location Card -->
<div class="glass-panel p-8 rounded-xl flex items-start gap-6 hover:scale-[1.02] transition-transform duration-300">
<div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0 border border-outline-variant/20 shadow-[0_0_15px_rgba(105,218,255,0.1)]">
<span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 0;">location_on</span>
</div>
<div>
<h3 class="font-headline font-bold text-lg text-on-surface mb-2">Офис</h3>
<p class="text-on-surface text-base leading-relaxed">Кибер-пространство Ethereal<br/>Ул. Неоновая, д. 42<br/>Сектор 7G</p>
</div>
</div>
</div>
<!-- Socials -->
<div class="mt-8">
<h4 class="font-headline font-semibold text-on-surface-variant mb-6 text-sm uppercase tracking-widest">Мы в сетях</h4>
<div class="flex gap-4">
<a class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface hover:text-primary hover:bg-surface-container-highest border border-outline-variant/20 transition-all duration-300 hover:shadow-[0_0_20px_rgba(105,218,255,0.2)]" href="mailto:info@bitbuddy.ru">
<span class="material-symbols-outlined">alternate_email</span>
</a>
<a class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface hover:text-primary hover:bg-surface-container-highest border border-outline-variant/20 transition-all duration-300 hover:shadow-[0_0_20px_rgba(105,218,255,0.2)]" href="tel:+78001234567">
<span class="material-symbols-outlined">forum</span>
</a>
<a class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface hover:text-primary hover:bg-surface-container-highest border border-outline-variant/20 transition-all duration-300 hover:shadow-[0_0_20px_rgba(105,218,255,0.2)]" href="https://t.me/bitbuddy" target="_blank" rel="noopener">
<span class="material-symbols-outlined">public</span>
</a>
</div>
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
<a class="text-[#69daff] underline underline-offset-4 hover:text-white hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="contacts.php">Контакты</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
</body></html>