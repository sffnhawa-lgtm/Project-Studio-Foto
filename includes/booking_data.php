<?php
    $addons         = $connect->query("SELECT * FROM addons")->fetch_all(MYSQLI_ASSOC);
    $all_time_slots = $connect->query("SELECT * FROM time_slots")->fetch_all(MYSQLI_ASSOC);

    $booking_data   = $connect->query("SELECT id, studio_id, tanggal, time_slots_id FROM pemesanan")->fetch_all(MYSQLI_ASSOC);
?>
