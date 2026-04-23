<?php
session_start();
require 'db_connect.php';

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error = '';

// Обработка POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Ошибка безопасности: неверный CSRF токен');
    }
    
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $error = 'Введите логин и пароль';
    } else {
        // Поиск пользователя по username или email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$login, $login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header("Location: profile.php");
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}
?><!DOCTYPE html>

<html lang="ru" data-theme="light"><head>
<script>(function(){const s=localStorage.getItem('bitbuddy-theme');const p=window.matchMedia('(prefers-color-scheme:light)').matches;const t=s||(p?'light':'dark');document.documentElement.setAttribute('data-theme',t);document.documentElement.style.colorScheme=t;})();</script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BitBuddy - Вход / Регистрация</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
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
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-x-hidden">
<!-- Ambient Floating Orbs -->
<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
<div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/5 blur-[120px] mix-blend-multiply"></div>
<div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-dim/5 blur-[150px] mix-blend-multiply"></div>
</div>
<!-- Top Navigation (Suppressed due to linear/transactional intent) -->
<!-- Content Canvas prioritized for Auth -->
<header class="fixed top-0 w-full z-50 flex justify-between items-center px-8 h-20 max-w-none">
<div class="text-2xl font-extrabold tracking-tighter text-slate-900">BitBuddy</div>
<button onclick="location.href='login.php'" class="text-slate-500 hover:text-slate-900 transition-colors duration-300 focus:outline-none" title="Переключить на темную тему">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">light_mode</span>
</button>
</header>
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center p-6 relative z-10">
<!-- Glassmorphic Auth Card -->
<div class="w-full max-w-md bg-white/70 backdrop-blur-[25px] rounded-2xl p-8 shadow-[0_20px_40px_rgba(14,165,233,0.08)] border border-slate-200/50 relative overflow-hidden">
<!-- Subtle Inner Glow -->
<div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-2 bg-gradient-to-r from-transparent via-primary/20 to-transparent blur-sm"></div>
<!-- Header -->
<div class="text-center mb-8">
<h1 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-2">Добро пожаловать</h1>
<p class="text-on-surface-variant text-sm">Войдите, чтобы продолжить работу с BitBuddy.</p>
</div>
<!-- Tab Switcher -->
<div class="flex p-1 bg-slate-100 rounded-lg mb-8 relative">
<!-- Sliding Indicator (Static representation for "Вход") -->
<div class="absolute inset-y-1 left-1 w-[calc(50%-4px)] bg-white backdrop-blur-md border border-slate-200 rounded-md shadow-sm pointer-events-none"></div>
<button class="flex-1 py-2 text-sm font-semibold text-primary z-10 relative focus:outline-none">
                Вход
            </button>
<a href="register_light.php" class="flex-1 py-2 text-sm font-medium text-on-surface-variant hover:text-slate-900 transition-colors duration-300 z-10 relative focus:outline-none text-center">
                Регистрация
            </a>
</div>
<?php if ($error): ?>
<div class="mb-6 p-4 bg-error-container/30 border border-error/30 rounded-xl text-error text-sm text-center">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>
<!-- Form -->
<form method="POST" action="" class="space-y-6">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<!-- Email Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
<input name="login" class="w-full bg-slate-50 text-on-surface pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-slate-400" id="email" placeholder="Имя пользователя или email" type="text" required/>
</div>
</div>
<!-- Password Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="password">Пароль</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
<input name="password" class="w-full bg-slate-50 text-on-surface pl-12 pr-12 py-3 rounded-xl border border-slate-200 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-slate-400" id="password" placeholder="••••••••" type="password" required/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary transition-colors focus:outline-none" type="button">
<span class="material-symbols-outlined text-[20px]">visibility_off</span>
</button>
</div>
</div>
<!-- Meta Actions -->
<div class="flex items-center justify-between text-sm">
<label class="flex items-center cursor-pointer group">
<input class="form-checkbox rounded bg-slate-50 border-slate-300 text-primary focus:ring-primary focus:ring-offset-background h-4 w-4 transition-colors" type="checkbox"/>
<span class="ml-2 text-on-surface-variant group-hover:text-slate-900 transition-colors">Запомнить меня</span>
</label>
<a class="text-primary hover:text-primary-dim transition-colors hover:drop-shadow-[0_0_8px_rgba(14,165,233,0.3)]" href="#">Забыли пароль?</a>
</div>
<!-- Submit Button -->
<button class="w-full py-3.5 px-6 bg-gradient-to-r from-primary to-primary-container text-white font-bold rounded-xl shadow-[0_4px_15px_rgba(14,165,233,0.3)] hover:shadow-[0_6px_20px_rgba(14,165,233,0.4)] transition-all duration-300 transform hover:-translate-y-0.5" type="submit">
                    Войти в систему
                </button>
</form>
<!-- Social/Alternative Auth -->
<div class="mt-8">
<div class="relative flex items-center justify-center mb-6">
<div class="absolute inset-x-0 h-px bg-gradient-to-r from-transparent via-slate-200 to-transparent"></div>
<span class="relative bg-white px-4 text-xs text-on-surface-variant tracking-wider uppercase backdrop-blur-md rounded-full border border-slate-200">или</span>
</div>
<button class="w-full flex items-center justify-center gap-3 py-3 px-6 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 transition-all duration-300">
<span class="material-symbols-outlined text-[20px]">login</span>
                    Продолжить с Google
                </button>
</div>
</div>
</main>
<!-- Footer (Suppressed Nav links, minimal branding for Auth page) -->
<footer class="w-full relative bottom-0 bg-white border-t border-primary/10 shadow-[0_-4px_20px_rgba(14,165,233,0.02)] z-20">
<div class="max-w-7xl mx-auto px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
<div class="text-lg font-black text-slate-900/50 tracking-tighter">BitBuddy</div>
<div class="font-inter text-xs tracking-wide text-slate-400">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
</body></html>
