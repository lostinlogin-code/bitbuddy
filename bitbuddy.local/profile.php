<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id  = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Пользователь';

$orders = [];
try {
    $stmt = $pdo->prepare('SELECT o.*, s.title as service_name, s.price FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC');
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {
    // table might be missing
}

$total_spent   = array_sum(array_column($orders, 'price'));
$active_orders = count(array_filter($orders, fn($o) => $o['status'] === 'active'));
$total_orders  = count($orders);

$page_title  = 'BitBuddy — Личный кабинет';
$active_page = 'profile';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col overflow-x-hidden relative">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[50vw] h-[50vw] top-[-15%] right-[-10%] animate-float"></div>
        <div class="ambient-orb w-[40vw] h-[40vw] bottom-[-10%] left-[-10%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="relative z-10 flex-grow pt-32 pb-16 px-6 md:px-8 max-w-[1600px] mx-auto w-full">
        <div class="grid lg:grid-cols-[280px_1fr] gap-10">
            <!-- Sidebar -->
            <aside class="glass-panel rounded-2xl p-6 flex flex-col h-max lg:sticky lg:top-28 animate-slide-left">
                <div class="flex items-center gap-3 px-3 py-3 mb-6 border-b border-outline-variant/20 pb-6">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary-fixed-dim flex items-center justify-center text-on-primary font-bold text-xl">
                        <?php echo strtoupper(mb_substr($username, 0, 1)); ?>
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-on-surface truncate"><?php echo htmlspecialchars($username); ?></div>
                        <div class="text-xs text-on-surface-variant"><?php echo ($_SESSION['role'] ?? '') === 'admin' ? 'Администратор' : 'Клиент'; ?></div>
                    </div>
                </div>
                <nav class="flex flex-col gap-1">
                    <a class="flex items-center gap-4 px-4 py-3 rounded-xl bg-primary/10 text-primary font-semibold" href="#overview">
                        <span class="material-symbols-outlined">dashboard</span>Обзор
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="#orders">
                        <span class="material-symbols-outlined">history</span>История заказов
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="services.php">
                        <span class="material-symbols-outlined">grid_view</span>Услуги
                    </a>
                </nav>
                <div class="mt-auto pt-6 border-t border-outline-variant/20">
                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                        <a class="flex items-center gap-4 px-4 py-3 rounded-xl text-primary hover:bg-primary/10 transition-all" href="admin.php">
                            <span class="material-symbols-outlined">admin_panel_settings</span>Админ-панель
                        </a>
                    <?php endif; ?>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-xl text-error-dim hover:text-error hover:bg-error/10 transition-all" href="logout.php">
                        <span class="material-symbols-outlined">logout</span>Выйти
                    </a>
                </div>
            </aside>

            <!-- Main area -->
            <div class="flex flex-col gap-10 min-w-0">
                <header class="animate-fade-in-up" id="overview">
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-on-surface to-on-surface-variant">
                        Добро пожаловать, <?php echo htmlspecialchars($username); ?>
                    </h1>
                    <p class="mt-2 text-on-surface-variant text-lg">Обзор вашей активности в The Ethereal Exchange.</p>
                </header>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    $stat_cards = [
                        ['label' => 'Всего потрачено', 'value' => '$' . number_format($total_spent, 2), 'icon' => 'account_balance_wallet', 'tint' => 'primary'],
                        ['label' => 'Активные заказы', 'value' => $active_orders,                        'icon' => 'autorenew',              'tint' => 'secondary'],
                        ['label' => 'Всего заказов',   'value' => $total_orders,                         'icon' => 'inventory_2',           'tint' => 'tertiary'],
                    ];
                    foreach ($stat_cards as $s): ?>
                        <div class="card-animate glass-panel rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300">
                            <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-2xl -mr-10 -mt-10 transition-colors bg-<?php echo $s['tint']; ?>/10 group-hover:bg-<?php echo $s['tint']; ?>/20"></div>
                            <div class="flex justify-between items-start relative z-10">
                                <div class="text-on-surface-variant font-medium text-sm"><?php echo $s['label']; ?></div>
                                <span class="material-symbols-outlined text-<?php echo $s['tint']; ?>" style="font-variation-settings:'FILL' 1"><?php echo $s['icon']; ?></span>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2 relative z-10">
                                <span class="text-3xl font-bold text-on-surface"><?php echo htmlspecialchars((string)$s['value']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Orders Table -->
                <section class="flex flex-col gap-6" id="orders">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-on-surface">Последние заказы</h2>
                            <p class="text-on-surface-variant mt-1">Хронология ваших транзакций на платформе.</p>
                        </div>
                        <a href="services.php" class="glow-button self-start md:self-auto inline-flex items-center gap-2 bg-primary text-on-primary font-semibold px-5 py-3 rounded-xl">
                            <span class="material-symbols-outlined text-lg">add</span>Новый заказ
                        </a>
                    </div>

                    <div class="glass-panel rounded-2xl overflow-hidden shadow-card">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-highest/60 border-b border-outline-variant/25">
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">ID заказа</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Услуга</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Цена</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Статус</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Дата</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/15">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td class="px-6 py-8 text-sm text-on-surface-variant text-center" colspan="5">У вас пока нет заказов</td>
                                    </tr>
                                <?php else: foreach ($orders as $order):
                                    $status_config = [
                                        'pending'   => ['bg' => 'bg-tertiary/10',   'text' => 'text-tertiary',          'border' => 'border-tertiary/25',         'dot' => 'bg-tertiary animate-pulse',                             'label' => 'Ожидает'],
                                        'active'    => ['bg' => 'bg-primary/10',    'text' => 'text-primary',           'border' => 'border-primary/25',          'dot' => 'bg-primary shadow-[0_0_8px_rgba(var(--rgb-primary)/0.7)]','label' => 'Активен'],
                                        'completed' => ['bg' => 'bg-surface-variant','text' => 'text-on-surface-variant','border' => 'border-outline-variant/30', 'dot' => 'bg-on-surface-variant',                                  'label' => 'Завершён'],
                                        'cancelled' => ['bg' => 'bg-error/10',      'text' => 'text-error',             'border' => 'border-error/25',            'dot' => 'bg-error',                                                'label' => 'Отменён'],
                                    ];
                                    $cfg = $status_config[$order['status']] ?? $status_config['pending'];
                                ?>
                                    <tr class="hover:bg-surface-variant/20 transition-colors duration-200 group">
                                        <td class="px-6 py-5 text-sm font-medium text-on-surface"><?php echo htmlspecialchars($order['order_code'] ?? '#' . $order['id']); ?></td>
                                        <td class="px-6 py-5 text-sm text-on-surface-variant group-hover:text-on-surface transition-colors"><?php echo htmlspecialchars($order['service_name']); ?></td>
                                        <td class="px-6 py-5 text-sm font-bold text-primary">$<?php echo number_format($order['price'], 2); ?></td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border <?php echo $cfg['bg'] . ' ' . $cfg['text'] . ' ' . $cfg['border']; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $cfg['dot']; ?>"></span>
                                                <?php echo $cfg['label']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-on-surface-variant text-right"><?php echo date('d.m.Y', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
