# 🍵 Teh Kitta — Sistem Manajemen & Kasir POS IKM (PT Rafika Mandiri)

Sistem Informasi Manajemen Bisnis dan Point of Sale (POS) berbasis web yang dirancang khusus untuk **Teh Kitta**, sebuah unit usaha IKM (Industri Kecil dan Menengah) di bawah naungan **PT Rafika Mandiri**. Aplikasi ini menggabungkan *Landing Page* profil kemitraan usaha dengan sistem *back-office* komprehensif untuk mengelola operasional harian, transaksi penjualan, pembelian bahan baku, inventaris, dan laporan keuangan.

---

## 🚀 Fitur Utama

### 1. 🌐 Landing Page & Company Profile
- Tampilan modern, responsif, dan elegan untuk *branding* produk minuman Teh Kitta.
- Informasi peluang **Kemitraan (Franchise)** lengkap dengan skema paket usaha.
- Katalog varian menu unggulan, keunggulan produk, testimoni, dan kontak kemitraan.
- Akses langsung menuju portal login mitra / staf internal.

### 2. 🔐 Autentikasi & Hak Akses Bertingkat (RBAC)
- Autentikasi sesi aman menggunakan enkripsi sandi standar industri (`password_hash` dengan algoritma Bcrypt).
- Middleware penjaga rute (`auth/middleware.php`) untuk proteksi akses halaman internal.
- Dukungan Multi-Role:
  - **Admin**: Akses penuh ke seluruh menu master data, transaksi, laporan, dan pengelolaan akun.
  - **Staff**: Akses operasional harian seperti kasir penjualan dan pencatatan transaksi.

### 3. 📦 Manajemen Master Data
- **Data Barang (Bahan Baku)**: Pengelolaan stok bahan baku (teh, gula, cup, sedotan, plastik, dll.) lengkap dengan satuan dan harga.
- **Data Produk**: Pengelolaan menu minuman siap jual beserta harga jual.
- **Data Customer**: Manajemen data pelanggan dan mitra usaha.
- **Data Supplier**: Rekapitulasi vendor/pemasok bahan baku.
- **Data Petugas**: Manajemen data staf, kasir, dan operator sistem.

### 4. 🛒 Modul Transaksi Terpadu
- **Pembelian Bahan Baku**:
  - Pencatatan transaksi pembelian dari supplier dengan detail multi-item.
  - Kalkulasi otomatis total biaya pembelian.
- **Penjualan (Kasir / Point of Sale)**:
  - Antarmuka transaksi penjualan yang cepat dan intuitif.
  - Detail transaksi penjualan per produk, perhitungan subtotal, dan total belanja.
  - Fitur cetak nota / struk penjualan langsung ke printer kasir / PDF.

### 5. 📊 Laporan & Rekapitulasi
- **Laporan Penjualan**: Filter riwayat penjualan berdasarkan rentang tanggal/periode dengan fitur cetak rekapitulasi.
- **Laporan Pembelian**: Filter pengeluaran belanja bahan baku dan cetak laporan rekapitulasi.
- **Dashboard Analitik**: Ringkasan performa bisnis, statistik omzet, total transaksi harian, dan ringkasan aktivitas terkini.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP 8.x (Native / Procedural Modular Architecture)
- **Database**: MySQL / MariaDB (Relasional)
- **Frontend**: HTML5, Modern CSS3 (Grid & Flexbox), Vanilla JavaScript
- **Tipografi**: *Plus Jakarta Sans* & *Instrument Serif* (Google Fonts)
- **Koneksi Database**: PHP Data Extension (`mysqli`)

---

## 📂 Struktur Direktori

```plaintext
estehikmrevisi/
├── auth/                       # Modul Autentikasi & Hak Akses
│   ├── auth_controller.php     # Logika proses login & logout
│   ├── login.php               # Halaman antarmuka login
│   ├── middleware.php          # Session & route guard
│   ├── seeder.php              # Script pembuat akun awal & tabel users
│   └── users_migration.sql     # Skema tabel users terpisah
├── barang/                     # CRUD Master Data Bahan Baku
├── customer/                   # CRUD Master Data Pelanggan / Mitra
├── dashboard/                  # Halaman Dashboard Utama & Metrik Bisnis
├── laporan/                    # Modul Rekapitulasi & Cetak Laporan
│   ├── cetak_laporan_beli.php  # Cetak rekapitulasi pembelian
│   ├── cetak_laporan_jual.php  # Cetak rekapitulasi penjualan
│   ├── cetak_pembelian.php     # Cetak faktur pembelian
│   ├── cetak_penjualan.php     # Cetak nota penjualan
│   ├── pembelian.php           # Filter & tinjau laporan pembelian
│   └── penjualan.php           # Filter & tinjau laporan penjualan
├── layouts/                    # Template UI Bersama
│   ├── header.php              # Sidebar, topbar navigasi & toast notification
│   └── footer.php              # Footer penutup struktur halaman
├── pembelian/                  # Modul Transaksi Pembelian Bahan Baku
├── penjualan/                  # Modul Transaksi Penjualan & POS Kasir
├── petugas/                    # CRUD Data Staf / Petugas Kasir
├── produk/                     # CRUD Master Data Menu Minuman
├── supplier/                   # CRUD Master Data Pemasok
├── index.php                   # Pintu masuk utama sistem (Routing ke landing)
├── landing.php                 # Halaman Company Profile & Kemitraan
├── koneksi.php                 # Konfigurasi koneksi database MySQL
├── logo.png                    # Aset logo resmi Teh Kitta
├── ptrafikamandiri.sql         # Dump skema database lengkap & data awal
├── script.js                   # Interaksi antarmuka (DOM scripts)
└── style.css                   # Lembar gaya CSS utama
```

---

## 💻 Panduan Instalasi & Menjalankan Sistem

### 1. Prasyarat
- Web Server lokal: [XAMPP](https://www.apachefriends.org/) / [Laragon](https://laragon.org/) / Apache dengan PHP 7.4 ke atas.
- MySQL atau MariaDB Server.
- Web Browser modern (Chrome, Edge, Firefox, Safari).

### 2. Langkah Setup Database
1. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru bernama `ptrafikamandiri`.
3. Pilih database tersebut, lalu pilih tab **Import**.
4. Pilih file `ptrafikamandiri.sql` yang ada di root direktori project, lalu klik **Go** / **Kirim**.

### 3. Konfigurasi Koneksi Database
Jika menggunakan konfigurasi server selain default XAMPP, sesuaikan pengaturan kredensial di file [`koneksi.php`](file:///c:/Users/LOQ/Downloads/Github/estehikmrevisi/koneksi.php):
```php
$host = "localhost"; 
$user = "root";     
$pass = "";          
$db   = "ptrafikamandiri";
```

### 4. Setup Akun Awal (Seeder)
Buka browser dan jalankan script seeder untuk membuat tabel `users` dan akun awal:
```
http://localhost/estehikmrevisi/auth/seeder.php
```
> ⚠️ **Catatan Keamanan**: Setelah seeder berhasil dijalankan dan akun terverifikasi, disarankan untuk menghapus atau memindahkan file `auth/seeder.php` dari server produksi.

### 5. Kredensial Login Default

| Role | Email | Password |
|---|---|---|
| **Admin** | `admin@gmail.com` | `admin123` |
| **Staff** | `staff@gmail.com` | `admin123` |

---

## 📄 Lisensi & Hak Cipta

Dikembangkan untuk **PT Rafika Mandiri** — Hak Cipta dilindungi undang-undang.
