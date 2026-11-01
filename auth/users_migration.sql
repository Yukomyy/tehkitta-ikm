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

-- ─── Seeder: akun dummy ───
-- Password: admin123  (di-hash dengan password_hash PHP)
INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
('Nadya Aulia',  'admin@gmail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'staff@gmail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff');

-- Catatan: Hash di atas adalah hash dari kata sandi "password"
-- Jika ingin hash "admin123", jalankan PHP berikut di browser atau CLI:
-- echo password_hash('admin123', PASSWORD_BCRYPT);
-- Lalu ganti hash di INSERT di atas.

-- ─── Atau gunakan seeder.php yang sudah disediakan ───
-- Buka browser: http://localhost/estehikm/auth/seeder.php
-- Setelah seeder berjalan, HAPUS file seeder.php dari server!
