<?php
    session_start();
    if (empty($_SESSION["admin"]) && isset($_COOKIE["remember_admin"])) {
        $_SESSION["admin"] = TRUE;
    }

    $page = $_GET['page'] ?? 'dashboard';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['Fira Code', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .terminal-bg { background-color: #0d1117; }
        .terminal-text { color: #38BDF8; }
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <title>Document</title>
</head>
<body>
    <header class="bg-slate-900 text-white shadow-2xl sticky top-0 z-40 border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3.5 cursor-pointer" onclick="switch_tab('dashboard')">
                <div class="bg-gradient-to-tr from-rose-500 via-pink-500 to-violet-600 text-white p-2.5 rounded-2xl shadow-lg shadow-rose-500/20 font-bold text-xl flex items-center justify-center w-11 h-11">
                    <i class="fa-solid fa-camera-retro"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-rose-400 via-pink-300 to-violet-400 bg-clip-text text-transparent">Lumina Studio</h1>
                    </div>
                    <p class="text-xs text-slate-400">Integrated Photo Studio Management</p>
                </div>
            </div>

            <nav class="flex bg-slate-800/90 p-1.5 rounded-2xl border border-slate-700/60 text-xs sm:text-sm font-medium overflow-x-auto max-w-full hide-scrollbar">
                <a href="?page=dashboard" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap <?= $page !== 'admin' ? 'shadow-sm bg-rose-600 text-white font-semibold' : 'text-slate-400 hover:text-white' ?>">
                    <i class="fa-solid fa-desktop mr-1.5"></i> Website (Client)
                </a>
                <a href="?page=admin" class="px-4 py-2 rounded-xl transition-all whitespace-nowrap flex items-center <?= $page === 'admin' ? 'shadow-sm bg-rose-600 text-white font-semibold' : 'text-slate-400 hover:text-white' ?>">
                    <i class="fa-solid fa-lock mr-1.5"></i> Admin Portal
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
        <?php
        if ($page === 'admin') {
            if(empty($_SESSION["admin"])){
                include 'login.php';
            } else {
                include 'admin.php';
            }
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