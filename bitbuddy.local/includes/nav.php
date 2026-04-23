<?php
/**
 * Shared top navigation + mobile menu.
 * Caller may set before including:
 *   $active_page  — one of 'home' | 'services' | 'contacts' | 'profile' | 'privacy' | null
 *   $is_logged_in — bool
 *   $username     — string
 */
$active_page  = $active_page  ?? null;
$is_logged_in = $is_logged_in ?? (isset($_SESSION['user_id']));
$username     = $username     ?? ($_SESSION['username'] ?? '');

$bb_nav_items = [
    ['key' => 'home',     'href' => 'index.php',    'label' => 'О нас'],
    ['key' => 'services', 'href' => 'services.php', 'label' => 'Услуги'],
    ['key' => 'contacts', 'href' => 'contacts.php', 'label' => 'Контакты'],
];
?>
<nav id="top-nav" class="fixed top-0 w-full z-50 bg-surface/70 backdrop-blur-[25px] border-b border-outline-variant/15 shadow-nav font-body tracking-tight antialiased transition-all duration-300">
    <div class="flex justify-between items-center px-6 md:px-8 h-20 w-full max-w-none">
        <a class="text-2xl font-extrabold tracking-tighter text-on-surface hover:text-primary transition-colors duration-300" href="index.php">BitBuddy</a>

        <div id="bb-nav-group" class="relative hidden md:flex items-center gap-10" data-active="<?php echo htmlspecialchars($active_page ?? ''); ?>">
            <?php foreach ($bb_nav_items as $item):
                $is_active = ($active_page !== null && $active_page === $item['key']);
            ?>
                <a class="bb-nav-link relative font-semibold pb-2 leading-none transition-colors duration-300 <?php echo $is_active ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface'; ?>"
                   data-nav-link="<?php echo htmlspecialchars($item['key']); ?>"
                   <?php echo $is_active ? 'data-active="true"' : ''; ?>
                   href="<?php echo htmlspecialchars($item['href']); ?>"><?php echo htmlspecialchars($item['label']); ?></a>
            <?php endforeach; ?>
            <span id="bb-nav-indicator"
                  class="pointer-events-none absolute bottom-0 h-[2px] rounded-full bg-primary transition-[left,width,opacity] duration-300 ease-out"
                  style="left:0;width:0;opacity:0;"></span>
        </div>

        <div class="flex items-center gap-4 md:gap-6">
            <button type="button" onclick="ThemeManager.toggle()" aria-label="Переключить тему"
                    class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-on-surface/5 transition-all duration-300 active:scale-90">
                <span class="material-symbols-outlined" data-theme-icon style="font-variation-settings: 'FILL' 1;">dark_mode</span>
            </button>

            <?php if ($is_logged_in): ?>
                <a href="profile.php"
                   class="hidden md:inline-flex items-center justify-center px-5 py-2 rounded-full bg-primary/10 border border-primary/30 text-primary font-semibold hover:bg-primary/20 hover:shadow-glow-primary transition-all duration-300 active:scale-95"><?php echo htmlspecialchars($username); ?></a>
            <?php else: ?>
                <a href="login.php"
                   class="hidden md:inline-flex items-center justify-center px-5 py-2 rounded-full bg-primary/10 border border-primary/30 text-primary font-semibold hover:bg-primary/20 hover:shadow-glow-primary transition-all duration-300 active:scale-95">Войти</a>
            <?php endif; ?>

            <button type="button" onclick="MobileMenu.toggle()" aria-label="Меню"
                    class="md:hidden w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all duration-300">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>
        </div>
    </div>
</nav>

