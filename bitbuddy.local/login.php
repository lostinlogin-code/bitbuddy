<?php
session_start();
require_once 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password']   ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, password_hash, role FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: profile.php');
            exit;
        } else {
            $error = 'Неверный email или пароль.';
        }
    }
}

$page_title = 'BitBuddy — Вход';
?><!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex flex-col relative overflow-x-hidden">

    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="ambient-orb w-[50vw] h-[50vw] top-[-10%] left-[-10%] animate-float"></div>
        <div class="ambient-orb w-[60vw] h-[60vw] bottom-[-20%] right-[-10%] opacity-60 animate-float" style="animation-delay:-3s"></div>
    </div>

    <?php include __DIR__ . '/includes/auth-header.php'; ?>

    <main class="flex-grow flex items-center justify-center p-6 relative z-10">
        <div class="w-full max-w-md glass-panel rounded-2xl p-8 md:p-10 shadow-card relative overflow-hidden animate-scale-in">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-[2px] bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>

            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold tracking-tight text-on-surface mb-2">Добро пожаловать</h1>
                <p class="text-on-surface-variant text-sm">Войдите, чтобы продолжить работу с BitBuddy.</p>
            </div>

            <div class="flex p-1 bg-surface-container-low rounded-lg mb-8 relative border border-outline-variant/20">
                <div class="absolute inset-y-1 left-1 w-[calc(50%-4px)] bg-surface-variant/90 border border-outline-variant/30 rounded-md shadow-sm pointer-events-none transition-transform"></div>
                <button type="button" class="flex-1 py-2 text-sm font-semibold text-primary z-10 relative">Вход</button>
                <a class="flex-1 py-2 text-sm font-medium text-on-surface-variant hover:text-on-surface transition-colors z-10 relative text-center" href="register.php">Регистрация</a>
            </div>

            <?php if ($error): ?>
                <div class="mb-6 p-4 rounded-xl bg-error/10 border border-error/30 text-error text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">error</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form class="space-y-6" method="POST" action="login.php">
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="email">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">mail</span>
                        <input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-4 py-3 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="email" name="email" placeholder="name@example.com" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"/>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-2 ml-1" for="password">Пароль</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">lock</span>
                        <input class="w-full bg-surface-container-lowest text-on-surface pl-12 pr-12 py-3 rounded-xl border border-outline-variant/20 focus:border-primary focus:outline-none transition-all placeholder:text-on-surface-variant/50" id="password" name="password" placeholder="••••••••" type="password" required/>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button" onclick="togglePassword('password', this)">
                            <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center cursor-pointer group">
                        <input class="form-checkbox rounded bg-surface-container-lowest border-outline-variant/40 text-primary focus:ring-primary h-4 w-4" type="checkbox"/>
                        <span class="ml-2 text-on-surface-variant group-hover:text-on-surface transition-colors">Запомнить меня</span>
                    </label>
                    <a class="text-primary hover:text-primary-dim transition-colors" href="contacts.php">Забыли пароль?</a>
                </div>

                <button class="glow-button w-full py-3.5 px-6 bg-gradient-to-r from-primary to-primary-fixed-dim text-on-primary font-bold rounded-xl hover:-translate-y-0.5" type="submit">
                    Войти в систему
                </button>
            </form>

            <div class="mt-8">
                <div class="relative flex items-center justify-center mb-6">
                    <div class="absolute inset-x-0 h-px bg-gradient-to-r from-transparent via-outline-variant/40 to-transparent"></div>
                    <span class="relative bg-surface-container px-4 text-xs text-on-surface-variant tracking-wider uppercase rounded-full border border-outline-variant/20">или</span>
                </div>
                <button class="w-full flex items-center justify-center gap-3 py-3 px-6 bg-surface-container-lowest/50 border border-outline-variant/20 rounded-xl text-sm font-medium text-on-surface-variant/60 cursor-not-allowed" type="button" disabled title="Скоро будет доступно">
                    <span class="material-symbols-outlined text-[20px]">login</span>
                    Продолжить с Google
                </button>
            </div>
        </div>
    </main>

    <footer class="w-full border-t border-outline-variant/20 mt-auto">
        <div class="max-w-7xl mx-auto px-6 md:px-8 py-6 flex flex-col md:flex-row justify-between items-center gap-2">
            <div class="text-lg font-black text-on-surface-variant tracking-tighter">BitBuddy</div>
            <div class="text-xs tracking-wide text-on-surface-variant/70">© <?php echo date('Y'); ?> BitBuddy. The Ethereal Exchange.</div>
        </div>
    </footer>

    <script>
        function togglePassword(inputId, btn) {
            var input = document.getElementById(inputId);
            var icon  = btn.querySelector('.material-symbols-outlined');
            if (input.type === 'password') { input.type = 'text';     icon.textContent = 'visibility'; }
            else                            { input.type = 'password'; icon.textContent = 'visibility_off'; }
        }
    </script>
</body>
</html>
