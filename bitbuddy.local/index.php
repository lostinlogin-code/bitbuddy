<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

$page_title    = 'BitBuddy — The Ethereal Exchange';
$include_smoke = true;
$active_page   = 'home';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body overflow-x-hidden relative min-h-screen flex flex-col">

    <!-- Ambient orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[800px] h-[800px] top-[-200px] left-[-200px] animate-float"></div>
        <div class="ambient-orb w-[600px] h-[600px] top-[40%] right-[-100px] opacity-60 animate-float" style="animation-delay:-3s"></div>
        <div class="ambient-orb w-[1000px] h-[1000px] bottom-[-300px] left-[20%] opacity-40 animate-float" style="animation-delay:-5s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="relative z-10 pt-20 flex-grow">

        <!-- Hero -->
        <section class="min-h-screen flex items-center justify-center relative px-6 md:px-8 py-20 overflow-hidden">
            <canvas id="smoke-canvas" class="absolute inset-0 w-full h-full pointer-events-none" style="z-index:0;"></canvas>
            <div class="max-w-5xl mx-auto flex flex-col items-center text-center space-y-8 relative" style="z-index:2;">
                <div class="glass-panel px-6 py-2 rounded-full inline-flex items-center gap-2 text-sm font-medium tracking-wide animate-fade-in-down">
                    <span class="text-primary">✦</span>
                    <span class="text-on-surface-variant">Премиум цифровые услуги</span>
                </div>
                <h1 class="text-5xl md:text-[64px] font-headline font-extrabold leading-[1.1] tracking-[-0.02em] text-on-surface max-w-4xl animate-fade-in-up animate-delay-1">
                    Ваши цифровые проблемы, решаемые <span class="text-gradient">быстро</span>
                </h1>
                <p class="text-lg md:text-xl text-on-surface-variant max-w-2xl font-medium animate-fade-in-up animate-delay-2">
                    Эксклюзивный доступ к передовым решениям. Мы не просто пишем код — мы создаём цифровой опыт будущего.
                </p>
                <div class="pt-8 animate-fade-in-up animate-delay-3">
                    <a href="services.php" class="glow-button bg-primary text-on-primary font-bold text-lg px-10 py-4 rounded-xl inline-flex items-center gap-3 hover:-translate-y-0.5">
                        <span>Начать проект</span>
                        <span class="material-symbols-outlined font-bold">arrow_forward</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section class="py-24 px-6 md:px-8 relative z-10 bg-surface-container-low/60 border-y border-outline-variant/10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
                <div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-0">
                    <span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="500">0+</span>
                    <span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Довольных клиентов</span>
                </div>
                <div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-12">
                    <span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="99.9" data-suffix="%" data-decimal="1">0%</span>
                    <span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Uptime систем</span>
                </div>
                <div class="stat-card glass-panel p-10 rounded-2xl flex flex-col items-center justify-center text-center md:mt-24">
                    <span class="stat-value text-[48px] font-black text-primary mb-2 tracking-tighter" data-target="12">0</span>
                    <span class="text-on-surface-variant font-medium text-sm tracking-wide uppercase">Наград за дизайн</span>
                </div>
            </div>
        </section>

        <!-- Services -->
        <section class="py-24 md:py-32 px-6 md:px-8 relative z-10">
            <div class="max-w-7xl mx-auto">
                <div class="mb-16 md:mb-20 pl-4 md:pl-12 border-l-2 border-primary/40 card-animate">
                    <h2 class="text-4xl md:text-5xl font-headline font-bold text-on-surface mb-4 tracking-tight">Наши решения</h2>
                    <p class="text-on-surface-variant max-w-xl text-lg">Комплексный подход к вашему цифровому присутствию.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php
                    $services_preview = [
                        ['icon' => 'code',             'title' => 'Веб-разработка',   'desc' => 'Высокопроизводительные приложения на современных стеках. От архитектуры до деплоя.', 'price' => 'от $1k'],
                        ['icon' => 'design_services', 'title' => 'UI/UX Дизайн',      'desc' => 'Интерфейсы, которые продают. Глубокая аналитика и премиальный визуальный стиль.',   'price' => 'от $800'],
                        ['icon' => 'security',         'title' => 'Кибербезопасность', 'desc' => 'Аудит и защита вашей инфраструктуры по высочайшим стандартам безопасности.',      'price' => 'от $2k'],
                    ];
                    foreach ($services_preview as $s): ?>
                        <div class="card-animate glass-panel p-8 rounded-2xl hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300 group flex flex-col min-h-[380px]">
                            <div class="mb-auto">
                                <span class="material-symbols-outlined text-4xl text-primary mb-6 block" style="font-variation-settings: 'FILL' 1;"><?php echo $s['icon']; ?></span>
                                <h3 class="text-2xl font-bold text-on-surface mb-3"><?php echo $s['title']; ?></h3>
                                <p class="text-on-surface-variant text-sm leading-relaxed"><?php echo $s['desc']; ?></p>
                            </div>
                            <div class="flex justify-between items-end mt-8">
                                <a href="services.php" class="text-sm font-medium text-primary opacity-0 group-hover:opacity-100 translate-x-[-4px] group-hover:translate-x-0 transition-all duration-300 flex items-center gap-1">
                                    Подробнее <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                                <span class="text-[28px] font-bold text-primary"><?php echo $s['price']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-24 md:py-32 px-6 md:px-8 relative z-10 bg-surface-container-low/60 border-y border-outline-variant/10">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center gap-16">
                <div class="w-full md:w-1/3 flex flex-col items-start pr-8 card-animate">
                    <h2 class="text-4xl font-headline font-bold text-on-surface mb-6 tracking-tight">Что говорят<br/>партнёры</h2>
                    <p class="text-on-surface-variant text-lg">Доверие, подкреплённое результатами.</p>
                </div>
                <div class="w-full md:w-2/3 flex flex-col space-y-8 relative">
                    <div class="card-animate glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-card transform md:-translate-x-8 z-20">
                        <div class="flex text-primary mb-4">
                            <?php for ($i = 0; $i < 5; $i++): ?><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span><?php endfor; ?>
                        </div>
                        <p class="text-on-surface font-medium text-lg mb-6 italic">«Команда BitBuddy полностью переосмыслила нашу платформу. Конверсия выросла на 40% за первый месяц.»</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-surface-container overflow-hidden ring-1 ring-outline-variant/30">
                                <img alt="Алексей Смирнов" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC8zETM7H8WLjpa1Crg-9f7dKVmjtPR0rFXIkw2pdknajrRn5hd40S_7K0eyDuWSORuDzjTiqOOvfxe3GvfTex0gIJGqYcuDLcfcuJ3wFdMseRP4gXEIFnU-s5llDs2s-Do4q3X5XNU7oD8nZw-6_O7UOIZpIRq7a2uYrsgzphN_KyGlRNGUKCcjZBR3zrtQ3s-UG6nPRqHur9EXKYWTHN8L0bvwWAIoIudDnpcVAWb7DsEUkqIZU-uAYiWlkswNi1KCWKcWCWbC_Rx"/>
                            </div>
                            <div>
                                <h4 class="text-on-surface font-bold">Алексей Смирнов</h4>
                                <span class="text-on-surface-variant text-sm">CEO, TechNova</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-animate glass-panel p-8 rounded-2xl bg-surface-container-highest shadow-card transform md:translate-x-12 z-10">
                        <div class="flex text-primary mb-4">
                            <?php for ($i = 0; $i < 5; $i++): ?><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span><?php endfor; ?>
                        </div>
                        <p class="text-on-surface font-medium text-lg mb-6 italic">«Их подход к дизайну не имеет равных. Интерфейс выглядит невероятно премиально и работает безупречно.»</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-surface-container overflow-hidden ring-1 ring-outline-variant/30">
                                <img alt="Елена Волкова" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCH_Vb0wdmdie1NnohgPOsnkxKG-v7Q6aCClS_kljLyAL7st8aXUvBhknJawvP3hLvAhpbR_6qXOF9u78oBUaBklFh__wYJBR5BPh5V5150W7u21n8rVOIry-tvmENbTpkFF0SZay6tomaVRXEERMxCcUhrGv3IRBU5vePOTgKNGguk1qz4G7dYX5I4QHbwyqI7jzL6mva4wW-oJbkvl5QbLKV53t_1msoKiLDZ1ZftUBwu18GsjJt0BcJ6fcWSI4478MH_9ppWm6Cy"/>
                            </div>
                            <div>
                                <h4 class="text-on-surface font-bold">Елена Волкова</h4>
                                <span class="text-on-surface-variant text-sm">Creative Director, ArtSpace</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
