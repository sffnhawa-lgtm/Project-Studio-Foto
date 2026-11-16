<?php
    include("connection.php");

    $addons = $connect->query("SELECT * FROM addons")->fetch_all(MYSQLI_ASSOC);
    $all_time_slots = $connect->query("SELECT * FROM time_slots")->fetch_all(MYSQLI_ASSOC);
    $booking_data = $connect->query("SELECT * FROM pemesanan")->fetch_all(MYSQLI_ASSOC);

    $nama_pemesan = $_COOKIE["nama_pemesan"] ?? '';
    $no_hp = $_COOKIE["no_hp"] ?? '';

    // Catalog Region =======================================
    $kategori = $_GET["kategori_filter"] ?? '';

    if ($kategori !== '') {
        $searchParam = "%" . $kategori . "%";
        $stmt = $connect->prepare("SELECT * FROM studio WHERE nama_studio LIKE ? OR tema LIKE ?");
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();
        $daftarStudio = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $daftarStudio = $connect->query("SELECT * FROM studio")->fetch_all(MYSQLI_ASSOC);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ["Plus Jakarta Sans", "sans-serif"], mono: ["Fira Code", "monospace"] } } } }
    </script>

    <link rel="stylesheet" href="style/base.css">
    <link rel="stylesheet" href="style/catalog.css">
    <link rel="stylesheet" href="style/form.css">
    <link rel="stylesheet" href="style/dashboard.css">

    <title>Lumina Studio - Reservasi Studio Foto</title>
</head>

