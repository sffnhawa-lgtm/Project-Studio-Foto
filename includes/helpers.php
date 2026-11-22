<?php
    function e($text) {
        return htmlspecialchars((string) $text, ENT_QUOTES);
    }

    function rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }

    function redirect($url) {
        header("Location: $url");
        exit;
    }

    function require_admin() {
        if (empty($_SESSION["admin"])) {
            redirect("../index.php?page=admin");
        }
    }

    function save_addons($connect, $pemesanan_id, $addons) {
        if (empty($addons) || !is_array($addons)) {
            return;
        }

        $stmt = $connect->prepare("INSERT INTO pemesanan_addon (pemesanan_id, addons_id) VALUES (?, ?)");
        foreach ($addons as $addon_id) {
            $addon_id_int = (int) $addon_id;
            $stmt->bind_param("ii", $pemesanan_id, $addon_id_int);
            $stmt->execute();
        }
    }

    function delete_addons($connect, $pemesanan_id) {
        $stmt = $connect->prepare("DELETE FROM pemesanan_addon WHERE pemesanan_id = ?");
        $stmt->bind_param("i", $pemesanan_id);
        $stmt->execute();
    }

    function log_activity($connect, $aksi, $detail = '') {
        $pelaku = $_SESSION["username"] ?? 'unknown';

        $stmt = $connect->prepare("INSERT INTO activity_log (username, aksi, detail, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $pelaku, $aksi, $detail);
        $stmt->execute();
    }
?>
