<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

// Fetch service by ID
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$service = null;
$related_services = [];

if ($service_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT s.*, c.name as category_name, c.slug as category_slug FROM services s JOIN categories c ON s.category_id = c.id WHERE s.id = ?');
        $stmt->execute([$service_id]);
        $service = $stmt->fetch();

        if ($service) {
            // Fetch related services (same category, excluding current)
            $stmt = $pdo->prepare('SELECT s.*, c.name as category_name FROM services s JOIN categories c ON s.category_id = c.id WHERE s.category_id = ? AND s.id != ? LIMIT 3');
            $stmt->execute([$service['category_id'], $service_id]);
            $related_services = $stmt->fetchAll();
        }
    } catch (Exception $e) {
        // Error handling
    }
}

// If service not found, redirect to services list
if (!$service) {
    header('Location: services.php');
    exit;
}

// Category icon mapping
$cat_icons = [
    'design' => 'design_services',
    'development' => 'code_blocks',
    'it-support' => 'support_agent',
    'video' => 'movie_creation'
];
$icon = $cat_icons[$service['category_slug']] ?? 'design_services';
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
<title>BitBuddy - Service Details</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
                    "spacing": {},
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
<script src="theme.js"></script>
<style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex flex-col overflow-x-hidden relative">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] bg-neutral-950/60 backdrop-blur-[25px]">
<div class="flex justify-between items-center px-8 h-20 w-full max-w-none">
<a class="text-2xl font-extrabold tracking-tighter text-white hover:opacity-80 transition-all" href="index.php">BitBuddy</a>
<div class="hidden md:flex gap-8 items-center font-inter tracking-tight antialiased">
<a class="text-[#69daff] font-semibold border-b-2 border-[#69daff] pb-1" href="services.php">Услуги</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="index.php">О нас</a>
<a class="text-neutral-400 hover:text-white transition-colors duration-300" href="contacts.php">Контакты</a>
</div>
<div class="flex items-center gap-6">
<button onclick="ThemeManager.toggle()" class="text-on-surface-variant hover:text-on-surface transition-colors duration-300" title="Переключить тему">
<span class="material-symbols-outlined" data-theme-icon>dark_mode</span>
</button>
<?php if ($is_logged_in): ?>
<a href="profile.php" class="text-primary font-semibold hover:opacity-80 transition-all duration-300 hidden md:block"><?php echo htmlspecialchars($username); ?></a>
<?php else: ?>
<a href="login.php" class="text-primary font-semibold hover:opacity-80 transition-all duration-300 hidden md:block">Войти</a>
<?php endif; ?>
<button onclick="MobileMenu.toggle()" class="md:hidden text-on-surface-variant hover:text-on-surface transition-colors duration-300">
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
<!-- Main Content -->
<main class="flex-grow pt-32 pb-24 relative z-10">
<!-- Ambient Background Orbs -->
<div class="absolute top-[-100px] left-[-200px] w-[600px] h-[600px] bg-primary/5 rounded-full blur-[100px] pointer-events-none"></div>
<div class="absolute bottom-[200px] right-[-300px] w-[800px] h-[800px] bg-secondary-dim/5 rounded-full blur-[100px] pointer-events-none opacity-50"></div>
<div class="max-w-7xl mx-auto px-6 md:px-12 lg:px-24">
<!-- Hero / Details Section -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24 mb-32 items-start">
<!-- Left: Title & Imagery -->
<div class="lg:col-span-7 flex flex-col gap-8">
<div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel ghost-border w-max">
<span class="material-symbols-outlined text-primary text-sm" data-icon="<?php echo $icon; ?>"><?php echo $icon; ?></span>
<span class="text-on-surface-variant text-sm font-medium tracking-wide uppercase"><?php echo htmlspecialchars($service['category_name']); ?></span>
</div>
<h1 class="text-5xl md:text-[64px] font-[800] leading-[1.1] tracking-[-0.02em] text-on-surface">
                        <?php echo htmlspecialchars($service['title']); ?>
