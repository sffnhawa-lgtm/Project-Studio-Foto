<?php
    include("../connection.php");
    include("../includes/helpers.php");

    $studio_id     = (int) ($_POST['studio_id'] ?? 0);
    $nama_pemesan  = trim($_POST['nama_pemesan'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $tanggal       = $_POST['tanggal'] ?? '';
    $time_slots_id = (int) ($_POST['time_slots_id'] ?? 0);
    $addons        = $_POST['addons'] ?? [];
    $is_remembered = isset($_POST["remember"]);

    if (!$studio_id || !$nama_pemesan || !$no_hp || !$tanggal || !$time_slots_id){
        die("Data pemesanan belum lengkap!");
    }

    $kode_booking = 'LUM-' . date('Ymd') . '-' . rand(100, 999);

    $stmt = $connect->prepare("INSERT INTO pemesanan (kode_booking, nama_pemesan, no_hp, tanggal, studio_id, time_slots_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssii", $kode_booking, $nama_pemesan, $no_hp, $tanggal, $studio_id, $time_slots_id);

    if ($stmt->execute()){
        save_addons($connect, $connect->insert_id, $addons);

        if ($is_remembered){
            setcookie("nama_pemesan", $nama_pemesan, time() + (86400 * 7), "/");
            setcookie("no_hp", $no_hp, time() + (86400 * 7), "/");
        }

        redirect("../index.php?page=dashboard&kode=" . urlencode($kode_booking) . "#bagian-booking");
    } else {
        echo "Gagal menyimpan booking: " . $connect->error;
    }
?>
