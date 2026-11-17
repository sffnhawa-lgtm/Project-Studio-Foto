# Lumina Studio

Aplikasi reservasi studio foto berbasis **PHP** dan **MySQL**, dengan halaman client untuk memesan studio dan panel admin untuk mengelola data pemesanan.

> Project ini dibuat untuk belajar / tugas. Beberapa bagian keamanan sengaja disederhanakan.

## Fitur

- Katalog studio dengan pencarian dan filter tema
- Formulir booking dengan pengecekan slot jam yang sudah terisi
- Add-on layanan dengan perhitungan total harga otomatis
- Struk reservasi yang bisa dicetak
- Fitur "ingat pengisian" (nama dan nomor HP) memakai cookie
- Panel admin: statistik, daftar pemesanan, edit, hapus, dan reset data
- Login admin dengan opsi "Ingat Saya"

## Teknologi

- PHP (mysqli) dan MySQL
- Tailwind CSS (via CDN) dan Font Awesome
- JavaScript biasa (tanpa framework)

## Cara Menjalankan

1. Letakkan folder project di `htdocs` (XAMPP) atau `www` (Laragon).
2. Buat database bernama `db_studio_foto`, lalu import file SQL-nya.
   Tabel yang dipakai: `studio`, `time_slots`, `addons`, `pemesanan`, `pemesanan_addon`, `users`.
3. Sesuaikan `connection.php` bila perlu (host, username, password).
4. Buka `http://localhost/nama-folder/` di browser.
5. Login admin (akun demo): username `admin`, password `admin1234`.

> Membutuhkan koneksi internet karena Tailwind, Font Awesome, dan Google Fonts dimuat dari CDN.

## Struktur Folder

```
project/
├── index.php              Halaman utama (header, menu, memuat halaman lain)
├── dashboard.php          Halaman client: katalog + form booking
├── admin.php              Panel admin: statistik + tabel pemesanan
├── login.php              Form login admin
├── struk.php              Struk reservasi (modal)
├── form_edit.php          Form edit pemesanan (admin)
├── connection.php         Koneksi database
│
├── components/            Bagian tampilan yang dipakai ulang
│   ├── booking_fields.php   Isi form booking (dipakai dashboard & form_edit)
│   ├── studio_card.php      Kartu studio di katalog
│   └── toast.php            Notifikasi toast
│
├── includes/              File pendukung PHP
│   ├── head.php             <head> bersama (meta, Tailwind, CSS)
│   ├── helpers.php          Fungsi bantu: e(), rupiah(), redirect(), dll.
│   └── booking_data.php     Query data add-on, slot jam, dan booking
│
├── services/              Proses form (tanpa tampilan)
│   ├── process_booking.php
│   ├── process_login.php
│   ├── process_logout.php
│   ├── update_booking.php
│   ├── delete_data.php
│   └── delete_all_data.php
│
├── js/
│   ├── booking.js           Logika form: hitung total, pilih dan cek slot
│   └── dashboard.js         Pencarian, filter tema, toast, validasi submit
│
└── style/
    ├── base.css             Variabel warna, utilitas, print
    ├── layout.css           Wrapper, grid, section, responsive
    └── components.css       Card, tombol, form, badge, tabel, struk
```

## Alur Singkat

1. Client memilih studio, tanggal, slot jam, dan add-on di `dashboard.php`.
2. Form dikirim ke `services/process_booking.php`, lalu data disimpan.
3. Client diarahkan kembali ke `index.php?page=dashboard&kode=...` dan struk ditampilkan.
4. Admin login lewat `index.php?page=admin` untuk melihat statistik, mengubah, atau menghapus pemesanan.

## Fungsi Bantu (`includes/helpers.php`)

| Fungsi | Kegunaan |
|---|---|
| `e($text)` | Escape output HTML (pengganti `htmlspecialchars`) |
| `rupiah($angka)` | `150000` menjadi `Rp 150.000` |
| `redirect($url)` | Pindah halaman lalu `exit` |
| `require_admin()` | Tolak akses jika belum login admin (untuk `services/`) |
| `save_addons()` / `delete_addons()` | Simpan / hapus add-on milik satu pemesanan |

## Catatan

Project ini belum siap untuk produksi. Beberapa hal yang sengaja dibiarkan sederhana:

- Cookie `remember_admin` tidak diverifikasi di server.
- `form_edit.php` belum mengecek login admin.
- Penghapusan data memakai link `GET`.
- Login masih menerima password plaintext, dan akun demo terisi otomatis di form.