<body data-page="dashboard">
    <div id="app-header"></div>

    <main class="main-dashboard">

        <!-- Banner -->
        <div class="hero-banner">
            <div class="hero-content">
                <span class="hero-badge">✨ Premium Aesthetic Photography</span>
                <h2 class="hero-title">Abadikan Momen Berharga Dengan Hasil Spektakuler</h2>
                <p class="hero-desc">Pilih tema studio pilihanmu, cek ketersediaan slot waktu secara real-time, dan
                    pesan dalam hitungan detik.</p>
                <div class="hero-actions">
                    <a href="#booking-section" class="btn-hero-primary">
                        <i class="fa-solid fa-calendar-check"></i> Booking Sekarang
                    </a>
                    <button type="button" id="btn-scroll-catalog" class="btn-hero-secondary" onclick="scroll_to_catalog()">
                        Lihat Katalog Studio
                    </button>
                </div>
            </div>
            <i class="fa-solid fa-camera-retro hero-bg-icon"></i>
        </div>

        <!-- Search & Filter -->
        <div id="katalog-section" class="catalog-filter-bar">
            <div class="search-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="search-input" placeholder="Cari nama studio atau tema..." class="search-input">
            </div>
            <div class="theme-filter-list hide-scrollbar">
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= ($kategori === '') ? 'active' : '' ?>">Semua Tema</button>
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= ($kategori === 'Korean Neutral') ? 'active' : '' ?>">Korean Neutral</button>
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= ($kategori === 'Retro & Dark') ? 'active' : '' ?>">Retro & Dark</button>
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= ($kategori === 'Self Photo') ? 'active' : '' ?>">Self Photo</button>
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= ($kategori === 'Futuristic Glow') ? 'active' : '' ?>">Futuristic Glow</button>
            </div>
        </div>

        <!-- Catalog Studio -->
        <div>
            <div class="studio-section-header">
                <h3><i class="fa-solid fa-sliders text-rose-dark"></i> Pilihan Ruangan Studio</h3>
            </div>
            <div id="studio-grid" class="studio-grid">
                <?php foreach ($daftarStudio as $s): ?>
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden hover:shadow-lg transition-all flex flex-col justify-between group">
                        <div>
                            <div class="relative h-44 overflow-hidden bg-slate-900">
                                <img src="<?= htmlspecialchars($s['gambar']); ?>" alt="<?= htmlspecialchars($s['nama_studio']); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-90">
                                <span class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md text-rose-300 text-[11px] font-bold px-2.5 py-1 rounded-full border border-rose-500/30">
                                    <?= htmlspecialchars($s['tema']); ?>
                                </span>
                            </div>
                            <div class="p-5">
                                <h4 class="font-extrabold text-base text-slate-800"><?= htmlspecialchars($s['nama_studio']); ?></h4>
                                <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-users text-rose-500 mr-1"></i> Kapasitas Maks: <?= (int)$s['kapasitas_maks']; ?> orang</p>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-baseline justify-between">
                                    <span class="text-xs text-slate-400 font-medium">Tarif Sewa</span>
                                    <span class="text-base font-extrabold text-rose-600">Rp <?= number_format($s['harga_per_jam'], 0, ',', '.'); ?> <span class="text-xs font-normal text-slate-400">/jam</span></span>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 pt-0">
                            <button onclick="select_studio(<?= $s['id']; ?>)" class="w-full bg-slate-900 hover:bg-rose-600 text-white font-bold py-2.5 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-sm">
                                <i class="fa-solid fa-calendar-plus"></i> Pilih Studio Ini
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Form Reservasi  -->
        <div id="booking-section" class="booking-section">
            <div class="booking-container">
                <div class="booking-header">
                    <span class="booking-badge">Sistem Booking Terintegrasi</span>
                    <h3 class="booking-title">Formulir Reservasi Studio Foto</h3>
                    <p class="booking-subtitle">Sistem secara cerdas memeriksa ketersediaan jam slot untuk mencegah
                        bentrok jadwal.</p>
                </div>

                <form id="booking-form" method="POST" action="services/process_booking.php" class="booking-form">
                    <!-- 1. Studio -->
                    <div class="form-group">
                        <label class="form-label" for="form-studio">1. Pilih Ruangan Studio</label>
                        <select onchange="calculate_total(); updateSlotStatus();" id="form-studio" name="studio_id" required class="form-control form-select">
                            <option value="" disabled selected>-- Pilih Studio Foto --</option>
                            <?php foreach ($daftarStudio as $s): ?>
                                <option value="<?= (int)$s['id']; ?>" data-harga="<?= (int)$s['harga_per_jam']; ?>">
                                    <?= htmlspecialchars($s['nama_studio']); ?> (Rp <?= number_format($s['harga_per_jam'], 0, ',', '.'); ?>/jam)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Identitas + cookie -->
                    <div class="identity-group">
                        <div class="grid-2-cols">
                            <div>
                                <label class="form-label" for="form-nama">2. Nama Pemesan / Client</label>
                                <input type="text" id="form-nama" name="nama_pemesan" required maxlength="100"
                                    placeholder="Contoh: Saffanah Syalwa" value="<?= htmlspecialchars($nama_pemesan) ?>"
                                    class="form-control">
                            </div>
                            <div>
                                <label class="form-label" for="form-hp">Nomor WhatsApp / HP</label>
                                <input type="tel" id="form-hp" name="no_hp" required maxlength="20"
                                    placeholder="Contoh: 081234567890" value="<?= htmlspecialchars($no_hp) ?>"
                                    class="form-control">
                            </div>
                        </div>
                        <label class="cookie-label">
                            <input type="checkbox" id="save-cookie-checkbox" name="remember" checked class="checkbox-rose">
                            <span class="cookie-text"><i class="fa-solid cookie-icon"></i> Ingat pengisian ini</span>
                        </label>
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
                                <input type="date" id="form-tanggal" onchange="updateSlotStatus()" name="tanggal" min="<?= date('Y-m-d'); ?>" required class="form-control date-input">
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
                                <input type="hidden" id="form-jam-selected" name="time_slots_id">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Add-ons -->
                    <div>
                        <label class="form-label mb-2">4. Tambahan Layanan Opsional (Add-Ons)</label>
                        <div id="addons-grid" class="addons-grid">
                            <?php foreach ($addons as $addon): ?>
                                <label class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100/80 transition-all">
                                    <div class="flex items-center gap-2.5">
                                        <input type="checkbox" 
                                            name="addons[]" 
                                            value="<?= $addon['id'] ?>" 
                                            data-harga="<?= $addon['harga'] ?>" 
                                            onchange="calculate_total()" 
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
                                <p class="summary-label">Rincian Estimasi Pembayaran</p>
                                <h4 id="total-price-display" class="summary-total">Rp 0</h4>
                            </div>
                            <div id="price-breakdown-list" class="summary-list"><span>Silakan pilih studio & slot
                                    jam</span></div>
                        </div>
                    </div>

                    <button type="submit" id="submit-btn" class="submit-btn"><i class="fa-solid fa-receipt"></i>
                        Konfirmasi & Terbitkan Struk Reservasi</button>
                </form>
            </div>
        </div>

        <!-- Global Toast Banner -->
        <div id="toast-container" class="fixed bottom-5 right-5 z-50 max-w-md w-full px-4 hidden transition-all duration-300 transform translate-y-2">
            <div id="toast-box" class="p-4 rounded-2xl border flex items-center justify-between shadow-2xl bg-white/90 backdrop-blur-md">
                <div class="flex items-start space-x-3">
                    <div id="toast-icon-bg" class="p-2 rounded-xl text-white">
                        <i id="toast-icon" class="text-lg"></i>
                    </div>
                    <div>
                        <h5 id="toast-title" class="font-bold text-sm text-slate-800"></h5>
                        <p id="toast-message" class="text-xs text-slate-600 leading-relaxed mt-0.5"></p>
                    </div>
                </div>
                <button type="button" onclick="hide_toast()" class="text-slate-400 hover:text-slate-600 p-1 ml-3">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </main>

    <script>
        function scroll_to_catalog(){
            document.getElementById('katalog-section').scrollIntoView({ behavior: 'smooth' });
        }

        // Catalog Region ======================================
        document.getElementById('search-input').addEventListener('input', function() {
            const keyword = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('#studio-grid > div');

            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                card.style.display = text.includes(keyword) ? 'flex' : 'none';
            });
        });

        function filter_by_theme(btn){
            const theme = btn.innerText.trim();

            if (theme === 'Semua Tema') {
                window.location.href = 'dashboard.php';
            } else {
                window.location.href = `?kategori_filter=${encodeURIComponent(theme)}`;
            }
        }

        function select_studio(studio_id){
            const selectStudio = document.getElementById('form-studio');
            selectStudio.value = studio_id;
            selectStudio.dispatchEvent(new Event('change'));

            document.getElementById('booking-section').scrollIntoView({ 
                behavior: 'smooth' 
            });
        }

        // Form Region ======================================
        function calculate_total(){
            const selectStudio = document.getElementById('form-studio');
            const selectedOption = selectStudio.options[selectStudio.selectedIndex];
            const hargaStudio = parseInt(selectedOption.dataset.harga) || 0;

            let totalAddon = 0;
            document.querySelectorAll('.addon-checkbox:checked').forEach(item => {
                totalAddon += parseInt(item.dataset.harga);
            });

            const grandTotal = hargaStudio + totalAddon;
            document.getElementById('total-price-display').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
        }

        function selectSlot(btn) {
            if (btn.disabled) return;
            document.getElementById('form-jam-selected').value = btn.dataset.id;
            updateSlotStatus();
        }

        function updateSlotStatus() {
            const pemesanan_data = <?= json_encode($booking_data); ?>;
            const studioId = parseInt(document.getElementById('form-studio').value);
            const tanggal = document.getElementById('form-tanggal').value;
            const selectedId = document.getElementById('form-jam-selected').value;

            if (!studioId || !tanggal) return;

            const bookedSlotIds = pemesanan_data
                .filter(p => parseInt(p.studio_id) === studioId && p.tanggal === tanggal)
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

        // Toast Handler
        function showToast(type, title, msg) {
            const container = document.getElementById('toast-container');
            const iconBg = document.getElementById('toast-icon-bg');
            const icon = document.getElementById('toast-icon');

            if (type === 'error') {
                iconBg.className = "p-2 rounded-xl bg-rose-600 text-white";
                icon.className = "fa-solid fa-triangle-exclamation";
            } else {
                iconBg.className = "p-2 rounded-xl bg-emerald-600 text-white";
                icon.className = "fa-solid fa-circle-check";
            }

            document.getElementById('toast-title').textContent = title;
            document.getElementById('toast-message').textContent = msg;

            container.classList.remove('hidden');
            setTimeout(() => hide_toast(), 5000);
        }

        function hide_toast() {
            document.getElementById('toast-container').classList.add('hidden');
        }

        document.getElementById('booking-form').addEventListener('submit', function(e) {
            const selectedSlot = document.getElementById('form-jam-selected').value;
            if (!selectedSlot) {
                e.preventDefault();
                showToast('error', 'Pilih Slot Jam!', 'Harap klik salah satu slot waktu hijau yang tersedia.');
            }
        });
        

    </script>
</body>

</html>