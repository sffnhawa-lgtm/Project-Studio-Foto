<?php
    session_start();
    include_once("connection.php");
    include_once("includes/helpers.php");

    if (empty($_SESSION["admin"]) && !empty($_COOKIE["remember_admin"])) {
        $stmt = $connect->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $_COOKIE["remember_admin"]);
        $stmt->execute();
        $user_cookie = $stmt->get_result()->fetch_assoc();

        if ($user_cookie) {
            $_SESSION["admin"]    = TRUE;
            $_SESSION["username"] = $user_cookie["username"];
        }
    }

    $page       = $_GET['page'] ?? 'dashboard';
    $page_title = 'Lumina Studio - Reservasi Studio Foto';

    $menu = [
        'dashboard' => ['Website (Client)', 'fa-desktop'],
        'admin'     => ['Admin Portal',     'fa-lock'],
    ];
    $active_menu = ($page === 'admin') ? 'admin' : 'dashboard';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <header class="bg-slate-900 text-white shadow-2xl sticky top-0 z-40 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row justify-between items-center gap-4">
            <a href="?page=dashboard" class="flex items-center space-x-3.5">
                <div class="bg-gradient-to-tr from-rose-500 via-pink-500 to-violet-600 text-white p-2.5 rounded-2xl shadow-lg shadow-rose-500/20 font-bold text-xl flex items-center justify-center w-11 h-11">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-rose-400 via-pink-300 to-violet-400 bg-clip-text text-transparent">Lumina Studio</h1>
                    </div>
                    <p class="text-xs text-slate-400">Integrated Photo Studio Management</p>
                </div>
            </a>

            <nav class="flex bg-slate-800/90 p-1.5 rounded-2xl border border-slate-700/60 text-xs sm:text-sm font-medium overflow-x-auto max-w-full hide-scrollbar">
                <?php foreach ($menu as $key => [$label, $icon]): ?>
                    <a href="?page=<?= $key ?>" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap flex items-center <?= ($key === $active_menu) ? 'shadow-sm bg-rose-600 text-white font-semibold' : 'text-slate-400 hover:text-white' ?>">
                        <i class="fa-solid <?= $icon ?> mr-1.5"></i> <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
        <?php
        if ($page === 'admin'){
            include (empty($_SESSION["admin"]) ? 'login.php' : 'admin.php');
        } else {
            include 'dashboard.php';
        }

        if (!empty($_GET['kode'])){
            include 'struk.php';
        }
        ?>
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>© 2026 Lumina Studio Enterprise</p>
    </footer>
</body>
</html>
