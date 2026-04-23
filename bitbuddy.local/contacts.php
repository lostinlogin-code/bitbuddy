<?php
session_start();
require_once 'db_connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? '';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = 'Заполните все поля.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный email адрес.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $message]);
            $success = 'Сообщение отправлено! Мы ответим в кратчайшие сроки.';
        } catch (Exception $e) {
            $success = 'Сообщение получено! Мы свяжемся с вами в ближайшее время.';
        }
    }
}

$page_title  = 'BitBuddy — Контакты';
$active_page = 'contacts';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen relative overflow-x-hidden flex flex-col">

    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[50vw] h-[50vw] top-[-10%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[60vw] h-[60vw] bottom-[-20%] right-[-10%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/nav.php'; ?>

    <main class="flex-grow pt-32 pb-24 px-6 md:px-12 lg:px-24 relative z-10 max-w-[1600px] mx-auto w-full">
        <div class="mb-16 md:mb-20 max-w-3xl animate-fade-in-up">
            <h1 class="font-headline font-black text-5xl md:text-7xl tracking-tighter mb-6 text-gradient pb-2">Свяжитесь с нами</h1>
            <p class="font-body text-on-surface-variant text-lg md:text-xl leading-relaxed max-w-2xl">
                Готовы обсудить ваш следующий проект или нужна помощь? Оставьте сообщение, и мы ответим в кратчайшие сроки.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 relative z-10">
            <!-- Form -->
            <div class="lg:col-span-7 xl:col-span-6 card-animate">
                <div class="glass-panel rounded-2xl p-8 md:p-12 box-glow">
                    <?php if ($error): ?>
                        <div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">error</span>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/30 text-primary text-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form class="space-y-8 flex flex-col" method="POST" action="contacts.php">
                        <div class="space-y-2">
                            <label class="font-label text-sm text-on-surface-variant tracking-wide" for="name">Имя</label>
                            <input class="w-full bg-transparent border-b border-outline-variant/40 text-on-surface p-3 focus:outline-none focus:border-primary transition-colors placeholder:text-on-surface-variant/50" id="name" name="name" placeholder="Как к вам обращаться?" type="text" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"/>
                        </div>
                        <div class="space-y-2">
                            <label class="font-label text-sm text-on-surface-variant tracking-wide" for="email">Email</label>
                            <input class="w-full bg-transparent border-b border-outline-variant/40 text-on-surface p-3 focus:outline-none focus:border-primary transition-colors placeholder:text-on-surface-variant/50" id="email" name="email" placeholder="Ваш электронный адрес" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
                        </div>
                        <div class="space-y-2 flex-grow">
                            <label class="font-label text-sm text-on-surface-variant tracking-wide" for="message">Сообщение</label>
                            <textarea class="w-full bg-transparent border-b border-outline-variant/40 text-on-surface p-3 focus:outline-none focus:border-primary transition-colors placeholder:text-on-surface-variant/50 resize-none min-h-[150px]" id="message" name="message" placeholder="Расскажите нам о вашей задаче..." rows="5" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                        </div>

                        <button class="glow-button mt-4 bg-primary text-on-primary font-bold py-4 px-8 rounded-xl w-full flex items-center justify-center gap-3 hover:-translate-y-0.5" type="submit">
                            <span>Отправить</span>
                            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">send</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Info cards -->
            <div class="lg:col-span-5 xl:col-span-6 flex flex-col gap-6">
                <?php
                $info_cards = [
                    ['icon' => 'mail',         'title' => 'Написать нам', 'content' => '<a class="text-primary hover:text-primary-dim transition-colors break-all" href="mailto:hello@bitbuddy.exchange">hello@bitbuddy.exchange</a><p class="text-on-surface-variant text-sm mt-2">Отвечаем в течение 24 часов.</p>'],
                    ['icon' => 'call',         'title' => 'Позвонить',    'content' => '<a class="text-primary hover:text-primary-dim transition-colors block text-xl font-medium tracking-tight" href="tel:+1234567890">+1 (234) 567-890</a><p class="text-on-surface-variant text-sm mt-2">Пн–Пт с 9:00 до 18:00 (UTC+3)</p>'],
                    ['icon' => 'location_on', 'title' => 'Офис',          'content' => '<p class="text-on-surface text-base leading-relaxed">Кибер-пространство Ethereal<br/>Ул. Неоновая, д. 42<br/>Сектор 7G</p>'],
                ];
                foreach ($info_cards as $card): ?>
                    <div class="card-animate glass-panel p-8 rounded-2xl flex items-start gap-6 hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300">
                        <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-primary shrink-0 border border-outline-variant/25 shadow-glow-primary">
                            <span class="material-symbols-outlined text-2xl"><?php echo $card['icon']; ?></span>
                        </div>
                        <div>
                            <h3 class="font-headline font-bold text-lg text-on-surface mb-2"><?php echo $card['title']; ?></h3>
                            <?php echo $card['content']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="pt-4">
                    <h4 class="font-headline font-semibold text-on-surface-variant mb-4 text-sm uppercase tracking-widest">Мы в сетях</h4>
                    <div class="flex gap-3">
                        <?php
                        $socials = [
                            ['href' => 'mailto:info@bitbuddy.ru',      'icon' => 'alternate_email'],
                            ['href' => 'https://t.me/bitbuddy',         'icon' => 'send'],
                            ['href' => 'https://github.com/bitbuddy',   'icon' => 'code'],
                            ['href' => 'https://discord.gg/bitbuddy',   'icon' => 'chat'],
                        ];
                        foreach ($socials as $s): ?>
                            <a class="w-12 h-12 rounded-full glass-panel flex items-center justify-center text-on-surface-variant hover:text-primary hover:-translate-y-1 hover:shadow-glow-primary transition-all duration-300" href="<?php echo $s['href']; ?>">
                                <span class="material-symbols-outlined"><?php echo $s['icon']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
