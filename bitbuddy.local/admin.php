<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$flash    = $_SESSION['admin_flash'] ?? null;
unset($_SESSION['admin_flash']);

/* ---------- actions ---------- */

// Mark single message as read
if (isset($_GET['read']) && is_numeric($_GET['read'])) {
    $stmt = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $stmt->execute([(int)$_GET['read']]);
    $_SESSION['admin_flash'] = ['type' => 'ok', 'text' => 'Сообщение отмечено прочитанным'];
    header('Location: admin.php#messages');
    exit;
}

// Mark all messages as read
if (isset($_GET['mark_all'])) {
    $pdo->query('UPDATE contact_messages SET is_read = 1 WHERE is_read = 0');
    $_SESSION['admin_flash'] = ['type' => 'ok', 'text' => 'Все сообщения отмечены прочитанными'];
    header('Location: admin.php#messages');
    exit;
}

// Delete message
if (isset($_GET['delete_msg']) && is_numeric($_GET['delete_msg'])) {
    $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = ?');
    $stmt->execute([(int)$_GET['delete_msg']]);
    $_SESSION['admin_flash'] = ['type' => 'ok', 'text' => 'Сообщение удалено'];
    header('Location: admin.php#messages');
    exit;
}

// Update order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['new_status'])) {
    $valid = ['pending', 'active', 'completed', 'cancelled'];
    $new   = $_POST['new_status'];
    $oid   = (int)$_POST['order_id'];
    if (in_array($new, $valid, true) && $oid > 0) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute([$new, $oid]);
        $_SESSION['admin_flash'] = ['type' => 'ok', 'text' => 'Статус заказа обновлён'];
    } else {
        $_SESSION['admin_flash'] = ['type' => 'err', 'text' => 'Некорректный статус'];
    }
    header('Location: admin.php#orders');
    exit;
}

/* ---------- data ---------- */

$user_count    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$order_count   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$msg_count     = $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE is_read = 0')->fetchColumn();
$service_count = $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn();

$status_filter = $_GET['status'] ?? 'all';
$valid_statuses = ['all', 'pending', 'active', 'completed', 'cancelled'];
if (!in_array($status_filter, $valid_statuses, true)) $status_filter = 'all';

