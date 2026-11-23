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

<body class="site-body">
    <header class="site-header">
        <div class="header-inner">
            <a href="?page=dashboard" class="brand-link">
                <div class="brand-icon">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <div>
                    <h1 class="brand-name">Lumina Studio</h1>
                    <p class="brand-tagline">Integrated Photo Studio Management</p>
                </div>
            </a>

            <nav class="main-nav hide-scrollbar">
                <?php foreach ($menu as $key => [$label, $icon]): ?>
                    <a href="?page=<?= $key ?>" class="nav-link <?= ($key === $active_menu) ? 'active' : '' ?>">
                        <i class="fa-solid <?= $icon ?>"></i> <?= $label ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
        <?php
        if (in_array($page, ['admin', 'register'])) {
            if (!empty($_SESSION["admin"])) {
                include 'admin.php';
            } else {
                include ($page === 'register' ? 'register.php' : 'login.php');
            }
        } else {
            include 'dashboard.php';
        }

        if (!empty($_GET['kode'])) {
            include 'struk.php';
        }
        ?>
    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>© 2026 Lumina Studio Enterprise</p>
    </footer>
</body>
</html>