<script>
// Sliding underline indicator: buttons stay stationary, only a single <span>
// underneath animates its left/width to the active (and hovered) link.
(function(){
    function init() {
        var group = document.getElementById('bb-nav-group');
        if (!group) return;
        var indicator = group.querySelector('#bb-nav-indicator');
        var links = group.querySelectorAll('[data-nav-link]');
        if (!indicator || !links.length) return;

        var active = group.querySelector('[data-nav-link][data-active="true"]') || null;

        function moveTo(el, animate) {
            if (!el) {
                indicator.style.opacity = '0';
                return;
            }
            var groupRect = group.getBoundingClientRect();
            var rect = el.getBoundingClientRect();
            var left = rect.left - groupRect.left;
            var width = rect.width;
            if (!animate) {
                var prev = indicator.style.transition;
                indicator.style.transition = 'none';
                indicator.style.left = left + 'px';
                indicator.style.width = width + 'px';
                indicator.style.opacity = '1';
                // Force reflow so the next change animates.
                indicator.offsetWidth;
                indicator.style.transition = prev;
            } else {
                indicator.style.left = left + 'px';
                indicator.style.width = width + 'px';
                indicator.style.opacity = '1';
            }
        }

        // Initial placement on the active link (if any).
        moveTo(active, false);

        links.forEach(function(link) {
            link.addEventListener('mouseenter', function(){ moveTo(link, true); });
            link.addEventListener('focus',      function(){ moveTo(link, true); });
        });
        group.addEventListener('mouseleave', function(){
            if (active) moveTo(active, true); else indicator.style.opacity = '0';
        });

        window.addEventListener('resize', function(){ moveTo(active, false); });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>

<!-- Mobile Menu Overlay -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-inverse-surface/60 backdrop-blur-sm z-[60] md:hidden" onclick="MobileMenu.close()"></div>

<!-- Mobile Menu Drawer -->
<aside id="mobile-menu" class="fixed top-0 right-0 h-full w-80 max-w-[90vw] bg-surface-container backdrop-blur-[25px] border-l border-outline-variant/20 z-[70] transform translate-x-full transition-transform duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] md:hidden flex flex-col p-8 gap-2 shadow-[-20px_0_60px_rgba(0,0,0,0.25)]">
    <div class="flex justify-between items-center mb-6">
        <span class="text-xl font-black text-on-surface tracking-tighter">BitBuddy</span>
        <button type="button" onclick="MobileMenu.close()" aria-label="Закрыть"
                class="w-10 h-10 rounded-full flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5 transition-all duration-300">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <a class="flex items-center gap-3 px-3 py-3 rounded-xl <?php echo $active_page === 'home' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5'; ?> transition-colors" href="index.php" onclick="MobileMenu.close()">
        <span class="material-symbols-outlined text-xl">home</span>О нас
    </a>
    <a class="flex items-center gap-3 px-3 py-3 rounded-xl <?php echo $active_page === 'services' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5'; ?> transition-colors" href="services.php" onclick="MobileMenu.close()">
        <span class="material-symbols-outlined text-xl">grid_view</span>Услуги
    </a>
    <a class="flex items-center gap-3 px-3 py-3 rounded-xl <?php echo $active_page === 'contacts' ? 'bg-primary/10 text-primary' : 'text-on-surface-variant hover:text-on-surface hover:bg-on-surface/5'; ?> transition-colors" href="contacts.php" onclick="MobileMenu.close()">
        <span class="material-symbols-outlined text-xl">mail</span>Контакты
    </a>
    <div class="mt-auto pt-6 border-t border-outline-variant/20">
        <?php if ($is_logged_in): ?>
            <a href="profile.php" class="flex items-center gap-3 px-3 py-3 rounded-xl text-primary font-semibold hover:bg-primary/10 transition-colors" onclick="MobileMenu.close()">
                <span class="material-symbols-outlined text-xl">person</span><?php echo htmlspecialchars($username); ?>
            </a>
            <a href="logout.php" class="flex items-center gap-3 px-3 py-3 rounded-xl text-error-dim hover:bg-error/10 transition-colors">
                <span class="material-symbols-outlined text-xl">logout</span>Выйти
            </a>
        <?php else: ?>
            <a href="login.php" class="flex items-center gap-3 px-3 py-3 rounded-xl text-primary font-semibold hover:bg-primary/10 transition-colors" onclick="MobileMenu.close()">
                <span class="material-symbols-outlined text-xl">login</span>Войти
            </a>
        <?php endif; ?>
    </div>
</aside>
