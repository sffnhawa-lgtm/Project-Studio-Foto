<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    require_admin();

    $total = $connect->query("SELECT COUNT(*) AS total FROM pemesanan")->fetch_assoc()['total'];

    $connect->query("DELETE FROM pemesanan_addon");
    $connect->query("DELETE FROM pemesanan");

    $connect->query("ALTER TABLE pemesanan_addon AUTO_INCREMENT = 1");
    $connect->query("ALTER TABLE pemesanan AUTO_INCREMENT = 1");

    log_activity($connect, 'hapus_semua', "Hapus semua data pemesanan ($total data)");

    redirect("../index.php?page=admin&reset=sukses");
?>
