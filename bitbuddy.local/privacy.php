<?php
session_start();

$is_logged_in = isset($_SESSION['user_id']);
$username     = $_SESSION['username'] ?? '';

$page_title  = 'BitBuddy — Политика конфиденциальности';
$active_page = null;
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col overflow-x-hidden relative">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[50vw] h-[50vw] top-[-10%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[40vw] h-[40vw] bottom-[-10%] right-[-10%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="relative z-10 flex-grow pt-32 pb-20 px-6 md:px-12 lg:px-24 max-w-4xl mx-auto w-full animate-fade-in-up">
        <header class="mb-12">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel ghost-border text-xs uppercase tracking-[0.2em] text-on-surface-variant mb-6">
                <span class="material-symbols-outlined text-primary text-sm">shield_lock</span>
                Конфиденциальность
            </span>
            <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight text-gradient leading-tight mb-4">
                Политика конфиденциальности
            </h1>
            <p class="text-on-surface-variant text-lg">
                Мы уважаем вашу приватность. Ниже описано, какие данные мы собираем и зачем.
            </p>
        </header>

        <article class="glass-panel ghost-border rounded-2xl p-8 md:p-10 space-y-8 shadow-card">
            <section class="space-y-3">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">info</span>
                    </span>
                    Какие данные мы собираем
                </h2>
                <ul class="list-disc pl-6 text-on-surface-variant leading-relaxed space-y-1">
                    <li>Имя и e-mail — при регистрации и отправке формы обратной связи.</li>
                    <li>Информацию о заказанных услугах.</li>
                    <li>Технические данные (IP, user-agent, cookies) — для защиты сессий и аналитики.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-secondary-dim/10 text-secondary-dim flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">sync_lock</span>
                    </span>
                    Зачем
                </h2>
                <p class="text-on-surface-variant leading-relaxed">
                    Исключительно чтобы выполнять заказы, связываться с вами по делу и поддерживать работу сайта.
                    Мы не продаём персональные данные третьим лицам.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-tertiary-dim/10 text-tertiary-dim flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">verified_user</span>
                    </span>
                    Ваши права
                </h2>
                <p class="text-on-surface-variant leading-relaxed">
                    Вы можете запросить удаление своего аккаунта и связанных с ним данных в любой момент через
                    <a class="text-primary hover:underline underline-offset-4" href="contacts.php">форму обратной связи</a>.
                </p>
            </section>

            <section class="space-y-3">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-lg">mail</span>
                    </span>
                    Контакты
                </h2>
                <p class="text-on-surface-variant leading-relaxed">
                    По вопросам обработки данных — пишите на страницу <a class="text-primary hover:underline underline-offset-4" href="contacts.php">контактов</a>.
                </p>
            </section>

            <p class="text-xs text-on-surface-variant/70 pt-4 border-t border-outline-variant/25">
                Последнее обновление: <?php echo date('d.m.Y'); ?>.
            </p>
        </article>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
