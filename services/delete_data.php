<?php
    session_start();
    include("../connection.php");

    // Wajib cek session admin
    if (empty($_SESSION["admin"])) {
        header("Location: ../index.php?page=admin");
        exit;
    }

    $kode = $_GET['kode'] ?? '';

    if (!empty($kode)) {
        // 1. Cari id pemesanan berdasarkan kode_booking
        $stmt = $connect->prepare("SELECT id FROM pemesanan WHERE kode_booking = ?");
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        if ($data) {
            $pemesanan_id = $data['id'];

            // 2. Hapus relasi add-on terlebih dahulu
            $del_addon = $connect->prepare("DELETE FROM pemesanan_addon WHERE pemesanan_id = ?");
            $del_addon->bind_param("i", $pemesanan_id);
            $del_addon->execute();

            // 3. Hapus data utama di tabel pemesanan
            $del_main = $connect->prepare("DELETE FROM pemesanan WHERE id = ?");
            $del_main->bind_param("i", $pemesanan_id);
            $del_main->execute();

            header("Location: ../index.php?page=admin&hapus=sukses");
            exit;
        }
    }

    header("Location: ../index.php?page=admin&hapus=gagal");
    exit;
?>