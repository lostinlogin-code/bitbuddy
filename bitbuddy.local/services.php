<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

$categories = [];
$services = [];
try {
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY id');
    $categories = $stmt->fetchAll();
    $stmt = $pdo->query('SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN categories c ON s.category_id = c.id ORDER BY s.id');
    $services = $stmt->fetchAll();
} catch (Exception $e) {
    // tables might not yet exist
}

$page_title  = 'BitBuddy — Каталог услуг';
$active_page = 'services';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen relative overflow-x-hidden flex flex-col">

    <!-- Ambient orbs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[60vw] h-[60vw] top-[-10%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[50vw] h-[50vw] bottom-[-20%] right-[-10%] opacity-60 animate-float" style="animation-delay:-4s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="relative z-10 pt-32 pb-24 px-6 md:px-12 lg:px-24 max-w-[1600px] mx-auto flex-grow w-full flex flex-col gap-16">

        <header class="flex flex-col items-center text-center max-w-3xl mx-auto space-y-6 animate-fade-in-up">
            <h1 class="text-5xl md:text-6xl font-headline font-extrabold tracking-tight text-gradient leading-tight">
                Каталог услуг
            </h1>
            <p class="text-lg md:text-xl text-on-surface-variant font-light max-w-2xl leading-relaxed">
                Выбирайте из кураторского списка высококачественных цифровых решений, созданных для ускорения вашего бизнеса.
            </p>
        </header>

        <section class="flex flex-wrap justify-center gap-3 w-full animate-fade-in-up animate-delay-2">
            <button class="filter-btn px-6 py-2 rounded-full bg-primary text-on-primary font-medium tracking-wide shadow-glow-primary transition-all duration-300 hover:scale-105 active:scale-95 border border-primary" data-filter="all">Все</button>
            <?php foreach ($categories as $cat): ?>
                <button class="filter-btn px-6 py-2 rounded-full bg-surface-variant/60 backdrop-blur-md text-on-surface-variant font-medium tracking-wide border border-outline-variant/20 hover:bg-surface-variant hover:text-on-surface hover:border-outline-variant/40 transition-all duration-300 hover:scale-105 active:scale-95" data-filter="<?php echo htmlspecialchars($cat['slug']); ?>"><?php echo htmlspecialchars($cat['name']); ?></button>
            <?php endforeach; ?>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 relative w-full">
            <?php
            $cat_icons = [
                'design' => 'design_services',
                'development' => 'code_blocks',
                'it-support' => 'support_agent',
                'video' => 'movie_creation',
            ];
            $cat_accent = [
                'design'       => 'text-primary    bg-primary/10    border-primary/20',
                'development'  => 'text-secondary-dim bg-secondary-dim/10 border-secondary-dim/20',
                'it-support'   => 'text-tertiary-dim  bg-tertiary-dim/10  border-tertiary-dim/20',
                'video'        => 'text-primary    bg-primary/10    border-primary/20',
            ];
            foreach ($services as $svc):
                $icon  = $cat_icons[$svc['category_slug']]  ?? 'design_services';
                $accent = $cat_accent[$svc['category_slug']] ?? $cat_accent['design'];
            ?>
                <article class="service-card card-animate group relative flex flex-col p-8 rounded-2xl glass-panel hover:shadow-glow-primary hover:-translate-y-1 transition-all duration-500 ease-out min-h-[400px]" data-category="<?php echo htmlspecialchars($svc['category_slug']); ?>">
                    <?php if (!empty($svc['is_popular'])): ?>
                        <div class="absolute top-6 right-6 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 flex items-center gap-1.5 shadow-glow-primary">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span class="text-xs font-semibold text-primary uppercase tracking-wider">Популярное</span>
                        </div>
                    <?php endif; ?>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-8 border <?php echo $accent; ?> group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;"><?php echo $icon; ?></span>
                    </div>
                    <h3 class="text-2xl font-headline font-bold text-on-surface mb-3 tracking-tight"><?php echo htmlspecialchars($svc['title']); ?></h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed mb-8 flex-grow">
                        <?php echo htmlspecialchars($svc['description']); ?>
                    </p>
                    <div class="flex items-end justify-between mt-auto pt-6 border-t border-outline-variant/20">
                        <div class="flex flex-col">
                            <span class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"><?php echo $svc['price_label'] ?? 'От'; ?></span>
                            <span class="text-2xl font-headline font-bold text-primary">₽<?php echo number_format($svc['price'], 0, '', ' '); ?></span>
                        </div>
                        <a href="service.php?id=<?php echo $svc['id']; ?>" class="px-5 py-2.5 rounded-full bg-surface-variant/60 backdrop-blur-md text-on-surface text-sm font-medium border border-outline-variant/20 hover:bg-primary hover:text-on-primary hover:border-primary transition-all duration-300">
                            Подробнее
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <div id="load-more-wrap" class="flex justify-center pt-8">
            <button id="load-more-btn" class="flex items-center gap-2 px-8 py-4 rounded-full bg-surface-variant/40 backdrop-blur-[25px] border border-outline-variant/25 text-on-surface hover:text-primary hover:border-primary/50 transition-all duration-300 hover:shadow-glow-primary">
                <span class="font-medium tracking-wide">Загрузить ещё</span>
                <span class="material-symbols-outlined text-xl">expand_more</span>
            </button>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script>
    (function () {
        var filterBtns = document.querySelectorAll('.filter-btn');
        var cards      = document.querySelectorAll('.service-card');
        var loadBtn    = document.getElementById('load-more-btn');
        var loadWrap   = document.getElementById('load-more-wrap');
        var VISIBLE    = 6;
        var filter     = 'all';
        var shown      = VISIBLE;

        function getFiltered() {
            if (filter === 'all') return Array.from(cards);
            return Array.from(cards).filter(function (c) { return c.dataset.category === filter; });
        }

        function update() {
            var list = getFiltered();
            list.forEach(function (card, i) {
                if (i < shown) {
                    card.style.display = '';
                    card.classList.add('visible');
                } else {
                    card.style.display = 'none';
                }
            });
            if (loadWrap) loadWrap.style.display = list.length > shown ? '' : 'none';
        }

        filterBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                filterBtns.forEach(function (b) {
                    b.classList.remove('bg-primary', 'text-on-primary', 'shadow-glow-primary', 'border-primary');
                    b.classList.add('bg-surface-variant/60', 'text-on-surface-variant', 'border-outline-variant/20');
                });
                btn.classList.add('bg-primary', 'text-on-primary', 'shadow-glow-primary', 'border-primary');
                btn.classList.remove('bg-surface-variant/60', 'text-on-surface-variant', 'border-outline-variant/20');
                filter = btn.dataset.filter;
                shown = VISIBLE;
                update();
            });
        });

        if (loadBtn) {
            loadBtn.addEventListener('click', function () {
                shown += VISIBLE;
                update();
            });
        }

        update();
    })();
    </script>
</body>
</html>
