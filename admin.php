<?php
    include("connection.php");

    $bookings = $connect->query( "
        SELECT 
            p.id,
            p.kode_booking,
            p.nama_pemesan,
            p.no_hp,
            s.nama_studio,
            p.tanggal,
            ts.slot_waktu,
            COALESCE(GROUP_CONCAT(a.nama_addon SEPARATOR ', '), '-') AS detail_addons,
            (s.harga_per_jam + COALESCE(SUM(a.harga), 0)) AS total_harga
        FROM pemesanan p
        JOIN studio s ON p.studio_id = s.id
        JOIN time_slots ts ON p.time_slots_id = ts.id
        LEFT JOIN pemesanan_addon pa ON p.id = pa.pemesanan_id
        LEFT JOIN addons a ON pa.addons_id = a.id
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC) ?? [];

    $total_pendapatan = 0;
    $total_pesanan    = count($bookings);
    $hitung_studio    = [];
    $sesi_fotografer  = 0;

    foreach ($bookings as $b) {
        $total_pendapatan += $b['total_harga'];

        $nama_st = $b['nama_studio'];
        $hitung_studio[$nama_st] = ($hitung_studio[$nama_st] ?? 0) + 1;

        if (stripos($b['detail_addons'], 'Fotografer') !== false) {
            $sesi_fotografer++;
        }
    }

    $studio_favorit = '-';
    if (!empty($hitung_studio)) {
        arsort($hitung_studio);
        $studio_favorit = array_key_first($hitung_studio);
    }

    $persen_fotografer = $total_pesanan > 0 
        ? round(($sesi_fotografer / $total_pesanan) * 100) 
        : 0;
?>


<head>
    <link rel="stylesheet" href="style/admin.css">
    <title>Document</title>
</head>

<div id="admin-dashboard-view" class="admin-dashboard">
    <!-- Session Active Header Badge -->
    <div class="session-banner">
        <div class="session-info-group">
            <div class="session-shield-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h4 class="session-title">Statistik & Manajemen Booking</h4>
                <p class="session-subtext">Login sebagai: <span class="text-rose-light">Administrator</p>
            </div>
        </div>

        <form method="POST" action="services/process_logout.php">
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-power-off"></i> Logout
            </button>
        </form>
    </div>

    <!-- Stats Dashboard Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div>
                <p class="stat-label">Total Pendapatan</p>
                <h4 id="stat-revenue" class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h4>
            </div>
            <div class="stat-icon icon-emerald">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-label">Total Pemesanan</p>
                <h4 id="stat-total-booking" class="stat-value"><?= $total_pesanan?> Transaksi</h4>
            </div>
            <div class="stat-icon icon-rose">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-label">Studio Terfavorit</p>
                <h4 id="stat-fav-studio" class="stat-value-sm"><?= $studio_favorit?></h4>
            </div>
            <div class="stat-icon icon-violet">
                <i class="fa-solid fa-crown"></i>
            </div>
        </div>

        <div class="stat-card">
            <div>
                <p class="stat-label">Penggunaan Fotografer</p>
                <h4 id="stat-photographer" class="stat-value"><?= $persen_fotografer?>% Sesi</h4>
            </div>
            <div class="stat-icon icon-amber">
                <i class="fa-solid fa-camera"></i>
            </div>
        </div>
    </div>

    <!-- Booking Management Table -->
    <div class="table-card">
        <div class="table-header-bar">
            <div>
                <h3 class="table-title">
                    <i class="fa-solid fa-list-check"></i> Kelola Transaksi Pemesanan Client
                </h3>
                <p class="table-subtitle">Admin dapat memantau, membatalkan, atau mengubah status pembayaran.</p>
            </div>
            <a href="services/delete_all_data.php" 
                onclick="return confirm('Yakin ingin menghapus SEMUA data pemesanan?')" 
                class="btn-reset">
                <i class="fa-solid fa-trash-can"></i> Reset Data Pemesanan
            </a>
        </div>

        <div class="table-responsive">
            <table class="booking-table">
                <thead>
                    <tr>
                        <th>ID / Booking Code</th>
                        <th>Nama Client</th>
                        <th>Ruangan Studio</th>
                        <th>Jadwal & Slot Jam</th>
                        <th>Detail Add-Ons</th>
                        <th>Total Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="admin-booking-rows" class="divide-y divide-slate-100">
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                                Belum ada data pemesanan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $row): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <!-- 1. ID / Booking Code -->
                                <td class="px-4 py-3.5 font-mono font-semibold text-rose-600">
                                    <?= htmlspecialchars($row['kode_booking']) ?>
                                </td>

                                <!-- 2. Nama Client -->
                                <td class="px-4 py-3.5">
                                    <div class="font-semibold text-slate-800"><?= htmlspecialchars($row['nama_pemesan']) ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($row['no_hp']) ?></div>
                                </td>

                                <!-- 3. Ruangan Studio -->
                                <td class="px-4 py-3.5 font-medium text-slate-700">
                                    <?= htmlspecialchars($row['nama_studio']) ?>
                                </td>

                                <!-- 4. Jadwal & Slot Jam -->
                                <td class="px-4 py-3.5">
                                    <div class="font-medium text-slate-800"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($row['slot_waktu']) ?></div>
                                </td>

                                <!-- 5. Detail Add-Ons -->
                                <td class="px-4 py-3.5 text-xs text-slate-600">
                                    <?= htmlspecialchars($row['detail_addons']) ?>
                                </td>

                                <!-- 6. Total Harga -->
                                <td class="px-4 py-3.5 font-bold text-slate-800">
                                    Rp <?= number_format($row['total_harga'], 0, ',', '.') ?>
                                </td>

                                <!-- 7. Aksi -->
                                <td class="px-4 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <!-- Tombol Edit (Arahkan ke halaman edit dengan parameter kode_booking) -->
                                        <a href="form_edit.php?kode=<?= urlencode($row['kode_booking']) ?>" 
                                        class="text-amber-600 hover:text-amber-800 font-semibold text-xs inline-flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        <span class="text-slate-300">|</span>

                                        <!-- Tombol Hapus -->
                                        <a href="services/delete_data.php?kode=<?= urlencode($row['kode_booking']) ?>" 
                                        onclick="return confirm('Hapus pemesanan ini?')" 
                                        class="text-rose-500 hover:text-rose-700 font-semibold text-xs inline-flex items-center gap-1">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>