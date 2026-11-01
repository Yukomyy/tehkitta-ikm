-- ============================================================
-- MIGRASI: Tambah tabel users untuk sistem autentikasi
-- Jalankan query ini di phpMyAdmin atau MySQL CLI
-- Database: ptrafikamandiri
-- ============================================================

USE ptrafikamandiri;

-- ─── Tabel users ───
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ─── Data Akun Awal ───
-- Kata sandi dienkripsi menggunakan Bcrypt (PASSWORD_BCRYPT)
INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
('Nadya Aulia',  'admin@gmail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'staff@gmail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff');

-- Catatan:
-- Kata sandi disimpan secara aman dalam bentuk hash satu arah (Bcrypt).
-- Untuk konfigurasi kredensial awal mandiri, gunakan seeder berbasis lingkungan (.env):
-- Akses melalui peramban: http://localhost/tehkitta-ikm/auth/seeder.php
-- Setelah inisialisasi selesai, hapus berkas seeder.php demi keamanan server.
