<?php
    include_once("connection.php");
    include_once("includes/helpers.php");
    include("includes/booking_data.php");

    $kategori = $_GET["kategori_filter"] ?? '';

    if ($kategori !== ''){
        $searchParam = "%" . $kategori . "%";
        $stmt = $connect->prepare("SELECT * FROM studio WHERE nama_studio LIKE ? OR tema LIKE ?");
        $stmt->bind_param("ss", $searchParam, $searchParam);
        $stmt->execute();
        $studios = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    } else {
        $studios = $connect->query("SELECT * FROM studio")->fetch_all(MYSQLI_ASSOC);
    }

    $themes = ['Semua Tema', 'Korean Neutral', 'Retro & Dark', 'Self Photo', 'Futuristic Glow'];

    $is_edit = false;
    $form = [
        'studio_id'     => '',
        'nama_pemesan'  => $_COOKIE["nama_pemesan"] ?? '',
        'no_hp'         => $_COOKIE["no_hp"] ?? '',
        'tanggal'       => '',
        'time_slots_id' => '',
    ];
    $selected_addon_ids = [];
?>


<div class="main-dashboard">

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
            <?php foreach ($themes as $tema): 
                $is_active = ($tema === 'Semua Tema') ? ($kategori === '') : ($kategori === $tema); ?>
                <button onclick="filter_by_theme(this)" class="theme-filter-btn <?= $is_active ? 'active' : '' ?>"><?= e($tema) ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Catalog Studio -->
    <div>
        <div class="studio-section-header">
            <h3><i class="fa-solid fa-sliders"></i> Pilihan Ruangan Studio</h3>
        </div>
        <div id="studio-grid" class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">">
            <?php foreach ($studios as $s): 
                include 'components/studio_card.php'; 
                endforeach; 
            ?>
        </div>
    </div>

    <!-- Form Reservasi -->
    <div id="booking-section" class="booking-section">
        <div class="booking-container">
            <div class="booking-header">
                <span class="booking-badge">Sistem Booking Terintegrasi</span>
                <h3 class="booking-title">Formulir Reservasi Studio Foto</h3>
                <p class="booking-subtitle">Sistem secara cerdas memeriksa ketersediaan jam slot untuk mencegah
                    bentrok jadwal.</p>
            </div>

            <form id="booking-form" method="POST" action="services/process_booking.php" class="booking-form">
                <?php include 'components/booking_fields.php'; ?>

                <button type="submit" id="submit-btn" class="submit-btn"><i class="fa-solid fa-receipt"></i>
                    Konfirmasi & Terbitkan Struk Reservasi</button>
            </form>
        </div>
    </div>

    <?php include 'components/toast.php'; ?>
</div>

<script>
    const bookingConfig = {
        pemesanan_data: <?= json_encode($booking_data) ?>,
        currentBookingId: 0,
        showBreakdown: false
    };
</script>
<script src="js/booking.js"></script>
<script src="js/dashboard.js"></script>
