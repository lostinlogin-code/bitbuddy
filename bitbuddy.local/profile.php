<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id  = (int)$_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Пользователь';

// CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

$flash = $_SESSION['profile_flash'] ?? null;
unset($_SESSION['profile_flash']);

/* ---------- actions (POST, CSRF protected) ---------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($csrf, $token)) {
        $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Сессия устарела, обновите страницу и попробуйте снова'];
        header('Location: profile.php');
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $new_username = trim((string)($_POST['username'] ?? ''));
        $new_email    = trim((string)($_POST['email']    ?? ''));
        if ($new_username === '' || $new_email === '' || !filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Укажите имя и корректный e-mail'];
        } else {
            try {
                $stmt = $pdo->prepare('SELECT id FROM users WHERE (username = ? OR email = ?) AND id <> ?');
                $stmt->execute([$new_username, $new_email, $user_id]);
                if ($stmt->fetch()) {
                    $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Имя или e-mail уже заняты'];
                } else {
                    $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ? WHERE id = ?');
                    $stmt->execute([$new_username, $new_email, $user_id]);
                    $_SESSION['username']      = $new_username;
                    $_SESSION['profile_flash'] = ['type' => 'ok', 'text' => 'Профиль обновлён'];
                }
            } catch (Exception $e) {
                $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Не удалось обновить профиль'];
            }
        }
        header('Location: profile.php#settings');
        exit;
    }

    if ($action === 'change_password') {
        $current = (string)($_POST['current_password'] ?? '');
        $new     = (string)($_POST['new_password']     ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');
        if ($new === '' || strlen($new) < 8) {
            $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Новый пароль должен быть не короче 8 символов'];
        } elseif ($new !== $confirm) {
            $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Пароли не совпадают'];
        } else {
            $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($current, $row['password_hash'])) {
                $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Неверный текущий пароль'];
            } else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $stmt->execute([$hash, $user_id]);
                $_SESSION['profile_flash'] = ['type' => 'ok', 'text' => 'Пароль изменён'];
            }
        }
        header('Location: profile.php#security');
        exit;
    }

    if ($action === 'cancel_order' && isset($_POST['order_id'])) {
        $oid  = (int)$_POST['order_id'];
        $stmt = $pdo->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->execute([$oid, $user_id]);
        if ($stmt->rowCount() > 0) {
            $_SESSION['profile_flash'] = ['type' => 'ok', 'text' => 'Заказ отменён'];
        } else {
            $_SESSION['profile_flash'] = ['type' => 'err', 'text' => 'Нельзя отменить этот заказ'];
        }
        header('Location: profile.php#orders');
        exit;
    }
}

/* ---------- data ---------- */

