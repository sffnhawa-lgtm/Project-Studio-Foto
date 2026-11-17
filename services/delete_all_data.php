<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    require_admin();

    $connect->query("DELETE FROM pemesanan_addon");
    $connect->query("DELETE FROM pemesanan");

    $connect->query("ALTER TABLE pemesanan_addon AUTO_INCREMENT = 1");
    $connect->query("ALTER TABLE pemesanan AUTO_INCREMENT = 1");

    redirect("../index.php?page=admin&reset=sukses");
?>
