<?php
session_start();
require_once 'db_connect.php';
require_once __DIR__ . '/includes/service_images.php';

$is_logged_in = isset($_SESSION['user_id']);
$username     = $_SESSION['username'] ?? '';

$service_id       = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service          = null;
$related_services = [];

if ($service_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN categories c ON s.category_id = c.id WHERE s.id = ?');
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();
        if ($service) {
            $stmt = $pdo->prepare('SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN categories c ON s.category_id = c.id WHERE s.category_id = ? AND s.id != ? LIMIT 3');
            $stmt->execute([$service['category_id'], $service_id]);
            $related_services = $stmt->fetchAll();
        }
    } catch (Exception $e) {
        // ignored
    }
}

if (!$service) {
    header('Location: services.php');
    exit;
}

$cat_icons = [
    'design'      => 'design_services',
    'development' => 'code_blocks',
    'it-support'  => 'support_agent',
    'video'       => 'movie_creation',
];
$icon = $cat_icons[$service['category_slug']] ?? 'design_services';
$cover_image = bb_service_image_url($service);

$page_title  = 'BitBuddy — ' . $service['title'];
$active_page = 'services';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col overflow-x-hidden relative">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[600px] h-[600px] top-[-100px] left-[-200px] animate-float"></div>
        <div class="ambient-orb w-[800px] h-[800px] bottom-[200px] right-[-300px] opacity-50 animate-float" style="animation-delay:-4s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="flex-grow pt-32 pb-24 relative z-10">
        <div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 mb-24 items-start">
                <div class="lg:col-span-7 flex flex-col gap-8 animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel ghost-border w-max">
                        <span class="material-symbols-outlined text-primary text-sm"><?php echo $icon; ?></span>
                        <span class="text-on-surface-variant text-sm font-medium tracking-wide uppercase"><?php echo htmlspecialchars($service['category_name']); ?></span>
                    </div>
                    <h1 class="text-5xl md:text-[64px] font-extrabold leading-[1.1] tracking-[-0.02em] text-on-surface">
                        <?php echo htmlspecialchars($service['title']); ?>
                    </h1>
                    <div class="w-full h-[400px] rounded-xl overflow-hidden glass-panel ghost-border relative group mt-4 bg-gradient-to-br from-primary/10 via-transparent to-tertiary-dim/10">
                        <span class="absolute inset-0 flex items-center justify-center material-symbols-outlined text-[160px] text-primary/30 pointer-events-none select-none" aria-hidden="true"><?php echo $icon; ?></span>
                        <?php if ($cover_image): ?>
                            <img src="<?php echo htmlspecialchars($cover_image); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" loading="lazy"
                                 onerror="this.style.display='none'"
                                 class="relative w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"/>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-background/70 via-background/10 to-transparent"></div>
                    </div>
                </div>

                <aside class="lg:col-span-5 lg:sticky lg:top-32 animate-fade-in-up animate-delay-2">
                    <div class="glass-panel ghost-border rounded-2xl p-8 md:p-10 flex flex-col gap-8 relative overflow-hidden shadow-card hover:shadow-glow-primary transition-shadow duration-500">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary rounded-full blur-[80px] opacity-10 pointer-events-none"></div>
                        <div>
                            <div class="text-primary text-[28px] font-bold tracking-tight mb-2">₽<?php echo number_format($service['price'], 0, '', ' '); ?></div>
                            <p class="text-on-surface-variant text-sm leading-relaxed">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
                        </div>

                        <div class="space-y-1">
                            <div class="flex justify-between items-center py-3 border-b border-outline-variant/25">
                                <span class="text-on-surface-variant text-sm">Срок выполнения</span>
                                <span class="text-on-surface font-medium">от 14 дней</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-outline-variant/25">
                                <span class="text-on-surface-variant text-sm">Аудит включён</span>
                                <span class="material-symbols-outlined text-primary text-sm">check_circle</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-outline-variant/25">
                                <span class="text-on-surface-variant text-sm">Поддержка</span>
                                <span class="text-on-surface font-medium">30 дней</span>
                            </div>
                        </div>

                        <a href="order.php?id=<?php echo $service['id']; ?>" class="glow-button block w-full py-4 rounded-lg bg-primary text-on-primary font-bold tracking-wide relative overflow-hidden group mt-2 text-center">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                Заказать сейчас
                                <span class="material-symbols-outlined text-xl">arrow_forward</span>
                            </span>
                        </a>
                    </div>
                </aside>
            </div>

            <?php if (!empty($related_services)): ?>
                <section class="pt-16 border-t border-outline-variant/25">
                    <div class="flex justify-between items-end mb-12">
                        <h2 class="text-3xl font-bold tracking-tight text-on-surface">Похожие услуги</h2>
                        <a class="text-primary text-sm font-medium hover:text-primary-dim transition-colors duration-300 flex items-center gap-1" href="services.php">
                            Смотреть все
                            <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php foreach ($related_services as $rel):
                            $rel_img  = bb_service_image_url($rel);
                            $rel_icon = $cat_icons[$rel['category_slug']] ?? 'design_services';
                        ?>
                            <a href="service.php?id=<?php echo $rel['id']; ?>" class="card-animate glass-panel ghost-border rounded-xl overflow-hidden group hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-500 cursor-pointer flex flex-col h-full bg-surface-container-high/60">
                                <div class="h-48 relative overflow-hidden bg-gradient-to-br from-primary/15 via-surface to-tertiary-dim/15">
                                    <span class="absolute inset-0 flex items-center justify-center material-symbols-outlined text-6xl text-primary/30 pointer-events-none select-none" aria-hidden="true"><?php echo $rel_icon; ?></span>
                                    <?php if ($rel_img): ?>
                                        <img src="<?php echo htmlspecialchars($rel_img); ?>" alt="<?php echo htmlspecialchars($rel['title']); ?>" loading="lazy"
                                             onerror="this.style.display='none'"
                                             class="relative w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"/>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-high/90 via-surface-container-high/20 to-transparent"></div>
                                </div>
                                <div class="p-6 flex flex-col flex-grow relative">
                                    <div class="text-primary text-xl font-bold mb-3 absolute -top-10 right-6 glass-panel px-3 py-1 rounded-md ghost-border shadow-lg">₽<?php echo number_format($rel['price'], 0, '', ' '); ?></div>
                                    <h3 class="text-lg font-bold text-on-surface mb-2 mt-2 leading-tight"><?php echo htmlspecialchars($rel['title']); ?></h3>
                                    <p class="text-on-surface-variant text-sm mb-6 flex-grow"><?php echo htmlspecialchars(mb_substr($rel['description'], 0, 80)) . '…'; ?></p>
                                    <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[16px]">category</span>
                                        <?php echo htmlspecialchars($rel['category_name']); ?>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
