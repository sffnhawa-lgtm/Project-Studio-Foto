<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    require_admin();

    $pemesanan_id  = (int) ($_POST['id'] ?? 0);
    $studio_id     = (int) ($_POST['studio_id'] ?? 0);
    $nama_pemesan  = trim($_POST['nama_pemesan'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $tanggal       = $_POST['tanggal'] ?? '';
    $time_slots_id = (int) ($_POST['time_slots_id'] ?? 0);
    $addons        = $_POST['addons'] ?? [];

    if (!$pemesanan_id || !$studio_id || !$nama_pemesan || !$no_hp || !$tanggal || !$time_slots_id){
        die("Data update belum lengkap!");
    }

    $stmt = $connect->prepare("UPDATE pemesanan SET nama_pemesan = ?, no_hp = ?, tanggal = ?, studio_id = ?, time_slots_id = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $nama_pemesan, $no_hp, $tanggal, $studio_id, $time_slots_id, $pemesanan_id);

    if ($stmt->execute()){
        delete_addons($connect, $pemesanan_id);
        save_addons($connect, $pemesanan_id, $addons);

        redirect("../index.php?page=admin&update=sukses");
    } else {
        echo "Gagal mengupdate booking: " . $connect->error;
    }
?>
