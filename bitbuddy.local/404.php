<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$username     = $_SESSION['username'] ?? '';
http_response_code(404);

$page_title = 'BitBuddy — 404';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-hidden">

    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="ambient-orb w-[60vw] h-[60vw] top-[-20%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[50vw] h-[50vw] bottom-[-10%] right-[-20%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/auth-header.php'; ?>

    <main class="flex-grow flex items-center justify-center relative z-10 px-4 pt-24 pb-20">
        <div class="relative max-w-2xl w-full text-center animate-scale-in">
            <div class="glass-panel rounded-3xl p-10 md:p-16 shadow-card">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-primary/10 border border-primary/30 mb-8 animate-pulse-glow">
                    <span class="material-symbols-outlined text-6xl text-primary">error</span>
                </div>
                <h1 class="text-7xl md:text-9xl font-black tracking-tighter text-gradient mb-4 leading-none">404</h1>
                <p class="text-2xl md:text-3xl font-bold text-on-surface mb-3">Страница не найдена</p>
                <p class="text-on-surface-variant max-w-md mx-auto mb-10 leading-relaxed">
                    Похоже, путь, по которому вы пошли, растворился в эфире. Давайте вернёмся к началу.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="index.php" class="glow-button inline-flex items-center justify-center gap-2 bg-primary text-on-primary font-bold px-8 py-3.5 rounded-xl hover:-translate-y-0.5">
                        <span class="material-symbols-outlined">home</span>На главную
                    </a>
                    <a href="services.php" class="inline-flex items-center justify-center gap-2 bg-surface-variant/50 text-on-surface border border-outline-variant/30 px-8 py-3.5 rounded-xl hover:bg-surface-variant hover:border-outline-variant/50 transition-all">
                        Каталог услуг
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full border-t border-outline-variant/20 mt-auto relative z-10">
        <div class="max-w-7xl mx-auto px-6 md:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="text-lg font-black text-on-surface-variant tracking-tighter">BitBuddy</div>
            <div class="text-xs tracking-wide text-on-surface-variant/70">© <?php echo date('Y'); ?> BitBuddy. The Ethereal Exchange.</div>
        </div>
    </footer>
</body>
</html>
