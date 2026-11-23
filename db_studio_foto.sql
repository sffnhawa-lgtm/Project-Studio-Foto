-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Nov 2026 pada 07.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_studio_foto`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `aksi` enum('edit','hapus','hapus_semua') NOT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `activity_log`
--

INSERT INTO `activity_log` (`id`, `username`, `aksi`, `detail`, `created_at`) VALUES
(1, 'kocak', 'edit', 'Edit booking LUM-20261122-474', '2026-11-22 09:06:30'),
(2, 'kocak', 'hapus', 'Hapus booking LUM-20261122-474', '2026-11-22 09:06:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `addons`
--

CREATE TABLE `addons` (
  `id` int(11) NOT NULL,
  `nama_addon` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `addons`
--

INSERT INTO `addons` (`id`, `nama_addon`, `harga`) VALUES
(1, 'Jasa Fotografer Studio', 50000),
(2, 'All Master RAW Files (Flashdisk)', 30000),
(3, 'Cetak 4R + Frame Aesthetic (2 Pcs)', 35000),
(4, 'Touch-up Makeup Artist (MUA)', 120000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `kode_booking` varchar(50) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `studio_id` int(11) NOT NULL,
  `time_slots_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `kode_booking`, `nama_pemesan`, `no_hp`, `tanggal`, `created_at`, `studio_id`, `time_slots_id`) VALUES
(4, 'LUM-20261122-896', 'azkaz', '0813200', '2026-11-28', '2026-11-22 05:51:39', 1, 1),
(5, 'LUM-20261122-490', 'olama', '1212121212', '2026-11-25', '2026-11-22 05:51:56', 4, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan_addon`
--

CREATE TABLE `pemesanan_addon` (
  `id` int(11) NOT NULL,
  `pemesanan_id` int(11) NOT NULL,
  `addons_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pemesanan_addon`
--

INSERT INTO `pemesanan_addon` (`id`, `pemesanan_id`, `addons_id`) VALUES
(12, 5, 1),
(13, 5, 2),
(14, 5, 3),
(15, 5, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `studio`
--

CREATE TABLE `studio` (
  `id` int(11) NOT NULL,
  `nama_studio` varchar(100) NOT NULL,
  `tema` varchar(50) NOT NULL,
  `harga_per_jam` int(11) NOT NULL,
  `kapasitas_maks` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `studio`
--

INSERT INTO `studio` (`id`, `nama_studio`, `tema`, `harga_per_jam`, `kapasitas_maks`, `gambar`) VALUES
(1, 'Studio A - Warm Aesthetic', 'Korean Neutral', 150000, 5, 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80'),
(2, 'Studio B - Vintage Classic', 'Retro & Dark', 175000, 6, 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=500'),
(3, 'Studio C - Self Photo Box', 'Self Photo', 100000, 4, 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?w=500'),
(4, 'Studio D - Cyberpunk Neon', 'Futuristic Glow', 200000, 8, 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=500'),
(5, 'Studio E - Soft Cream Minimal', 'Korean Neutral', 160000, 4, 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80'),
(6, 'Studio F - Noir Film Room', 'Retro & Dark', 185000, 5, 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80'),
(7, 'Studio G - Mirror Selfie Booth', 'Self Photo', 90000, 2, 'https://images.unsplash.com/photo-1618220179428-22790b461013?auto=format&fit=crop&w=800&q=80'),
(8, 'Studio H - Holographic Glow', 'Futuristic Glow', 220000, 10, 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?auto=format&fit=crop&w=800&q=80');

-- --------------------------------------------------------

--
-- Struktur dari tabel `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `slot_waktu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `time_slots`
--

INSERT INTO `time_slots` (`id`, `slot_waktu`) VALUES
(1, '10:00 - 11:00'),
(2, '11:00 - 12:00'),
(3, '13:00 - 14:00'),
(4, '14:00 - 15:00'),
(5, '15:00 - 16:00'),
(6, '16:00 - 17:00'),
(7, '18:30 - 19:30'),
(8, '19:30 - 20:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff') DEFAULT 'Admin',
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`) VALUES
(1, 'admin', 'admin1234', 'Admin', 'd309c7016b1e62df37e37cc7d58bbd641f5898c6b45e2f33d11e90ae55686b2d'),
(2, 'kocak', '$2y$10$VpCP0Iyh4ehsYV0fqfnrd.q1gWSytcP1UrurDDpa1JKpDzdwg5nxK', 'Admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `addons`
--
ALTER TABLE `addons`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_booking` (`kode_booking`),
  ADD KEY `studio_id` (`studio_id`),
  ADD KEY `time_slots_id` (`time_slots_id`);

--
-- Indeks untuk tabel `pemesanan_addon`
--
ALTER TABLE `pemesanan_addon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemesanan_id` (`pemesanan_id`),
  ADD KEY `addons_id` (`addons_id`);

--
-- Indeks untuk tabel `studio`
--
ALTER TABLE `studio`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `addons`
--
ALTER TABLE `addons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pemesanan_addon`
--
ALTER TABLE `pemesanan_addon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `studio`
--
ALTER TABLE `studio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`studio_id`) REFERENCES `studio` (`id`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`time_slots_id`) REFERENCES `time_slots` (`id`);

--
-- Ketidakleluasaan untuk tabel `pemesanan_addon`
--
ALTER TABLE `pemesanan_addon`
  ADD CONSTRAINT `pemesanan_addon_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pemesanan_addon_ibfk_2` FOREIGN KEY (`addons_id`) REFERENCES `addons` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
