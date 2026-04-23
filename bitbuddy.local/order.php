<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id    = $_SESSION['user_id'];
$username   = $_SESSION['username'] ?? '';
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service    = null;

if ($service_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.id = ?');
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();
    } catch (Exception $e) {}
}

if (!$service) {
    header('Location: services.php');
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = trim($_POST['notes'] ?? '');
    try {
        $order_code = 'BB-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));
        $stmt = $pdo->prepare('INSERT INTO orders (user_id, service_id, order_code, status, price, notes) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_id, $service_id, $order_code, 'pending', $service['price'], $notes]);
        $success = 'Заказ успешно создан! Код заказа: ' . $order_code;
    } catch (Exception $e) {
        $error = 'Ошибка при создании заказа. Попробуйте позже.';
    }
}

$page_title  = 'BitBuddy — Оформление заказа';
$active_page = 'services';
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

    <main class="flex-grow flex items-center justify-center p-6 relative z-10 pt-32 pb-20">
        <div class="w-full max-w-lg animate-scale-in">
            <?php if ($success): ?>
                <div class="glass-panel rounded-2xl p-8 text-center shadow-card">
                    <span class="material-symbols-outlined text-6xl text-primary mb-4 block animate-pulse-glow" style="font-variation-settings:'FILL' 1">check_circle</span>
                    <h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Заказ оформлен!</h1>
                    <p class="text-on-surface-variant mb-6"><?php echo htmlspecialchars($success); ?></p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="profile.php" class="glow-button px-6 py-3 bg-primary text-on-primary font-bold rounded-xl hover:-translate-y-0.5">Мои заказы</a>
                        <a href="services.php" class="px-6 py-3 bg-surface-variant/60 text-on-surface font-medium rounded-xl border border-outline-variant/25 hover:bg-surface-variant transition-all">Каталог услуг</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="glass-panel rounded-2xl p-8 relative overflow-hidden shadow-card">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[2px] bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Оформление заказа</h1>
                    <p class="text-on-surface-variant text-sm mb-8">Подтвердите детали вашего заказа.</p>

                    <?php if ($error): ?>
                        <div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">error</span>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-8 p-6 rounded-xl bg-surface-container-low/60 border border-outline-variant/20">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center border border-primary/25 shrink-0">
                                <span class="material-symbols-outlined text-primary">shopping_cart</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-on-surface"><?php echo htmlspecialchars($service['title']); ?></h3>
                                <p class="text-on-surface-variant text-sm mt-1"><?php echo htmlspecialchars($service['category_name']); ?></p>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-primary">₽<?php echo number_format($service['price'], 0, '', ' '); ?></span>
                            </div>
                        </div>
                        <?php if (!empty($service['delivery_days']) || !empty($service['support_days'])): ?>
                            <div class="mt-4 pt-4 border-t border-outline-variant/20 grid grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center gap-2 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-base">schedule</span>Срок: от <?php echo intval($service['delivery_days'] ?? 14); ?> дн.
                                </div>
                                <div class="flex items-center gap-2 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-base">support_agent</span>Поддержка: <?php echo intval($service['support_days'] ?? 30); ?> дн.
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <form method="POST" action="order.php?id=<?php echo $service_id; ?>">
                        <div class="mb-6">
                            <label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="notes">Комментарий к заказу</label>
                            <textarea class="w-full bg-surface-container-lowest text-on-surface p-4 rounded-xl border border-outline-variant/25 focus:border-primary focus:outline-none transition-all placeholder:text-on-surface-variant/50 resize-none min-h-[100px]" id="notes" name="notes" placeholder="Опишите ваши пожелания или требования к заказу..."></textarea>
                        </div>
                        <button class="glow-button w-full py-3.5 px-6 bg-gradient-to-r from-primary to-primary-fixed-dim text-on-primary font-bold rounded-xl hover:-translate-y-0.5" type="submit">
                            Подтвердить заказ
                        </button>
                    </form>
                    <div class="mt-6 text-center">
                        <a class="text-on-surface-variant hover:text-primary text-sm transition-colors" href="service.php?id=<?php echo $service_id; ?>">← Вернуться к услуге</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
