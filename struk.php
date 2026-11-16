<?php
    include("connection.php");

    $kode = $_GET['kode'];
    $stmt = $connect->prepare("
        SELECT p.*, s.nama_studio, s.harga_per_jam, t.slot_waktu 
        FROM pemesanan p
        JOIN studio s ON p.studio_id = s.id
        JOIN time_slots t ON p.time_slots_id = t.id
        WHERE p.kode_booking = ?
    ");
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $strukData = $stmt->get_result()->fetch_assoc();

    if ($strukData) {
        $stmtAddon = $connect->prepare("
            SELECT a.nama_addon, a.harga 
            FROM pemesanan_addon pa
            JOIN addons a ON pa.addons_id = a.id
            WHERE pa.pemesanan_id = ?
        ");
        $stmtAddon->bind_param("i", $strukData['id']);
        $stmtAddon->execute();
        $addonsDipilih = $stmtAddon->get_result()->fetch_all(MYSQLI_ASSOC);

        $totalBayar = (int)$strukData['harga_per_jam'];
        $namaAddons = [];
        foreach ($addonsDipilih as $ad) {
            $totalBayar += (int)$ad['harga'];
            $namaAddons[] = $ad['nama_addon'];
        }

        $strukData['addons_text'] = !empty($namaAddons) ? implode(', ', $namaAddons) : 'Tidak ada';
        $strukData['total_bayar'] = $totalBayar;
    }
?>


<head>
    <link rel="stylesheet" href="style/struk.css">
    <title>Document</title>
</head>

<div id="receipt-modal" class="modal-overlay">
    <div class="modal-card">
        <a href="index.php?page=dashboard" class="modal-close"><i class="fa-solid fa-xmark"></i></a>

        <div id="receipt-print-area" class="receipt-body">
            <div class="receipt-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <h3 class="studio-title">LUMINA STUDIO</h3>
                <p class="studio-subtitle">Bukti Reservasi Official E-Receipt</p>
            </div>

            <div class="receipt-details">
                <div class="detail-row font-mono">
                    <span class="detail-label">Kode Booking:</span>
                    <span class="detail-val font-bold"><?= htmlspecialchars($strukData['kode_booking']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Client:</span>
                    <span class="detail-val font-semibold"><?= htmlspecialchars($strukData['nama_pemesan']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ruangan Studio:</span>
                    <span class="detail-val font-semibold"><?= htmlspecialchars($strukData['nama_studio']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Sesi:</span>
                    <span class="detail-val font-semibold"><?= date('d M Y', strtotime($strukData['tanggal'])) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Slot Waktu:</span>
                    <span class="detail-val-accent font-bold"><?= htmlspecialchars($strukData['slot_waktu']) ?> WIB</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Layanan Add-ons:</span>
                    <span class="detail-val font-semibold text-right"><?= htmlspecialchars($strukData['addons_text']) ?></span>
                </div>
                <div class="detail-total">
                    <span class="total-label">Total Pembayaran:</span>
                    <span class="total-val">Rp <?= number_format($strukData['total_bayar'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="modal-actions">
            <button type="button" onclick="window.print()" class="btn btn-dark">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
            <a href="index.php?page=dashboard" class="btn btn-primary">
                Tutup
            </a>
        </div>
    </div>
</div>