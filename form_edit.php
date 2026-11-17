<?php
    include_once("connection.php");
    include_once("includes/helpers.php");
    include("includes/booking_data.php");

    $studios = $connect->query("SELECT * FROM studio")->fetch_all(MYSQLI_ASSOC);

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

    if (!$data_pesanan){
        die("Data pesanan tidak ditemukan.");
    }

    $stmt_addon = $connect->prepare("SELECT addons_id FROM pemesanan_addon WHERE pemesanan_id = ?");
    $stmt_addon->bind_param("i", $data_pesanan['id']);
    $stmt_addon->execute();
    $selected_addon_ids = array_column($stmt_addon->get_result()->fetch_all(MYSQLI_ASSOC), 'addons_id');

    $is_edit    = true;
    $form       = $data_pesanan;
    $page_title = 'Edit Reservasi - Lumina Studio';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/head.php'; ?>
</head>

<body>
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 py-8">
        <div id="booking-section" class="booking-section">
            <div class="booking-container">
                <div class="booking-header">
                    <span class="booking-badge bg-amber-100 text-amber-800 border border-amber-300">
                        <i class="fa-solid fa-pen-to-square"></i> Mode Edit Reservasi (<?= e($data_pesanan['kode_booking']) ?>)
                    </span>
                    <h3 class="booking-title">Edit Data Reservasi Studio</h3>
                    <p class="booking-subtitle">Perbarui jadwal, ruangan, atau layanan tambahan untuk pesanan ini.</p>
                </div>

                <form id="booking-form" method="POST" action="services/update_booking.php" class="booking-form">
                    <input type="hidden" name="id" value="<?= (int)$data_pesanan['id'] ?>">

                    <?php include 'components/booking_fields.php'; ?>

                    <div class="flex items-center gap-3 mt-6">
                        <a href="index.php?page=admin" class="px-6 py-3.5 rounded-xl font-semibold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-all text-center">
                            Batal
                        </a>

                        <button type="submit" id="submit-btn" class="submit-btn flex-1 py-3.5 px-6 rounded-xl font-bold text-sm text-white bg-rose-600 hover:bg-rose-700 shadow-md transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-circle"></i> Konfirmasi Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const bookingConfig = {
            pemesanan_data: <?= json_encode($booking_data) ?>,
            currentBookingId: <?= (int)$data_pesanan['id'] ?>,
            showBreakdown: true
        };
    </script>
    <script src="js/booking.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            updateSlotStatus();
            calculate_total();
        });
    </script>
</body>
</html>
