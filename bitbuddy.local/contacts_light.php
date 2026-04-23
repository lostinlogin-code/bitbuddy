<?php
session_start();
require 'db_connect.php';

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Генерация капчи
if (!isset($_SESSION['captcha_n1'])) {
    $_SESSION['captcha_n1'] = rand(1, 10);
    $_SESSION['captcha_n2'] = rand(1, 10);
}
$n1 = $_SESSION['captcha_n1'];
$n2 = $_SESSION['captcha_n2'];
$captchaAnswer = $n1 + $n2;

$success = '';
$error = '';

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Ошибка безопасности: неверный CSRF токен';
    } elseif (!isset($_POST['captcha']) || (int)$_POST['captcha'] !== $captchaAnswer) {
        $error = 'Неверный ответ на капчу';
        // Перегенерируем капчу при ошибке
        $_SESSION['captcha_n1'] = rand(1, 10);
        $_SESSION['captcha_n2'] = rand(1, 10);
        $n1 = $_SESSION['captcha_n1'];
        $n2 = $_SESSION['captcha_n2'];
        $captchaAnswer = $n1 + $n2;
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            $error = 'Заполните все поля';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Некорректный email';
        } else {
            // Сохраняем сообщение
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
            $stmt->execute([htmlspecialchars($name), $email, htmlspecialchars($message)]);
            
            $success = 'Сообщение успешно отправлено!';
            // Очищаем поля после успешной отправки
            $_SESSION['captcha_n1'] = rand(1, 10);
            $_SESSION['captcha_n2'] = rand(1, 10);
            $n1 = $_SESSION['captcha_n1'];
            $n2 = $_SESSION['captcha_n2'];
            $captchaAnswer = $n1 + $n2;
        }
    }
}
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
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
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col antialiased selection:bg-primary/20 selection:text-primary">
<!-- Glowing Orb Background -->
<div class="fixed top-0 right-0 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] pointer-events-none -z-10 translate-x-1/3 -translate-y-1/3"></div>
<div class="fixed bottom-0 left-0 w-[600px] h-[600px] bg-secondary-dim/5 rounded-full blur-[100px] pointer-events-none -z-10 -translate-x-1/3 translate-y-1/3"></div>
<!-- TopNavBar Component -->
<header class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-[25px] border-b border-slate-200/50 shadow-[0_4px_20px_0_rgba(0,0,0,0.08)] font-inter tracking-tight antialiased">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<div class="text-2xl font-extrabold tracking-tighter text-slate-900">BitBuddy</div>
<nav class="hidden md:flex gap-8">
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="services_light.php">Услуги</a>
<a class="text-slate-500 hover:text-slate-900 transition-colors duration-300" href="index_light.php">О нас</a>
<a class="text-primary font-semibold border-b-2 border-primary pb-1" href="contacts_light.php">Контакты</a>
</nav>
<div class="flex items-center gap-6">
<button onclick="location.href='contacts.php'" class="text-slate-500 hover:text-slate-900 transition-colors duration-300 hover:opacity-80 transition-all duration-300 active:scale-95 transform transition-transform" title="Переключить на темную тему">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">light_mode</span>
</button>
<a href="login_light.php" class="text-primary font-semibold hover:opacity-80 transition-all duration-300 active:scale-95 transform transition-transform hidden md:block">Войти</a>
</div>
</div>
</header>
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
<form method="POST" action="" class="space-y-8 flex flex-col h-full">
<div class="space-y-2">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="name">Имя</label>
<input name="name" class="w-full bg-slate-50 border border-slate-200 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-slate-400 rounded-lg" id="name" placeholder="Как к вам обращаться?" type="text" required/>
</div>
<div class="space-y-2">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="email">Email</label>
<input name="email" class="w-full bg-slate-50 border border-slate-200 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-slate-400 rounded-lg" id="email" placeholder="Ваш электронный адрес" type="email" required/>
</div>
<div class="space-y-2 flex-grow">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="message">Сообщение</label>
<textarea name="message" class="w-full bg-slate-50 border border-slate-200 text-on-surface p-4 focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent placeholder-slate-400 resize-none h-full min-h-[150px] rounded-lg" id="message" placeholder="Расскажите нам о вашей задаче..." rows="5" required></textarea>
</div>
<?php if ($success): ?>
<div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-xl text-green-600 text-center">
<?= htmlspecialchars($success) ?>
</div>
<?php endif; ?>
<?php if ($error): ?>
<div class="mb-6 p-4 bg-red-100 border border-red-300 rounded-xl text-red-600 text-center">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>
<!-- Captcha -->
<div class="space-y-2">
<label class="font-label text-sm text-on-surface-variant tracking-wide" for="captcha">Капча</label>
<div class="flex items-center gap-4">
<span class="text-on-surface font-medium">Сколько будет <?= $n1 ?> + <?= $n2 ?>?</span>
<input name="captcha" class="w-24 bg-slate-50 border border-slate-200 text-on-surface p-2 text-center focus:outline-none focus:border-primary focus:ring-0 transition-colors bg-transparent rounded-lg" id="captcha" type="number" required/>
</div>
</div>
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<button class="mt-8 bg-primary text-white font-bold py-4 px-8 rounded-lg w-full flex items-center justify-center gap-3 hover-box-glow transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] hover:-translate-y-1" type="submit">
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
<div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-primary shrink-0 border border-slate-200 shadow-[0_0_15px_rgba(14,165,233,0.1)]">
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
<div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-primary shrink-0 border border-slate-200 shadow-[0_0_15px_rgba(14,165,233,0.1)]">
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
<div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-primary shrink-0 border border-slate-200 shadow-[0_0_15px_rgba(14,165,233,0.1)]">
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
<a class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:text-primary hover:bg-slate-200 border border-slate-200 transition-all duration-300 hover:shadow-[0_0_20px_rgba(14,165,233,0.2)]" href="#">
<span class="material-symbols-outlined">alternate_email</span>
</a>
<a class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:text-primary hover:bg-slate-200 border border-slate-200 transition-all duration-300 hover:shadow-[0_0_20px_rgba(14,165,233,0.2)]" href="#">
<span class="material-symbols-outlined">forum</span>
</a>
<a class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:text-primary hover:bg-slate-200 border border-slate-200 transition-all duration-300 hover:shadow-[0_0_20px_rgba(14,165,233,0.2)]" href="#">
<span class="material-symbols-outlined">public</span>
</a>
</div>
</div>
</div>
</div>
</main>
<!-- Footer Component -->
<footer class="w-full relative bottom-0 bg-white border-t border-primary/20 shadow-[0_-4px_20px_rgba(14,165,233,0.05)] font-inter text-sm tracking-wide">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="text-xl font-black text-slate-900 tracking-tighter">BitBuddy</div>
<nav class="flex flex-wrap justify-center gap-6">
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Услуги</a>
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">О нас</a>
<a class="text-primary underline underline-offset-4 hover:text-slate-900 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Контакты</a>
<a class="text-slate-500 hover:text-primary transition-colors duration-300 hover:drop-shadow-[0_0_8px_#0ea5e9] focus:outline-none focus:ring-2 focus:ring-primary" href="#">Политика конфиденциальности</a>
</nav>
<div class="text-primary opacity-80">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
</body></html>