$user_row = null;
try {
    $stmt = $pdo->prepare('SELECT username, email, role, created_at FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user_row = $stmt->fetch();
} catch (Exception $e) {}

$orders = [];
try {
    $stmt = $pdo->prepare('SELECT o.*, s.title as service_name, s.price as service_price FROM orders o JOIN services s ON o.service_id = s.id WHERE o.user_id = ? ORDER BY o.created_at DESC');
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();
} catch (Exception $e) {}

$total_spent   = array_sum(array_map(fn($o) => (float)$o['price'], array_filter($orders, fn($o) => $o['status'] !== 'cancelled')));
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
        <?php if ($flash): ?>
            <div class="mb-8 p-4 rounded-xl border flex items-center gap-3 animate-fade-in-up <?php echo $flash['type'] === 'ok' ? 'bg-primary/10 border-primary/25 text-primary' : 'bg-error/10 border-error/25 text-error'; ?>">
                <span class="material-symbols-outlined"><?php echo $flash['type'] === 'ok' ? 'check_circle' : 'error'; ?></span>
                <?php echo htmlspecialchars($flash['text']); ?>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-[280px_1fr] gap-10">
            <!-- Sidebar -->
            <aside class="glass-panel rounded-2xl p-6 flex flex-col h-max lg:sticky lg:top-28 animate-slide-left">
                <div class="flex items-center gap-3 px-3 py-3 mb-6 border-b border-outline-variant/20 pb-6">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-primary-fixed-dim flex items-center justify-center text-on-primary font-bold text-xl">
                        <?php echo htmlspecialchars(mb_strtoupper(mb_substr($username, 0, 1))); ?>
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-on-surface truncate"><?php echo htmlspecialchars($username); ?></div>
                        <div class="text-xs text-on-surface-variant"><?php echo ($_SESSION['role'] ?? '') === 'admin' ? 'Администратор' : 'Клиент'; ?></div>
                    </div>
                </div>
                <nav class="flex flex-col gap-1">
                    <a data-profile-tab="#overview" class="profile-tab flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="#overview">
                        <span class="material-symbols-outlined">dashboard</span>Обзор
                    </a>
                    <a data-profile-tab="#orders" class="profile-tab flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="#orders">
                        <span class="material-symbols-outlined">history</span>История заказов
                    </a>
                    <a data-profile-tab="#settings" class="profile-tab flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="#settings">
                        <span class="material-symbols-outlined">manage_accounts</span>Профиль
                    </a>
                    <a data-profile-tab="#security" class="profile-tab flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="#security">
                        <span class="material-symbols-outlined">lock</span>Безопасность
                    </a>
                    <a class="flex items-center gap-4 px-4 py-3 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all" href="services.php">
                        <span class="material-symbols-outlined">grid_view</span>Каталог услуг
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
                        ['label' => 'Всего потрачено', 'value' => '₽' . number_format($total_spent, 0, '', ' '), 'icon' => 'account_balance_wallet', 'tint' => 'primary'],
                        ['label' => 'Активные заказы', 'value' => (string)$active_orders,                        'icon' => 'autorenew',              'tint' => 'secondary'],
                        ['label' => 'Всего заказов',   'value' => (string)$total_orders,                         'icon' => 'inventory_2',            'tint' => 'tertiary'],
                    ];
                    foreach ($stat_cards as $s): ?>
                        <div class="card-animate glass-panel rounded-2xl p-6 relative overflow-hidden group hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300">
                            <div class="absolute top-0 right-0 w-32 h-32 rounded-full blur-2xl -mr-10 -mt-10 transition-colors bg-<?php echo $s['tint']; ?>/10 group-hover:bg-<?php echo $s['tint']; ?>/20"></div>
                            <div class="flex justify-between items-start relative z-10">
                                <div class="text-on-surface-variant font-medium text-sm"><?php echo $s['label']; ?></div>
                                <span class="material-symbols-outlined text-<?php echo $s['tint']; ?>" style="font-variation-settings:'FILL' 1"><?php echo $s['icon']; ?></span>
                            </div>
                            <div class="mt-4 flex items-baseline gap-2 relative z-10">
                                <span class="text-3xl font-bold text-on-surface"><?php echo htmlspecialchars($s['value']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Orders -->
                <section class="flex flex-col gap-6" id="orders">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight text-on-surface">История заказов</h2>
                            <p class="text-on-surface-variant mt-1">Все ваши заказы на платформе и их текущие статусы.</p>
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
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Код</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Услуга</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Цена</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Статус</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Дата</th>
                                        <th class="px-6 py-5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Действия</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-outline-variant/15">
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td class="px-6 py-8 text-sm text-on-surface-variant text-center" colspan="6">
                                            У вас пока нет заказов. <a class="text-primary hover:underline" href="services.php">Выберите услугу</a>.
                                        </td>
                                    </tr>
                                <?php else: foreach ($orders as $order):
                                    $status_config = [
                                        'pending'   => ['bg' => 'bg-tertiary/10',    'text' => 'text-tertiary',          'border' => 'border-tertiary/25',         'dot' => 'bg-tertiary animate-pulse',                               'label' => 'Ожидает'],
                                        'active'    => ['bg' => 'bg-primary/10',     'text' => 'text-primary',           'border' => 'border-primary/25',          'dot' => 'bg-primary shadow-[0_0_8px_rgba(var(--rgb-primary)/0.7)]', 'label' => 'Активен'],
                                        'completed' => ['bg' => 'bg-surface-variant','text' => 'text-on-surface-variant','border' => 'border-outline-variant/30', 'dot' => 'bg-on-surface-variant',                                     'label' => 'Завершён'],
                                        'cancelled' => ['bg' => 'bg-error/10',       'text' => 'text-error',             'border' => 'border-error/25',            'dot' => 'bg-error',                                                  'label' => 'Отменён'],
                                    ];
                                    $cfg = $status_config[$order['status']] ?? $status_config['pending'];
                                ?>
                                    <tr class="hover:bg-surface-variant/20 transition-colors duration-200 group">
                                        <td class="px-6 py-5 text-sm font-mono font-semibold text-primary"><?php echo htmlspecialchars($order['order_code'] ?? '#' . $order['id']); ?></td>
                                        <td class="px-6 py-5 text-sm text-on-surface group-hover:text-on-surface transition-colors">
                                            <a href="service.php?id=<?php echo (int)$order['service_id']; ?>" class="hover:text-primary"><?php echo htmlspecialchars($order['service_name']); ?></a>
                                            <?php if (!empty($order['notes'])): ?>
                                                <div class="text-xs text-on-surface-variant mt-1 max-w-xs truncate" title="<?php echo htmlspecialchars($order['notes']); ?>"><?php echo htmlspecialchars($order['notes']); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-5 text-sm font-bold text-primary">₽<?php echo number_format($order['price'], 0, '', ' '); ?></td>
                                        <td class="px-6 py-5">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border <?php echo $cfg['bg'] . ' ' . $cfg['text'] . ' ' . $cfg['border']; ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?php echo $cfg['dot']; ?>"></span>
                                                <?php echo $cfg['label']; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-on-surface-variant whitespace-nowrap"><?php echo date('d.m.Y', strtotime($order['created_at'])); ?></td>
                                        <td class="px-6 py-5 text-right">
                                            <?php if ($order['status'] === 'pending'): ?>
                                                <form method="POST" action="profile.php#orders" onsubmit="return confirm('Отменить заказ <?php echo htmlspecialchars($order['order_code']); ?>?');" class="inline-flex">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>"/>
                                                    <input type="hidden" name="action" value="cancel_order"/>
                                                    <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>"/>
                                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-error/10 border border-error/25 text-error text-xs font-medium hover:bg-error/20 transition-all">
                                                        <span class="material-symbols-outlined text-sm">cancel</span>Отменить
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-xs text-on-surface-variant/70">—</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Profile settings -->
                <section id="settings" class="glass-panel rounded-2xl p-6 md:p-8 shadow-card">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3"><span class="material-symbols-outlined text-primary">manage_accounts</span>Данные профиля</h2>
                        <p class="text-on-surface-variant text-sm mt-1">Имя пользователя и e-mail, которые видит поддержка.</p>
                    </div>
                    <form method="POST" action="profile.php#settings" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>"/>
                        <input type="hidden" name="action" value="update_profile"/>
                        <label class="flex flex-col gap-2 text-sm">
                            <span class="text-on-surface-variant">Имя пользователя</span>
                            <input type="text" name="username" required maxlength="50" value="<?php echo htmlspecialchars($user_row['username'] ?? $username); ?>"
                                   class="bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant/25 px-4 py-2.5 focus:border-primary focus:outline-none"/>
                        </label>
                        <label class="flex flex-col gap-2 text-sm">
                            <span class="text-on-surface-variant">E-mail</span>
                            <input type="email" name="email" required maxlength="255" value="<?php echo htmlspecialchars($user_row['email'] ?? ''); ?>"
                                   class="bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant/25 px-4 py-2.5 focus:border-primary focus:outline-none"/>
                        </label>
                        <div class="md:col-span-2 flex items-center justify-between gap-3 pt-2">
                            <p class="text-xs text-on-surface-variant">Зарегистрирован: <?php echo !empty($user_row['created_at']) ? date('d.m.Y', strtotime($user_row['created_at'])) : '—'; ?></p>
                            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary font-semibold px-5 py-2.5 rounded-xl hover:shadow-glow-primary transition-all">
                                <span class="material-symbols-outlined text-lg">save</span>Сохранить
                            </button>
                        </div>
                    </form>
                </section>

                <!-- Password -->
                <section id="security" class="glass-panel rounded-2xl p-6 md:p-8 shadow-card">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-on-surface flex items-center gap-3"><span class="material-symbols-outlined text-primary">lock</span>Смена пароля</h2>
                        <p class="text-on-surface-variant text-sm mt-1">Минимум 8 символов. После смены вы останетесь залогинены.</p>
                    </div>
                    <form method="POST" action="profile.php#security" class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>"/>
                        <input type="hidden" name="action" value="change_password"/>
                        <label class="flex flex-col gap-2 text-sm md:col-span-2">
                            <span class="text-on-surface-variant">Текущий пароль</span>
                            <input type="password" name="current_password" required autocomplete="current-password"
                                   class="bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant/25 px-4 py-2.5 focus:border-primary focus:outline-none"/>
                        </label>
                        <label class="flex flex-col gap-2 text-sm">
                            <span class="text-on-surface-variant">Новый пароль</span>
                            <input type="password" name="new_password" required minlength="8" autocomplete="new-password"
                                   class="bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant/25 px-4 py-2.5 focus:border-primary focus:outline-none"/>
                        </label>
                        <label class="flex flex-col gap-2 text-sm">
                            <span class="text-on-surface-variant">Подтверждение</span>
                            <input type="password" name="confirm_password" required minlength="8" autocomplete="new-password"
                                   class="bg-surface-container-lowest text-on-surface rounded-lg border border-outline-variant/25 px-4 py-2.5 focus:border-primary focus:outline-none"/>
                        </label>
                        <div class="md:col-span-2 flex justify-end pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 bg-primary text-on-primary font-semibold px-5 py-2.5 rounded-xl hover:shadow-glow-primary transition-all">
                                <span class="material-symbols-outlined text-lg">key</span>Сменить пароль
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
    // Highlight the sidebar item matching the current #hash. Pure CSS :target
    // would also work but we also want a sensible default when no hash is set.
    (function(){
        function sync() {
            var hash = location.hash || '#overview';
            document.querySelectorAll('.profile-tab').forEach(function(el){
                var active = el.getAttribute('data-profile-tab') === hash;
                el.classList.toggle('bg-primary/10',    active);
                el.classList.toggle('text-primary',     active);
                el.classList.toggle('font-semibold',    active);
                el.classList.toggle('text-on-surface-variant', !active);
            });
        }
        window.addEventListener('hashchange', sync);
        document.addEventListener('DOMContentLoaded', sync);
    })();
    </script>
</body>
</html>
