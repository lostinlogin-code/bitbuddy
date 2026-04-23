<?php
/**
 * Shared footer.
 * Caller may set $active_page — same semantics as nav.php.
 */
$active_page = $active_page ?? null;

function bb_footer_link_classes(?string $current, ?string $self): string {
    if ($current !== null && $current === $self) {
        return 'text-primary underline underline-offset-4 decoration-primary/40 hover:decoration-primary transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/40 rounded';
    }
    return 'text-on-surface-variant hover:text-primary transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-primary/40 rounded';
}
?>
<footer class="w-full relative bg-surface-dim border-t border-outline-variant/25 shadow-footer text-sm tracking-wide z-20 mt-auto">
    <div class="max-w-7xl mx-auto px-6 md:px-8 py-12 md:py-16 flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex flex-col items-center md:items-start gap-2">
            <span class="text-xl font-black text-on-surface tracking-tighter">BitBuddy</span>
            <span class="text-on-surface-variant">© <?php echo date('Y'); ?> BitBuddy. The Ethereal Exchange.</span>
        </div>
        <nav class="flex flex-wrap justify-center md:justify-end gap-x-8 gap-y-4">
            <a class="<?php echo bb_footer_link_classes($active_page, 'services'); ?>" href="services.php">Услуги</a>
            <a class="<?php echo bb_footer_link_classes($active_page, 'home'); ?>" href="index.php">О нас</a>
            <a class="<?php echo bb_footer_link_classes($active_page, 'contacts'); ?>" href="contacts.php">Контакты</a>
            <a class="<?php echo bb_footer_link_classes($active_page, 'privacy'); ?>" href="privacy.php">Политика конфиденциальности</a>
        </nav>
    </div>
</footer>

<script>
// Safe confirmation for destructive forms: any <form data-confirm="...">
// prompts with the attribute value read via dataset (HTML-entity-decoded once
// by the browser, then treated as plain text — no JS context injection).
(function(){
    document.addEventListener('submit', function(ev){
        var form = ev.target;
        if (!(form instanceof HTMLFormElement)) return;
        var msg = form.dataset ? form.dataset.confirm : null;
        if (msg && !window.confirm(msg)) {
            ev.preventDefault();
        }
    }, true);
})();
</script>
