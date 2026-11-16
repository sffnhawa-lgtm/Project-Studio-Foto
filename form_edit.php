<?php
    include("connection.php");

    $daftar_studio = $connect->query("SELECT * FROM studio")->fetch_all(MYSQLI_ASSOC); // Pastikan query ini ada
    $addons = $connect->query("SELECT * FROM addons")->fetch_all(MYSQLI_ASSOC);
    $all_time_slots = $connect->query("SELECT * FROM time_slots")->fetch_all(MYSQLI_ASSOC);
    $booking_data = $connect->query("SELECT * FROM pemesanan")->fetch_all(MYSQLI_ASSOC);

    $kode = $_GET['kode'] ?? '';
    $stmt = $connect->prepare("
        SELECT p.*, s.nama_studio, s.harga_per_jam, t.slot_waktu 
        FROM pemesanan p
        JOIN studio s ON p.studio_id = s.id
        JOIN time_slots t ON p.time_slots_id = t.id
        WHERE p.kode_booking = ?
    ");
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $data_pesanan = $stmt->get_result()->fetch_assoc();

    if (!$data_pesanan) {
        die("Data pesanan tidak ditemukan.");
    }

    // Ambil hanya ID addon yang sudah dipilih sebelumnya
    $stmt_addon = $connect->prepare("SELECT addons_id FROM pemesanan_addon WHERE pemesanan_id = ?");
    $stmt_addon->bind_param("i", $data_pesanan['id']);
    $stmt_addon->execute();
    $res_addon = $stmt_addon->get_result()->fetch_all(MYSQLI_ASSOC);
    $selected_addon_ids = array_column($res_addon, 'addons_id');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        mono: ['Fira Code', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        .terminal-bg { background-color: #0d1117; }
        .terminal-text { color: #38BDF8; }
        .glass-panel { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <link rel="stylesheet" href="style/base.css">
    <link rel="stylesheet" href="style/catalog.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/dashboard.css">
    <title>Document</title>
</head>

<body>
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
        <div id="booking-section" class="booking-section">
            <div class="booking-container">
                <div class="booking-header">
                    <!-- Ubah Badge & Judul ke Mode Edit -->
                    <span class="booking-badge bg-amber-100 text-amber-800 border border-amber-300">
                        <i class="fa-solid fa-pen-to-square"></i> Mode Edit Reservasi (<?= htmlspecialchars($data_pesanan['kode_booking']) ?>)
                    </span>
                    <h3 class="booking-title">Edit Data Reservasi Studio</h3>
                    <p class="booking-subtitle">Perbarui jadwal, ruangan, atau layanan tambahan untuk pesanan ini.</p>
                </div>

                <!-- Arahkan action ke file update -->
                <form id="booking-form" method="POST" action="services/update_booking.php" class="booking-form">
                    <!-- Hidden ID & Kode Booking untuk proses UPDATE -->
                    <input type="hidden" name="id" value="<?= (int)$data_pesanan['id'] ?>">
                    <input type="hidden" name="kode_booking" value="<?= htmlspecialchars($data_pesanan['kode_booking']) ?>">

                    <!-- 1. Studio -->
                    <div class="form-group">
                        <label class="form-label" for="form-studio">1. Pilih Ruangan Studio</label>
                        <select onchange="calculate_total(); updateSlotStatus();" id="form-studio" name="studio_id" required class="form-control form-select">
                            <option value="" disabled>-- Pilih Studio Foto --</option>
                            <?php foreach ($daftar_studio as $s): ?>
                                <option value="<?= (int)$s['id']; ?>" 
                                        data-harga="<?= (int)$s['harga_per_jam']; ?>"
                                        <?= ($s['id'] == $data_pesanan['studio_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($s['nama_studio']); ?> (Rp <?= number_format($s['harga_per_jam'], 0, ',', '.'); ?>/jam)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Identitas -->
                    <div class="identity-group">
                        <div class="grid-2-cols">
                            <div>
                                <label class="form-label" for="form-nama">2. Nama Pemesan / Client</label>
                                <input type="text" id="form-nama" name="nama_pemesan" required maxlength="100"
                                    value="<?= htmlspecialchars($data_pesanan['nama_pemesan']) ?>" class="form-control">
                            </div>
                            <div>
                                <label class="form-label" for="form-hp">Nomor WhatsApp / HP</label>
                                <input type="tel" id="form-hp" name="no_hp" required maxlength="20"
                                    value="<?= htmlspecialchars($data_pesanan['no_hp']) ?>" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Tanggal & slot -->
                    <div class="slot-box">
                        <div class="slot-header">
                            <label class="form-label no-margin">3. Tanggal & Slot Jam Sesi</label>
                            <div class="slot-legend">
                                <span class="legend-item"><span class="dot dot-available"></span> Tersedia</span>
                                <span class="legend-item"><span class="dot dot-booked"></span> Terisi</span>
                                <span class="legend-item"><span class="dot dot-selected"></span> Dipilih</span>
                            </div>
                        </div>
                        <div class="slot-grid-layout">
                            <div>
                                <!-- Pre-fill tanggal lama & hapus batasan min agar data lama tetap valid saat diedit -->
                                <input type="date" id="form-tanggal" onchange="updateSlotStatus()" name="tanggal" 
                                    value="<?= htmlspecialchars($data_pesanan['tanggal']) ?>" required class="form-control date-input">
                            </div>
                            <div class="slot-matrix-wrapper">
                                <div id="slot-matrix-container" class="slot-matrix">
                                    <?php foreach ($all_time_slots as $ts): ?>
                                        <button type="button" 
                                                data-id="<?= (int)$ts['id'] ?>"
                                                data-slot="<?= htmlspecialchars($ts['slot_waktu']) ?>"
                                                onclick="selectSlot(this)"
                                                class="slot-btn py-2 px-2.5 rounded-xl text-xs font-semibold bg-white hover:bg-emerald-50 border border-emerald-300 text-slate-700 hover:text-emerald-700 transition-all flex items-center justify-center gap-1">
                                            <?= htmlspecialchars($ts['slot_waktu']) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <!-- Pre-fill ID slot jam lama -->
                                <input type="hidden" id="form-jam-selected" name="time_slots_id" value="<?= (int)$data_pesanan['time_slots_id'] ?>">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Add-ons -->
                    <div>
                        <label class="form-label mb-2">4. Tambahan Layanan Opsional (Add-Ons)</label>
                        <div id="addons-grid" class="addons-grid">
                            <?php foreach ($addons as $addon): ?>
                                <?php $isChecked = in_array($addon['id'], $selected_addon_ids) ? 'checked' : ''; ?>
                                <label class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/80 transition-all">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" 
                                            name="addons[]" 
                                            value="<?= $addon['id'] ?>" 
                                            data-harga="<?= $addon['harga'] ?>" 
                                            onchange="calculate_total()" 
                                            <?= $isChecked ?>
                                            class="addon-checkbox w-4 h-4 text-rose-600 rounded focus:ring-rose-500">
                                        <span class="text-xs font-semibold text-slate-700"><?= htmlspecialchars($addon['nama_addon']) ?></span>
                                    </div>
                                    <span class="text-xs font-bold text-rose-600">+Rp <?= number_format($addon['harga'], 0, ',', '.') ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Ringkasan harga -->
                    <div class="summary-box">
                        <div class="summary-content">
                            <div>
                                <p class="summary-label">Total Pembayaran Terbaru</p>
                                <h4 id="total-price-display" class="summary-total">Rp 0</h4>
                            </div>
                            <div id="price-breakdown-list" class="summary-list">
                                <span>Menghitung ulang rincian...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Simpan & Batal -->
                    <div class="flex items-center gap-3 mt-6">
                        <!-- Tombol Batal (Kembali tanpa menyimpan) -->
                        <a href="index.php?page=admin" class="px-6 py-3.5 rounded-xl font-semibold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-all text-center">
                            Batal
                        </a>

                        <!-- Tombol Konfirmasi Simpan (Submit form) -->
                        <button type="submit" id="submit-btn" class="submit-btn flex-1 py-3.5 px-6 rounded-xl font-bold text-sm text-white bg-rose-600 hover:bg-rose-700 shadow-md transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-circle"></i> Konfirmasi Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </body>

    <script>
        const currentBookingId = <?= (int)$data_pesanan['id']; ?>;
        const pemesanan_data = <?= json_encode($booking_data); ?>;

        function calculate_total() {
            const selectStudio = document.getElementById('form-studio');
            const selectedOption = selectStudio.options[selectStudio.selectedIndex];
            const hargaStudio = parseInt(selectedOption.dataset.harga) || 0;

            let totalAddon = 0;
            let breakdownHTML = `<div>• ${selectedOption.text.split('(')[0].trim()}: <b>Rp ${hargaStudio.toLocaleString('id-ID')}</b></div>`;

            document.querySelectorAll('.addon-checkbox:checked').forEach(item => {
                const harga = parseInt(item.dataset.harga);
                const nama = item.nextElementSibling.innerText;
                totalAddon += harga;
                breakdownHTML += `<div>• ${nama}: <b>Rp ${harga.toLocaleString('id-ID')}</b></div>`;
            });

            const grandTotal = hargaStudio + totalAddon;
            document.getElementById('total-price-display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
            document.getElementById('price-breakdown-list').innerHTML = breakdownHTML;
        }

        function selectSlot(btn) {
            if (btn.disabled) return;
            document.getElementById('form-jam-selected').value = btn.dataset.id;
            updateSlotStatus();
        }

        function updateSlotStatus() {
            const studioId = parseInt(document.getElementById('form-studio').value);
            const tanggal = document.getElementById('form-tanggal').value;
            const selectedId = document.getElementById('form-jam-selected').value;

            if (!studioId || !tanggal) return;

            // PENTING: Abaikan pesanan saat ini (p.id != currentBookingId) agar jam lamanya tidak dianggap terkunci/terisi
            const bookedSlotIds = pemesanan_data
                .filter(p => parseInt(p.studio_id) === studioId && p.tanggal === tanggal && parseInt(p.id) !== currentBookingId)
                .map(p => String(p.time_slots_id));

            document.querySelectorAll('.slot-btn').forEach(btn => {
                const slotId = btn.dataset.id;
                const slotText = btn.dataset.slot;
                const isBooked = bookedSlotIds.includes(slotId);
                const isSelected = selectedId === slotId;

                btn.disabled = isBooked;

                if (isBooked) {
                    btn.className = "slot-btn py-2 px-2.5 rounded-xl text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-400 cursor-not-allowed flex items-center justify-center gap-1 opacity-70";
                    btn.innerHTML = `<i class="fa-solid fa-lock text-[10px]"></i> ${slotText}`;
                } else if (isSelected) {
                    btn.className = "slot-btn py-2 px-2.5 rounded-xl text-xs font-bold bg-rose-600 text-white shadow-md border border-rose-600 flex items-center justify-center gap-1 ring-2 ring-rose-400";
                    btn.innerHTML = `<i class="fa-solid fa-check text-[10px]"></i> ${slotText}`;
                } else {
                    btn.className = "slot-btn py-2 px-2.5 rounded-xl text-xs font-semibold bg-white hover:bg-emerald-50 border border-emerald-300 text-slate-700 hover:text-emerald-700 transition-all flex items-center justify-center gap-1";
                    btn.innerHTML = slotText;
                }
            });
        }

        // Jalankan otomatis saat halaman pertama kali dibuka agar data lama langsung tampil & terhitung
        document.addEventListener("DOMContentLoaded", () => {
            updateSlotStatus();
            calculate_total();
        });
    </script>
</div>