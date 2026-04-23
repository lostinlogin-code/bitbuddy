<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Fetch categories and services from database
$categories = [];
$services = [];
try {
    $stmt = $pdo->query('SELECT * FROM categories ORDER BY id');
    $categories = $stmt->fetchAll();

    $stmt = $pdo->query('SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN categories c ON s.category_id = c.id ORDER BY s.id');
    $services = $stmt->fetchAll();
} catch (Exception $e) {
    // Tables might not exist
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
<title>BitBuddy - Каталог Услуг</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        "headline": ["Inter", "sans-serif"],
                        "body": ["Inter", "sans-serif"],
                        "label": ["Inter", "sans-serif"]
                    }
                }
            }
        }
    </script>
<script src="theme.js"></script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body min-h-screen relative overflow-x-hidden selection:bg-primary-container selection:text-on-primary-container">
<!-- Glowing Orbs (Ambient Background) -->
<div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none z-0">
<div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/10 blur-[120px] mix-blend-screen"></div>
<div class="absolute bottom-[-20%] right-[-10%] w-[60vw] h-[60vw] rounded-full bg-secondary-dim/5 blur-[150px] mix-blend-screen"></div>
</div>
<!-- TopNavBar Shared Component -->
<nav class="fixed top-0 w-full z-50 bg-neutral-950/60 backdrop-blur-[25px] border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] font-inter tracking-tight antialiased transition-all duration-300">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none mx-auto relative z-10">
<!-- Brand -->
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<!-- Navigation Links (Web) -->
<div class="hidden md:flex items-center space-x-8">
<a class="text-[#69daff] font-semibold border-b-2 border-[#69daff] pb-1" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</div>
<!-- Actions -->
<div class="flex items-center space-x-6">
<button onclick="ThemeManager.toggle()" aria-label="Переключить тему" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300 hover:opacity-80 transition-all duration-300 active:scale-95 transform transition-transform">
<span class="material-symbols-outlined text-xl" data-theme-icon>dark_mode</span>
</button>
<?php if ($is_logged_in): ?>
<a href="profile.php" class="hidden md:flex items-center justify-center px-6 py-2 rounded-full bg-surface-variant backdrop-blur-[25px] border border-outline-variant/15 text-primary hover:bg-primary hover:text-on-primary hover:shadow-[0_0_20px_rgba(105,218,255,0.4)] transition-all duration-300 active:scale-95 transform font-medium"><?php echo htmlspecialchars($username); ?></a>
<?php else: ?>
<a href="login.php" class="hidden md:flex items-center justify-center px-6 py-2 rounded-full bg-surface-variant backdrop-blur-[25px] border border-outline-variant/15 text-primary hover:bg-primary hover:text-on-primary hover:shadow-[0_0_20px_rgba(105,218,255,0.4)] transition-all duration-300 active:scale-95 transform font-medium">Войти</a>
<?php endif; ?>
<!-- Mobile Menu Toggle -->
<button onclick="MobileMenu.toggle()" class="md:hidden text-on-surface hover:text-primary transition-colors duration-300">
<span class="material-symbols-outlined text-2xl">menu</span>
</button>
</div>
</div>
</nav>
<!-- Mobile Menu Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[60] md:hidden" onclick="MobileMenu.close()"></div>
<!-- Mobile Menu Drawer -->
<div id="mobile-menu" class="fixed top-0 right-0 h-full w-72 bg-surface-container backdrop-blur-[25px] border-l border-outline-variant/15 z-[70] transform translate-x-full transition-transform duration-300 md:hidden flex flex-col p-8 gap-6">
<div class="flex justify-between items-center mb-4">
<span class="text-xl font-black text-on-surface tracking-tighter">BitBuddy</span>
<button onclick="MobileMenu.close()" class="text-on-surface-variant hover:text-on-surface">
<span class="material-symbols-outlined">close</span>
</button>
</div>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="index.php" onclick="MobileMenu.close()">О нас</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="services.php" onclick="MobileMenu.close()">Услуги</a>
<a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="contacts.php" onclick="MobileMenu.close()">Контакты</a>
<div class="mt-auto pt-6 border-t border-outline-variant/15">
<?php if ($is_logged_in): ?>
<a href="profile.php" class="text-primary font-semibold block py-2" onclick="MobileMenu.close()"><?php echo htmlspecialchars($username); ?></a>
<a href="logout.php" class="text-error-dim block py-2">Выйти</a>
<?php else: ?>
<a href="login.php" class="text-primary font-semibold block py-2" onclick="MobileMenu.close()">Войти</a>
<?php endif; ?>
</div>
</div>
<!-- Main Content Canvas -->
<main class="relative z-10 pt-32 pb-24 px-6 md:px-12 lg:px-24 max-w-[1600px] mx-auto min-h-screen flex flex-col gap-16">
<!-- Hero Header -->
<header class="flex flex-col items-center text-center max-w-3xl mx-auto space-y-6">
<h1 class="text-5xl md:text-6xl font-headline font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-primary to-primary-container leading-tight">
                Каталог Услуг
            </h1>
