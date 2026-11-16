<?php
    session_start();
    include("../connection.php");

    if (empty($_SESSION["admin"])) {
        header("Location: ../index.php?page=admin");
        exit;
    }

    $pemesanan_id  = (int) ($_POST['id'] ?? 0); // Dikirim dari <input type="hidden" name="id">
    $studio_id     = (int) ($_POST['studio_id'] ?? 0);
    $nama_pemesan  = trim($_POST['nama_pemesan'] ?? '');
    $no_hp         = trim($_POST['no_hp'] ?? '');
    $tanggal       = $_POST['tanggal'] ?? '';
    $time_slots_id = (int) ($_POST['time_slots_id'] ?? 0);
    $addons        = $_POST['addons'] ?? [];

    if (!$pemesanan_id || !$studio_id || !$nama_pemesan || !$no_hp || !$tanggal || !$time_slots_id) {
        die("Data update belum lengkap!");
    }

    // 1. Update data utama di tabel pemesanan
    $stmt = $connect->prepare("UPDATE pemesanan SET nama_pemesan = ?, no_hp = ?, tanggal = ?, studio_id = ?, time_slots_id = ? WHERE id = ?");
    $stmt->bind_param("sssiii", $nama_pemesan, $no_hp, $tanggal, $studio_id, $time_slots_id, $pemesanan_id);

    if ($stmt->execute()) {
        // 2. Hapus seluruh addon lama milik pemesanan ini
        $delAddon = $connect->prepare("DELETE FROM pemesanan_addon WHERE pemesanan_id = ?");
        $delAddon->bind_param("i", $pemesanan_id);
        $delAddon->execute();

        // 3. Masukkan ulang addon baru yang dicentang (jika ada)
        if (!empty($addons) && is_array($addons)) {
            $stmtAddon = $connect->prepare("INSERT INTO pemesanan_addon (pemesanan_id, addons_id) VALUES (?, ?)");
            foreach ($addons as $addon_id) {
                $addon_id_int = (int) $addon_id;
                $stmtAddon->bind_param("ii", $pemesanan_id, $addon_id_int);
                $stmtAddon->execute();
            }
        }

        header("Location: ../index.php?page=admin&update=sukses");
        exit;
    } else {
        echo "Gagal mengupdate booking: " . $connect->error;
    }
?>