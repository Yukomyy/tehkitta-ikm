# Teh Kitta — Sistem Informasi Manajemen & Point of Sale (POS)

Sistem Informasi Manajemen Bisnis dan Point of Sale (POS) berbasis web yang dikembangkan untuk unit usaha IKM (Industri Kecil dan Menengah) **Teh Kitta** di bawah naungan **PT Rafika Mandiri**. Aplikasi ini mengintegrasikan portal profil usaha (*landing page* kemitraan) dengan sistem *back-office* untuk operasional harian, mencakup pengadaan bahan baku, transaksi penjualan kasir, kontrol inventaris, serta pelaporan keuangan.

---

## Daftar Isi

- [Ikhtisar Sistem](#ikhtisar-sistem)
- [Teknologi](#teknologi)
- [Fitur Utama](#fitur-utama)
- [Struktur Direktori](#struktur-direktori)
- [Panduan Instalasi](#panduan-instalasi)
- [Konfigurasi Basis Data](#konfigurasi-basis-data)
- [Akun Pengguna Default](#akun-pengguna-default)
- [Catatan Keamanan](#catatan-keamanan)
- [Lisensi](#lisensi)

---

## Ikhtisar Sistem

Aplikasi dirancang untuk menjawab kebutuhan operasional terintegrasi pada industri minuman skala IKM. Sistem memiliki dua fokus utama:

1. **Front-Facing Portal**: Memperkenalkan profil brand Teh Kitta, katalog produk, transparansi paket kemitraan usaha (franchise), simulasi laba usaha, dan jalur registrasi mitra.
2. **Back-Office Management & POS**: Pengelolaan data master, alur pengadaan dari pemasok, kasir point-of-sale dengan pencetakan nota transaksi fisik, serta rekapitulasi performa bisnis berbasis periode.

---

## Teknologi

- **Bahasa Pemrograman**: PHP 8.x (Native / Modular Architecture)
- **Basis Data**: MySQL / MariaDB (Relational DBMS)
- **Frontend**: HTML5, Vanilla JavaScript, CSS3 (Modern Flexbox & CSS Grid)
- **Konektivitas Database**: PHP Data Objects / MySQL Improved (`mysqli`)
- **Autentikasi**: Password Hashing Bcrypt (`PASSWORD_BCRYPT`), Session-Based Guard Middleware

---

## Fitur Utama

### 1. Landing Page & Profil Kemitraan
- Antarmuka responsif dan informatif untuk profil usaha Teh Kitta.
- Rincian paket kemitraan (franchise), estimasi *Return on Investment* (ROI), serta formulir pendaftaran mitra.
- Tampilan katalog produk minuman unggulan dan ulasan pelanggan.
- Integrasi navigasi langsung ke sistem back-office / portal login staf.

### 2. Autentikasi dan Kontrol Hak Akses (RBAC)
- Mekanisme enkripsi kata sandi berbasis algoritma Bcrypt standar industri.
- Middleware penjaga sesi (`auth/middleware.php`) untuk proteksi rute halaman internal.
- Pembagian peran pengguna:
  - **Admin**: Akses menyeluruh terhadap manajemen master data, konfigurasi pengguna, pengadaan, transaksi, dan laporan komprehensif.
  - **Staff**: Akses terfokus pada aktivitas operasional harian kasir dan pencatatan transaksi penjualan.

### 3. Manajemen Master Data
- **Bahan Baku**: Pencatatan inventaris bahan baku (teh, pemanis, kemasan, cup) beserta satuan dan harga dasar.
- **Produk**: Katalog varian menu minuman siap jual dengan harga jual per item.
- **Customer**: Pengelolaan data pelanggan dan mitra cabang.
- **Supplier**: Rekapitulasi vendor pemasok bahan baku.
- **Petugas**: Pengelolaan data karyawan dan operator kasir.

### 4. Transaksi Terpadu
- **Pengadaan / Pembelian Bahan Baku**: Pencatatan faktur pembelian dari supplier dengan dukungan multi-item barang dan kalkulasi subtotal otomatis.
- **Penjualan (Kasir POS)**: Antarmuka kasir cepat untuk pemilihan produk minuman, kalkulasi total belanja, penanganan uang bayar dan kembalian, serta fungsi cetak nota penjualan langsung (`cetak_penjualan.php`).

### 5. Pelaporan & Analisis
- Rekapitulasi transaksi penjualan dengan filter rentang tanggal dan fitur ekspor cetak (`cetak_laporan_jual.php`).
- Rekapitulasi transaksi pengadaan bahan baku dengan filter periode dan cetak laporan (`cetak_laporan_beli.php`).
- Dashboard analitik dengan indikator performa penjualan, total pendapatan, volume transaksi, dan ringkasan data inventaris.

---

## Struktur Direktori

```plaintext
tehkitta-ikm/
├── auth/                       # Modul autentikasi dan manajemen sesi
│   ├── auth_controller.php     # Logika pemrosesan login dan logout
│   ├── login.php               # Antarmuka login pengguna
│   ├── middleware.php          # Route guard dan validasi sesi aktif
│   ├── seeder.php              # Script inisialisasi akun dan tabel users
│   └── users_migration.sql     # Skema DDL tabel pengguna
├── barang/                     # CRUD data master bahan baku
├── customer/                   # CRUD data master pelanggan dan mitra
├── dashboard/                  # Dashboard analitik dan ringkasan metrik
├── laporan/                    # Modul rekapitulasi dan cetak laporan
│   ├── cetak_laporan_beli.php  # Format cetak laporan pembelian
│   ├── cetak_laporan_jual.php  # Format cetak laporan penjualan
│   ├── cetak_pembelian.php     # Format cetak nota pembelian
│   ├── cetak_penjualan.php     # Format cetak nota kasir penjualan
│   ├── pembelian.php           # Tinjauan dan filter laporan pembelian
│   └── penjualan.php           # Tinjauan dan filter laporan penjualan
├── layouts/                    # Komponen tata letak global
│   ├── header.php              # Navigasi sidebar, status bar, dan notifikasi
│   └── footer.php              # Penutup struktur halaman
├── pembelian/                  # Modul operasional pembelian bahan baku
├── penjualan/                  # Modul operasional penjualan kasir POS
├── petugas/                    # CRUD data karyawan dan operator
├── produk/                     # CRUD data master varian produk minuman
├── supplier/                   # CRUD data master pemasok bahan baku
├── index.php                   # Titik masuk utama aplikasi (routing ke landing)
├── landing.php                 # Halaman utama profil usaha dan kemitraan
├── koneksi.php                 # Konfigurasi koneksi basis data MySQL
├── logo.png                    # Berkas aset visual resmi
├── ptrafikamandiri.sql         # Skema basis data relasional dan data awal
├── script.js                   # Logika interaktivitas antarmuka (DOM)
└── style.css                   # Lembar gaya CSS utama sistem
```

---

## Panduan Instalasi

### Prasyarat Sistem
- Web Server: Apache (melalui XAMPP, Laragon, atau konfigurasi mandiri)
- PHP Runtime: Versi 7.4 atau versi yang lebih baru (disarankan PHP 8.x)
- Database Server: MySQL 5.7+ atau MariaDB 10.4+
- Web Browser: Google Chrome, Mozilla Firefox, Microsoft Edge, atau browser berbasis Chromium terkini

### Langkah Pemasangan

1. **Kloning Repositori**
   ```bash
   git clone https://github.com/Yukomyy/tehkitta-ikm.git
   ```
   Pindahkan folder hasil kloning ke direktori dokumen web server (misalnya `htdocs` pada XAMPP atau `www` pada Laragon).

2. **Impor Basis Data**
   - Akses antarmuka phpMyAdmin melalui `http://localhost/phpmyadmin`.
   - Buat basis data baru bernama `ptrafikamandiri`.
   - Pilih tab **Import**, arahkan ke berkas `ptrafikamandiri.sql` yang tersedia di direktori proyek, lalu jalankan impor.

3. **Konfigurasi Koneksi Basis Data**
   Periksa berkas `koneksi.php` pada direktori root dan sesuaikan kredensial server lokal jika berbeda dari konfigurasi bawaan:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "ptrafikamandiri";
   ```

4. **Inisialisasi Pengguna Awal**
   Buka peramban web dan jalankan skrip seeder untuk memastikan struktur tabel pengguna dan akun default terpasang:
   ```
   http://localhost/tehkitta-ikm/auth/seeder.php
   ```

---

## Akun Pengguna Default

Setelah inisialisasi seeder selesai, akun bawaan yang dapat digunakan meliputi:

| Peran (Role) | Alamat Email | Kata Sandi |
|---|---|---|
| Administrator | `admin@gmail.com` | `admin123` |
| Staff Kasir | `staff@gmail.com` | `admin123` |

---

## Catatan Keamanan

- **Berkas Seeder**: Setelah instalasi pada lingkungan produksi atau pengujian selesai, segera hapus atau batasi akses berkas `auth/seeder.php` untuk mencegah inisialisasi ulang yang tidak diinginkan.
- **Kredensial Server**: Selalu ubah kata sandi akun default sebelum menerapkan aplikasi pada lingkungan publik.
- **Konfigurasi Produksi**: Matikan pelaporan kesalahan PHP terperinci (*display_errors = Off*) pada server produksi guna mencegah kebocoran informasi internal sistem.

---

## Lisensi dan Hak Cipta

Hak Cipta (c) PT Rafika Mandiri. Seluruh hak cipta dilindungi undang-undang.
