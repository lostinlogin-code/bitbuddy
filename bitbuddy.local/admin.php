<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];

$user_count    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$order_count   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$msg_count     = $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn();
$service_count = $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();

$recent_orders   = $pdo->query('SELECT o.*, u.username, s.title as service_title FROM orders o JOIN users u ON o.user_id = u.id JOIN services s ON o.service_id = s.id ORDER BY o.created_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
$recent_messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $stmt = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $stmt->execute([$_GET['read']]);
    header('Location: admin.php');
    exit;
}

$page_title  = 'BitBuddy — Админ-панель';
$active_page = null;
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col overflow-x-hidden relative">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[50vw] h-[50vw] top-[-10%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[60vw] h-[60vw] bottom-[-20%] right-[-10%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="relative z-10 pt-32 pb-16 px-6 md:px-12 lg:px-24 max-w-7xl mx-auto w-full flex-grow">
        <div class="mb-12 animate-fade-in-up">
            <h1 class="text-4xl font-bold text-on-surface tracking-tight mb-2">Админ-панель</h1>
            <p class="text-on-surface-variant">Добро пожаловать, <span class="text-primary font-semibold"><?php echo htmlspecialchars($username); ?></span></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <?php
            $admin_stats = [
                ['value' => $user_count,    'label' => 'Пользователей'],
                ['value' => $order_count,   'label' => 'Заказов'],
                ['value' => $msg_count,     'label' => 'Новых сообщений'],
                ['value' => $service_count, 'label' => 'Услуг'],
            ];
            foreach ($admin_stats as $s): ?>
                <div class="stat-card glass-panel p-6 rounded-2xl flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300">
                    <span class="stat-value text-[36px] font-black text-primary mb-1" data-target="<?php echo (int)$s['value']; ?>">0</span>
                    <span class="text-on-surface-variant text-sm"><?php echo $s['label']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="glass-panel rounded-2xl p-8 mb-8 shadow-card">
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
                            <tr class="border-b border-outline-variant/25 text-on-surface-variant">
                                <th class="pb-3 text-left font-medium">Код</th>
                                <th class="pb-3 text-left font-medium">Клиент</th>
                                <th class="pb-3 text-left font-medium">Услуга</th>
                                <th class="pb-3 text-left font-medium">Цена</th>
                                <th class="pb-3 text-left font-medium">Статус</th>
                                <th class="pb-3 text-left font-medium">Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $o):
                                $status_colors = [
                                    'pending'   => 'bg-tertiary/10    text-tertiary     border-tertiary/25',
                                    'active'    => 'bg-primary/10     text-primary      border-primary/25',
                                    'completed' => 'bg-surface-variant text-on-surface-variant border-outline-variant/30',
                                    'cancelled' => 'bg-error/10       text-error        border-error/25',
                                ];
                                $status_names = ['pending' => 'Ожидает', 'active' => 'Активен', 'completed' => 'Завершён', 'cancelled' => 'Отменён'];
                                $sc = $status_colors[$o['status']] ?? $status_colors['pending'];
                                $sn = $status_names[$o['status']] ?? $o['status'];
                            ?>
                                <tr class="border-b border-outline-variant/15 hover:bg-on-surface/5 transition-colors">
                                    <td class="py-3 text-primary font-mono font-semibold"><?php echo htmlspecialchars($o['order_code']); ?></td>
                                    <td class="py-3 text-on-surface"><?php echo htmlspecialchars($o['username']); ?></td>
                                    <td class="py-3 text-on-surface-variant"><?php echo htmlspecialchars($o['service_title']); ?></td>
                                    <td class="py-3 text-on-surface font-semibold">₽<?php echo number_format($o['price'], 0, '', ' '); ?></td>
                                    <td class="py-3"><span class="px-2 py-1 rounded-full text-xs font-medium border <?php echo $sc; ?>"><?php echo $sn; ?></span></td>
                                    <td class="py-3 text-on-surface-variant text-xs"><?php echo date('d.m.Y H:i', strtotime($o['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="glass-panel rounded-2xl p-8 shadow-card">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-on-surface">Сообщения</h2>
                <span class="material-symbols-outlined text-primary text-2xl">mail</span>
            </div>
            <?php if (empty($recent_messages)): ?>
                <p class="text-on-surface-variant text-center py-8">Сообщений пока нет</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recent_messages as $m): ?>
                        <div class="p-4 rounded-xl bg-surface-container/60 border border-outline-variant/20 <?php echo !$m['is_read'] ? 'border-l-4 border-l-primary' : ''; ?>">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                                        <span class="font-semibold text-on-surface"><?php echo htmlspecialchars($m['name']); ?></span>
                                        <span class="text-on-surface-variant text-xs"><?php echo htmlspecialchars($m['email']); ?></span>
                                        <?php if (!$m['is_read']): ?>
                                            <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium border border-primary/25">Новое</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
                                    <span class="text-on-surface-variant/60 text-xs mt-2 block"><?php echo date('d.m.Y H:i', strtotime($m['created_at'])); ?></span>
                                </div>
                                <?php if (!$m['is_read']): ?>
                                    <a href="admin.php?read=<?php echo $m['id']; ?>" class="shrink-0 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-medium border border-primary/25 hover:bg-primary/20 transition-colors">Прочитать</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
