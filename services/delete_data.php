<?php
    session_start();
    include("../connection.php");
    include("../includes/helpers.php");

    require_admin();

    $kode = $_GET['kode'] ?? '';

    if (!empty($kode)){
        $stmt = $connect->prepare("SELECT id FROM pemesanan WHERE kode_booking = ?");
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        if ($data){
            $pemesanan_id = $data['id'];

            delete_addons($connect, $pemesanan_id);

            $del_main = $connect->prepare("DELETE FROM pemesanan WHERE id = ?");
            $del_main->bind_param("i", $pemesanan_id);
            $del_main->execute();

            redirect("../index.php?page=admin&hapus=sukses");
        }
    }

    redirect("../index.php?page=admin&hapus=gagal");
?>
