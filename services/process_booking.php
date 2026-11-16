<?php
    include("../connection.php");

    $studio_id     = (int) ($_POST['studio_id'] ?? 0);
    $nama_pemesan  = trim($_POST['nama_pemesan'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $tanggal       = $_POST['tanggal'] ?? '';
    $time_slots_id = (int) ($_POST['time_slots_id'] ?? 0);
    $addons        = $_POST['addons'] ?? [];
    $is_remebered = isset($_POST["remember"]) ?? FALSE;

    if (!$studio_id || !$nama_pemesan || !$no_hp || !$tanggal || !$time_slots_id) {
        die("Data pemesanan belum lengkap!");
    }

    $kode_booking = 'LUM-' . date('Ymd') . '-' . rand(100, 999);

    $stmt = $connect->prepare("INSERT INTO pemesanan (kode_booking, nama_pemesan, no_hp, tanggal, studio_id, time_slots_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $kode_booking, $nama_pemesan, $no_hp, $tanggal, $studio_id, $time_slots_id);

    if ($stmt->execute()) {
        $pemesanan_id = $connect->insert_id;

        if (!empty($addons) && is_array($addons)) {
            $stmtAddon = $connect->prepare("INSERT INTO pemesanan_addon (pemesanan_id, addons_id) VALUES (?, ?)");
            foreach ($addons as $addon_id) {
                $addon_id_int = (int) $addon_id;
                $stmtAddon->bind_param("ii", $pemesanan_id, $addon_id_int);
                $stmtAddon->execute();
            }
        }

        if($is_remebered){
            setcookie("nama_pemesan", $nama_pemesan, time() + (86400 * 7), "/");
            setcookie("no_hp", $no_hp, time() + (86400 * 7), "/");
        }

        header("Location: ../index.php?page=dashboard&kode=" . urlencode($kode_booking) . "#bagian-booking");
        exit;
    } else {
        echo "Gagal menyimpan booking: " . $connect->error;
    }
?>