</h1>
<div class="w-full h-[400px] rounded-xl overflow-hidden glass-panel ghost-border relative group mt-4">
<img alt="Service preview" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity duration-700 mix-blend-screen" data-alt="Abstract glowing code interface with deep neon blue and purple hues on a dark glass screen, highly detailed, cinematic lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAs_4xYdzFuN8kif3X-r-KbJTClkL1Miwp6U7Pcepji3TcqfPthPbq33XoEkK1rMrmLCRglexEIB-8G6KqzhdiTcOxVUsy-9Bq5kqBdEjrxa7JMdvwWmfFoks0tGT2lDKnhnOVHz0bPokjXQQDmdw7YeG8yJbbcfIhuzMUhMn_AVPDdnVUkaRqJ-Kf8hzT8fyjXTzSu8StXA3huoTQCyxQNaUKFFaIJmzra5ij_n7sJ_4eBTKMbYfHFa6E0FYfSR93atArXkO3peb6i"/>
<div class="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent"></div>
</div>
</div>
<!-- Right: Booking Pane -->
<div class="lg:col-span-5 sticky top-32">
<div class="glass-panel ghost-border rounded-2xl p-8 md:p-10 flex flex-col gap-8 relative overflow-hidden shadow-[0_20px_60px_rgba(105,218,255,0.08)] transition-all duration-500 hover:shadow-[0_30px_80px_rgba(105,218,255,0.12)]">
<!-- Inner Orb for depth -->
<div class="absolute -top-20 -right-20 w-64 h-64 bg-primary rounded-full blur-[80px] opacity-10 pointer-events-none"></div>
<div>
<div class="text-primary text-[28px] font-bold tracking-tight mb-2">₽<?php echo number_format($service['price'], 0, '', ' '); ?></div>
<p class="text-on-surface-variant text-sm leading-relaxed">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
</div>
<div class="space-y-4">
<div class="flex justify-between items-center py-3 border-b border-outline-variant/30">
<span class="text-on-surface-variant text-sm">Срок выполнения</span>
<span class="text-on-surface font-medium">от 14 дней</span>
</div>
<div class="flex justify-between items-center py-3 border-b border-outline-variant/30">
<span class="text-on-surface-variant text-sm">Аудит включен</span>
<span class="material-symbols-outlined text-primary text-sm" data-icon="check_circle">check_circle</span>
</div>
<div class="flex justify-between items-center py-3 border-b border-outline-variant/30">
<span class="text-on-surface-variant text-sm">Поддержка</span>
<span class="text-on-surface font-medium">30 дней</span>
</div>
</div>
<a href="order.php?id=<?php echo $service['id']; ?>" class="block w-full py-4 rounded-lg bg-primary text-on-primary font-bold tracking-wide transition-all duration-300 glow-hover relative overflow-hidden group mt-4 text-center">
<span class="relative z-10 flex items-center justify-center gap-2">
                                Заказать сейчас
                                <span class="material-symbols-outlined text-xl" data-icon="arrow_forward">arrow_forward</span>
</span>
</a>
</div>
</div>
</div>
<!-- Similar Services -->
<div class="pt-16 border-t border-surface-container-highest">
<div class="flex justify-between items-end mb-12">
<h2 class="text-3xl font-bold tracking-tight text-on-surface">Похожие услуги</h2>
<a class="text-primary text-sm font-medium hover:text-on-surface transition-colors duration-300 flex items-center gap-1" href="services.php">
                        Смотреть все
                        <span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<?php foreach ($related_services as $rel): ?>
<a href="service.php?id=<?php echo $rel['id']; ?>" class="glass-panel ghost-border rounded-xl overflow-hidden group hover:scale-[1.02] transition-transform duration-500 cursor-pointer flex flex-col h-full bg-surface-container-high/50">
<div class="h-48 relative overflow-hidden bg-surface-variant/30">
<div class="absolute inset-0 flex items-center justify-center">
<span class="material-symbols-outlined text-6xl text-on-surface-variant/30"><?php echo $cat_icons[$rel['category_slug']] ?? 'design_services'; ?></span>
</div>
<div class="absolute inset-0 bg-gradient-to-t from-surface-container-high/90 to-transparent"></div>
</div>
<div class="p-6 flex flex-col flex-grow relative">
<div class="text-primary text-xl font-bold mb-3 absolute -top-14 right-6 glass-panel px-3 py-1 rounded-md ghost-border shadow-lg">₽<?php echo number_format($rel['price'], 0, '', ' '); ?></div>
<h3 class="text-lg font-bold text-on-surface mb-2 mt-2 leading-tight"><?php echo htmlspecialchars($rel['title']); ?></h3>
<p class="text-on-surface-variant text-sm mb-6 flex-grow"><?php echo htmlspecialchars(mb_substr($rel['description'], 0, 80)) . '...'; ?></p>
<div class="flex items-center gap-2 text-xs text-on-surface-variant">
<span class="material-symbols-outlined text-[16px]" data-icon="category">category</span>
                                <?php echo htmlspecialchars($rel['category_name']); ?>
                            </div>
</div>
</a>
<?php endforeach; ?>
</div>
</div>
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
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="services.php">Услуги</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="index.php">О нас</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="contacts.php">Контакты</a>
<a class="text-neutral-500 hover:text-[#69daff] hover:drop-shadow-[0_0_8px_#69daff] transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-[#69daff] rounded" href="#">Политика конфиденциальности</a>
</nav>
</div>
</footer>
</body></html>