<p class="text-lg md:text-xl text-on-surface-variant font-light max-w-2xl leading-relaxed">
                Выбирайте из кураторского списка высококачественных цифровых решений, созданных для ускорения вашего бизнеса.
            </p>
</header>
<!-- Filters (Pills) -->
<section class="flex flex-wrap justify-center gap-4 w-full">
<button class="filter-btn px-6 py-2 rounded-full bg-primary text-on-primary font-medium tracking-wide shadow-[0_0_20px_rgba(105,218,255,0.3)] transition-all duration-300 hover:scale-105 active:scale-95 border border-primary" data-filter="all">Все</button>
<?php foreach ($categories as $cat): ?>
<button class="filter-btn px-6 py-2 rounded-full bg-surface-variant/50 backdrop-blur-md text-on-surface-variant font-medium tracking-wide border border-outline-variant/15 hover:bg-surface-variant hover:text-on-surface hover:border-outline-variant/30 transition-all duration-300 hover:scale-105 active:scale-95" data-filter="<?php echo htmlspecialchars($cat['slug']); ?>"><?php echo htmlspecialchars($cat['name']); ?></button>
<?php endforeach; ?>
</section>
<!-- Services Grid -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12 relative w-full">
<?php
// Category icon mapping
$cat_icons = [
    'design' => 'design_services',
    'development' => 'code_blocks',
    'it-support' => 'support_agent',
    'video' => 'movie_creation'
];
$cat_colors = [
    'design' => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'border' => 'border-primary/20', 'hover' => 'group-hover:bg-primary/20'],
    'development' => ['bg' => 'bg-secondary-dim/10', 'text' => 'text-secondary-dim', 'border' => 'border-secondary-dim/20', 'hover' => 'group-hover:bg-secondary-dim/20'],
    'it-support' => ['bg' => 'bg-tertiary-dim/10', 'text' => 'text-tertiary-dim', 'border' => 'border-tertiary-dim/20', 'hover' => 'group-hover:bg-tertiary-dim/20'],
    'video' => ['bg' => 'bg-primary/10', 'text' => 'text-primary', 'border' => 'border-primary/20', 'hover' => 'group-hover:bg-primary/20']
];
foreach ($services as $svc):
    $icon = $cat_icons[$svc['category_slug']] ?? 'design_services';
    $colors = $cat_colors[$svc['category_slug']] ?? $cat_colors['design'];
?>
<article class="service-card group relative flex flex-col p-8 rounded-2xl bg-surface-container-high/80 backdrop-blur-xl border border-outline-variant/10 shadow-[0_20px_40px_-10px_rgba(0,0,0,0.5)] hover:shadow-[0_30px_60px_-15px_rgba(105,218,255,0.1)] hover:scale-[1.02] hover:bg-surface-container-high transition-all duration-500 ease-out flex-1 min-h-[400px]" data-category="<?php echo htmlspecialchars($svc['category_slug']); ?>">
<?php if ($svc['is_popular']): ?>
<div class="absolute top-6 right-6 px-3 py-1 rounded-full bg-primary/10 border border-primary/20 flex items-center gap-1.5 shadow-[0_0_15px_rgba(105,218,255,0.15)]">
<span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
<span class="text-xs font-semibold text-primary uppercase tracking-wider">Популярное</span>
</div>
<?php endif; ?>
<div class="w-16 h-16 rounded-full <?php echo $colors['bg']; ?> flex items-center justify-center mb-8 border <?php echo $colors['border']; ?> <?php echo $colors['hover']; ?> transition-colors duration-300">
<span class="material-symbols-outlined text-3xl <?php echo $colors['text']; ?>" style="font-variation-settings: 'FILL' 1;"><?php echo $icon; ?></span>
</div>
<h3 class="text-2xl font-headline font-bold text-white mb-3 tracking-tight"><?php echo htmlspecialchars($svc['title']); ?></h3>
<p class="text-on-surface-variant text-sm leading-relaxed mb-8 flex-grow">
                    <?php echo htmlspecialchars($svc['description']); ?>
                </p>
