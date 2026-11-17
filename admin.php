<?php
    include_once("connection.php");
    include_once("includes/helpers.php");

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

    foreach ($bookings as $b){
        $total_pendapatan += $b['total_harga'];

        $nama_st = $b['nama_studio'];
        $hitung_studio[$nama_st] = ($hitung_studio[$nama_st] ?? 0) + 1;

        if (stripos($b['detail_addons'], 'Fotografer') !== false){
            $sesi_fotografer++;
        }
    }

    $studio_favorit = '-';
    if (!empty($hitung_studio)){
        arsort($hitung_studio);
        $studio_favorit = array_key_first($hitung_studio);
    }

    $persen_fotografer = $total_pesanan > 0 
        ? round(($sesi_fotografer / $total_pesanan) * 100) 
        : 0;

    $stats = [
        ['Total Pendapatan',       rupiah($total_pendapatan),       'stat-value',    'icon-emerald', 'fa-money-bill-wave', 'stat-revenue'],
        ['Total Pemesanan',        $total_pesanan . ' Transaksi',   'stat-value',    'icon-rose',    'fa-calendar-check',  'stat-total-booking'],
        ['Studio Terfavorit',      $studio_favorit,                 'stat-value-sm', 'icon-violet',  'fa-crown',           'stat-fav-studio'],
        ['Penggunaan Fotografer',  $persen_fotografer . '% Sesi',   'stat-value',    'icon-amber',   'fa-camera',          'stat-photographer'],
    ];
?>


<div id="admin-dashboard-view" class="admin-dashboard">
    <!-- Session Active Header Badge -->
    <div class="session-banner">
        <div class="session-info-group">
            <div class="session-shield-icon">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h4 class="session-title">Statistik & Manajemen Booking</h4>
                <p class="session-subtext">Login sebagai: <span class="text-rose-light">Administrator</span></p>
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
        <?php foreach ($stats as [$label, $value, $value_class, $icon_class, $icon, $id]): ?>
            <div class="stat-card">
                <div>
                    <p class="stat-label"><?= $label ?></p>
                    <h4 id="<?= $id ?>" class="<?= $value_class ?>"><?= e($value) ?></h4>
                </div>
                <div class="stat-icon <?= $icon_class ?>">
                    <i class="fa-solid <?= $icon ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>
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
                            <td colspan="7" class="empty-state">Belum ada data pemesanan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $row): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="font-mono font-semibold text-rose-600"><?= e($row['kode_booking']) ?></td>

                                <td>
                                    <div class="font-semibold text-slate-800"><?= e($row['nama_pemesan']) ?></div>
                                    <div class="text-xs text-slate-400"><?= e($row['no_hp']) ?></div>
                                </td>

                                <td class="font-medium text-slate-700"><?= e($row['nama_studio']) ?></td>

                                <td>
                                    <div class="font-medium text-slate-800"><?= date('d M Y', strtotime($row['tanggal'])) ?></div>
                                    <div class="text-xs text-slate-500"><?= e($row['slot_waktu']) ?></div>
                                </td>

                                <td class="text-xs text-slate-600"><?= e($row['detail_addons']) ?></td>

                                <td class="font-bold text-slate-800"><?= rupiah($row['total_harga']) ?></td>

                                <td class="text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="form_edit.php?kode=<?= urlencode($row['kode_booking']) ?>"
                                           class="text-amber-600 hover:text-amber-800 font-semibold text-xs inline-flex items-center gap-1">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        <span class="text-slate-300">|</span>

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
