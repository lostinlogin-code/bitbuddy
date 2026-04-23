<?php
session_start();
require_once 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Заполните все поля.';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Имя пользователя должно быть от 3 до 50 символов.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email адрес.';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен быть не менее 6 символов.';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = 'Пользователь с таким email или именем уже существует.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hashed]);
            $success = 'Регистрация успешна! Теперь вы можете войти.';
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
<title>BitBuddy - Регистрация</title>
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
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-x-hidden">
<!-- Ambient Floating Orbs -->
<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
<div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/5 blur-[120px] mix-blend-screen"></div>
<div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-dim/5 blur-[150px] mix-blend-screen"></div>
</div>
<!-- Top Navigation -->
<header class="fixed top-0 w-full z-50 flex justify-between items-center px-8 h-20 max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 focus:outline-none" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 1;">dark_mode</span>
</button>
</header>
<!-- Main Content -->
<main class="flex-grow flex items-center justify-center p-6 relative z-10">
<!-- Glassmorphic Auth Card -->
<div class="w-full max-w-md bg-surface-container-highest/60 backdrop-blur-[25px] rounded-2xl p-8 shadow-[0_40px_80px_rgba(105,218,255,0.08)] border border-outline-variant/15 relative overflow-hidden">
<!-- Subtle Inner Glow -->
<div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-2 bg-gradient-to-r from-transparent via-primary/30 to-transparent blur-sm"></div>
<!-- Header -->
<div class="text-center mb-8">
<h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Создать аккаунт</h1>
<p class="text-on-surface-variant text-sm">Присоединяйтесь к BitBuddy — The Ethereal Exchange.</p>
</div>
<!-- Tab Switcher -->
<div class="flex p-1 bg-surface-container-low rounded-lg mb-8 relative">
<div class="absolute inset-y-1 left-1 w-[calc(50%-4px)] bg-surface-variant/80 backdrop-blur-md border border-outline-variant/20 rounded-md shadow-sm pointer-events-none transition-transform duration-300" id="tab-indicator"></div>
<a class="flex-1 py-2 text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors duration-300 z-10 relative focus:outline-none text-center" href="login.php">
                    Вход
                </a>
<button class="flex-1 py-2 text-sm font-semibold text-primary z-10 relative focus:outline-none">
                    Регистрация
                </button>
</div>
<!-- Error / Success Messages -->
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
<!-- Form -->
<form class="space-y-6" method="POST" action="register.php">
<!-- Username Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="username">Имя пользователя</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">person</span>
<input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-4 py-3 rounded-xl border-b border-outline-variant/20 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="username" name="username" placeholder="username" type="text" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"/>
</div>
</div>
<!-- Email Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="email">Email</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
<input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-4 py-3 rounded-xl border-b border-outline-variant/20 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="email" name="email" placeholder="name@example.com" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
</div>
</div>
<!-- Password Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="password">Пароль</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
<input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-12 py-3 rounded-xl border-b border-outline-variant/20 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="password" name="password" placeholder="••••••••" type="password" required/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors focus:outline-none" type="button" onclick="togglePassword('password', this)">
<span class="material-symbols-outlined text-[20px]">visibility_off</span>
</button>
</div>
</div>
<!-- Password Confirm Field -->
<div>
<label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="password_confirm">Подтвердите пароль</label>
<div class="relative">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
<input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-12 py-3 rounded-xl border-b border-outline-variant/20 focus:border-primary focus:ring-0 focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="password_confirm" name="password_confirm" placeholder="••••••••" type="password" required/>
<button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors focus:outline-none" type="button" onclick="togglePassword('password_confirm', this)">
<span class="material-symbols-outlined text-[20px]">visibility_off</span>
</button>
</div>
</div>
<!-- Submit Button -->
<button class="w-full py-3.5 px-6 bg-gradient-to-r from-primary to-primary-container text-on-primary font-bold rounded-xl shadow-[0_0_20px_rgba(105,218,255,0.3)] hover:shadow-[0_0_30px_rgba(105,218,255,0.6)] transition-all duration-300 transform hover:-translate-y-0.5" type="submit">
                    Зарегистрироваться
                </button>
</form>
<!-- Link to Login -->
<div class="mt-6 text-center text-sm text-on-surface-variant">
                Уже есть аккаунт? <a class="text-primary hover:text-primary-dim transition-colors" href="login.php">Войти</a>
</div>
</div>
</main>
<!-- Footer -->
<footer class="w-full relative bottom-0 bg-[#0e0e0e] border-t border-[#69daff]/10 shadow-[0_-10px_40px_rgba(105,218,255,0.02)] z-20">
<div class="max-w-7xl mx-auto px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-4">
<div class="text-lg font-black text-white/50 tracking-tighter">BitBuddy</div>
<div class="font-inter text-xs tracking-wide text-neutral-600">
                © 2024 BitBuddy. The Ethereal Exchange.
            </div>
</div>
</footer>
<script>
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('.material-symbols-outlined');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility_off';
        }
    }
</script>
</body></html>
