<?php
    session_start();
    include("../connection.php");

    if (empty($_SESSION["admin"])) {
        header("Location: ../index.php?page=admin");
        exit;
    }

    $connect->query("DELETE FROM pemesanan_addon");
    $connect->query("DELETE FROM pemesanan");

    $connect->query("ALTER TABLE pemesanan_addon AUTO_INCREMENT = 1");
    $connect->query("ALTER TABLE pemesanan AUTO_INCREMENT = 1");

    header("Location: ../index.php?page=admin&reset=sukses");
    exit;
?>