if ($status_filter === 'all') {
    $recent_orders = $pdo->query('SELECT o.*, u.username, s.title as service_title FROM orders o JOIN users u ON o.user_id = u.id JOIN services s ON o.service_id = s.id ORDER BY o.created_at DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare('SELECT o.*, u.username, s.title as service_title FROM orders o JOIN users u ON o.user_id = u.id JOIN services s ON o.service_id = s.id WHERE o.status = ? ORDER BY o.created_at DESC LIMIT 20');
    $stmt->execute([$status_filter]);
    $recent_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$recent_messages = $pdo->query('SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 20')->fetchAll(PDO::FETCH_ASSOC);

$status_names = ['pending' => 'Ожидает', 'active' => 'Активен', 'completed' => 'Завершён', 'cancelled' => 'Отменён'];
$status_chip  = [
    'pending'   => 'bg-tertiary/10   text-tertiary     border-tertiary/25',
    'active'    => 'bg-primary/10    text-primary      border-primary/25',
    'completed' => 'bg-surface-variant text-on-surface-variant border-outline-variant/30',
    'cancelled' => 'bg-error/10      text-error        border-error/25',
];

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
        <div class="mb-12 animate-fade-in-up flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-4xl font-bold text-on-surface tracking-tight mb-2">Админ-панель</h1>
                <p class="text-on-surface-variant">Добро пожаловать, <span class="text-primary font-semibold"><?php echo htmlspecialchars($username); ?></span></p>
            </div>
            <a href="admin.php" class="self-start md:self-auto inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-variant/60 border border-outline-variant/25 text-on-surface hover:bg-surface-variant transition-all text-sm">
                <span class="material-symbols-outlined text-lg">refresh</span>Обновить
            </a>
        </div>

        <?php if ($flash): ?>
            <div class="mb-8 p-4 rounded-xl border flex items-center gap-3 animate-fade-in-up <?php echo $flash['type'] === 'ok' ? 'bg-primary/10 border-primary/25 text-primary' : 'bg-error/10 border-error/25 text-error'; ?>">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'ok' ? 'check_circle' : 'error'; ?></span>
                <?php echo htmlspecialchars($flash['text']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <?php
            $admin_stats = [
                ['value' => $user_count,    'label' => 'Пользователей',   'icon' => 'group'],
                ['value' => $order_count,   'label' => 'Заказов',          'icon' => 'receipt_long'],
                ['value' => $msg_count,     'label' => 'Новых сообщений', 'icon' => 'mail'],
                ['value' => $service_count, 'label' => 'Услуг',            'icon' => 'grid_view'],
            ];
            foreach ($admin_stats as $s): ?>
                <div class="stat-card glass-panel p-6 rounded-2xl flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300">
                    <span class="material-symbols-outlined text-primary text-3xl mb-2" style="font-variation-settings:'FILL' 1"><?php echo $s['icon']; ?></span>
                    <span class="stat-value text-[36px] font-black text-on-surface mb-1" data-target="<?php echo (int)$s['value']; ?>">0</span>
                    <span class="text-on-surface-variant text-sm"><?php echo $s['label']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Orders -->
        <section id="orders" class="glass-panel rounded-2xl p-6 md:p-8 mb-8 shadow-card">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">receipt_long</span>Заказы
                </h2>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $tabs = [
                        'all'       => 'Все',
                        'pending'   => 'Ожидают',
                        'active'    => 'Активные',
                        'completed' => 'Завершённые',
                        'cancelled' => 'Отменённые',
                    ];
                    foreach ($tabs as $k => $label):
                        $active = $status_filter === $k;
                    ?>
                        <a href="admin.php?status=<?php echo $k; ?>#orders"
                           class="px-4 py-2 rounded-full text-sm font-medium border transition-all <?php echo $active ? 'bg-primary text-on-primary border-primary shadow-glow-primary' : 'bg-surface-variant/40 text-on-surface-variant border-outline-variant/25 hover:bg-surface-variant hover:text-on-surface'; ?>">
                            <?php echo $label; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (empty($recent_orders)): ?>
                <p class="text-on-surface-variant text-center py-8">В этой категории заказов нет</p>
            <?php else: ?>
                <div class="overflow-x-auto -mx-6 md:-mx-8">
                    <table class="w-full text-sm min-w-[800px]">
                        <thead>
                            <tr class="border-b border-outline-variant/25 text-on-surface-variant">
                                <th class="py-3 px-6 text-left font-medium">Код</th>
                                <th class="py-3 px-2 text-left font-medium">Клиент</th>
                                <th class="py-3 px-2 text-left font-medium">Услуга</th>
                                <th class="py-3 px-2 text-left font-medium">Цена</th>
                                <th class="py-3 px-2 text-left font-medium">Статус</th>
                                <th class="py-3 px-2 text-left font-medium">Дата</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $o):
                                $sn = $status_names[$o['status']] ?? $o['status'];
                                $sc = $status_chip[$o['status']] ?? $status_chip['pending'];
                            ?>
                                <tr class="border-b border-outline-variant/15 hover:bg-on-surface/5 transition-colors">
                                    <td class="py-3 px-6 text-primary font-mono font-semibold"><?php echo htmlspecialchars($o['order_code']); ?></td>
                                    <td class="py-3 px-2 text-on-surface"><?php echo htmlspecialchars($o['username']); ?></td>
                                    <td class="py-3 px-2 text-on-surface-variant"><?php echo htmlspecialchars($o['service_title']); ?></td>
                                    <td class="py-3 px-2 text-on-surface font-semibold">₽<?php echo number_format($o['price'], 0, '', ' '); ?></td>
                                    <td class="py-3 px-2">
                                        <form method="POST" action="admin.php" class="flex items-center gap-2">
                                            <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>"/>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium border <?php echo $sc; ?>"><?php echo $sn; ?></span>
                                            <select name="new_status" onchange="this.form.submit()"
                                                    class="bg-surface-container-lowest text-on-surface text-xs rounded-lg border border-outline-variant/25 px-2 py-1 focus:border-primary focus:outline-none cursor-pointer">
                                                <option value="" disabled selected>Изменить…</option>
                                                <?php foreach ($status_names as $k => $label): if ($k === $o['status']) continue; ?>
                                                    <option value="<?php echo $k; ?>"><?php echo $label; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-3 px-2 text-on-surface-variant text-xs whitespace-nowrap"><?php echo date('d.m.Y H:i', strtotime($o['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <!-- Messages -->
        <section id="messages" class="glass-panel rounded-2xl p-6 md:p-8 shadow-card">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">mail</span>Сообщения
                    <?php if ($msg_count > 0): ?>
                        <span class="ml-2 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-semibold border border-primary/25"><?php echo (int)$msg_count; ?> новых</span>
                    <?php endif; ?>
                </h2>
                <?php if ($msg_count > 0): ?>
                    <a href="admin.php?mark_all=1#messages"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary/10 border border-primary/25 text-primary hover:bg-primary/20 transition-all text-sm">
                        <span class="material-symbols-outlined text-base">done_all</span>Отметить все
                    </a>
                <?php endif; ?>
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
                                        <a href="mailto:<?php echo htmlspecialchars($m['email']); ?>" class="text-on-surface-variant text-xs hover:text-primary transition-colors"><?php echo htmlspecialchars($m['email']); ?></a>
                                        <?php if (!$m['is_read']): ?>
                                            <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium border border-primary/25">Новое</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo nl2br(htmlspecialchars($m['message'])); ?></p>
                                    <span class="text-on-surface-variant/60 text-xs mt-2 block"><?php echo date('d.m.Y H:i', strtotime($m['created_at'])); ?></span>
                                </div>
                                <div class="flex flex-col gap-2 shrink-0">
                                    <?php if (!$m['is_read']): ?>
                                        <a href="admin.php?read=<?php echo (int)$m['id']; ?>#messages"
                                           class="px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-xs font-medium border border-primary/25 hover:bg-primary/20 transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">done</span>Прочитать
                                        </a>
                                    <?php endif; ?>
                                    <a href="admin.php?delete_msg=<?php echo (int)$m['id']; ?>#messages"
                                       onclick="return confirm('Удалить сообщение?')"
                                       class="px-3 py-1.5 rounded-lg bg-error/10 text-error text-xs font-medium border border-error/25 hover:bg-error/20 transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">delete</span>Удалить
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