<div class="flex items-end justify-between mt-auto pt-6 border-t border-outline-variant/10">
<div class="flex flex-col">
<span class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"><?php echo $svc['price_label'] ?? 'От'; ?></span>
<span class="text-2xl font-headline font-bold text-primary">₽<?php echo number_format($svc['price'], 0, '', ' '); ?></span>
</div>
<a href="service.php?id=<?php echo $svc['id']; ?>" class="px-5 py-2.5 rounded-full bg-surface-variant backdrop-blur-md text-white text-sm font-medium border border-outline-variant/15 hover:bg-primary hover:text-on-primary hover:border-primary transition-all duration-300 group-hover:shadow-[0_0_20px_rgba(105,218,255,0.2)]">
                        Подробнее
                    </a>
</div>
</article>
<?php endforeach; ?>
</section>
<!-- Load More / CTA -->
<div id="load-more-wrap" class="flex justify-center pt-8">
<button id="load-more-btn" class="flex items-center gap-2 px-8 py-4 rounded-full bg-surface-variant/30 backdrop-blur-[25px] border border-outline-variant/20 text-on-surface hover:text-primary hover:border-primary/50 transition-all duration-300 hover:shadow-[0_0_30px_rgba(105,218,255,0.1)]">
<span class="font-medium tracking-wide">Загрузить еще</span>
<span class="material-symbols-outlined text-xl">expand_more</span>
</button>
</div>
</main>
<!-- Footer -->
<footer class="w-full relative bottom-0 bg-[#0e0e0e] border-t border-[#69daff]/30 shadow-[0_-10px_40px_rgba(105,218,255,0.05)] font-inter text-sm tracking-wide z-20">
<div class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row justify-between items-center gap-8">
<div class="flex flex-col items-center md:items-start gap-4">
<span class="text-xl font-black text-white tracking-tighter">BitBuddy</span>
<span class="text-neutral-500">© 2024 BitBuddy. The Ethereal Exchange.</span>
</div>
<nav class="flex flex-wrap justify-center md:justify-end gap-x-8 gap-y-4">
<a class="text-[#69daff] underline underline-offset-4 hover:text-white hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="services.php">Услуги</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="index.php">О нас</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="contacts.php">Контакты</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
<script>
(function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.service-card');
    const loadMoreBtn = document.getElementById('load-more-btn');
    const loadMoreWrap = document.getElementById('load-more-wrap');
    const VISIBLE_COUNT = 6;
    let currentFilter = 'all';
    let shown = VISIBLE_COUNT;

    function getFilteredCards() {
        if (currentFilter === 'all') return Array.from(cards);
        return Array.from(cards).filter(c => c.dataset.category === currentFilter);
    }

    function updateVisibility() {
        const filtered = getFilteredCards();
        filtered.forEach((card, i) => {
            if (i < shown) {
                card.style.display = '';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            }
        });
        if (loadMoreWrap) {
            loadMoreWrap.style.display = filtered.length > shown ? '' : 'none';
        }
    }

    // Load More
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            shown += VISIBLE_COUNT;
            const filtered = getFilteredCards();
            filtered.forEach((card, i) => {
                if (i < shown) {
                    card.style.display = '';
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    requestAnimationFrame(() => {
                        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    });
                }
            });
            if (filtered.length <= shown && loadMoreWrap) {
                loadMoreWrap.style.display = 'none';
            }
        });
    }

    // Filter buttons
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentFilter = this.dataset.filter;
            shown = VISIBLE_COUNT;

            filterBtns.forEach(b => {
                b.classList.remove('bg-primary', 'text-on-primary', 'border-primary', 'shadow-[0_0_20px_rgba(105,218,255,0.3)]');
                b.classList.add('bg-surface-variant/50', 'text-on-surface-variant', 'border-outline-variant/15');
            });
            this.classList.remove('bg-surface-variant/50', 'text-on-surface-variant', 'border-outline-variant/15');
            this.classList.add('bg-primary', 'text-on-primary', 'border-primary', 'shadow-[0_0_20px_rgba(105,218,255,0.3)]');

            // Hide all first
            cards.forEach(card => {
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            });

            // Show filtered with animation
            updateVisibility();
            const filtered = getFilteredCards();
            filtered.slice(0, shown).forEach((card, i) => {
                setTimeout(() => {
                    card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, i * 60);
            });
        });
    });

    // Initial state
    updateVisibility();
})();
</script>
</body></html>