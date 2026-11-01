<?php
/**
 * SEEDER — Buat tabel users + akun dummy
 * Akses: http://localhost/estehikm/auth/seeder.php
 * HAPUS file ini setelah berhasil!
 */

require_once '../koneksi.php';

$log = [];

// 1. Buat tabel
$sql_create = "CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `nama`       VARCHAR(100) NOT NULL,
  `email`      VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `role`       ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

if (mysqli_query($koneksi, $sql_create)) {
    $log[] = ['ok', 'Tabel <b>users</b> berhasil dibuat (atau sudah ada).'];
} else {
    $log[] = ['err', 'Gagal buat tabel: ' . mysqli_error($koneksi)];
}

$adminEmail = env('INITIAL_ADMIN_EMAIL', 'admin@gmail.com');
$adminPass  = env('INITIAL_ADMIN_PASSWORD', 'admin123');
$staffEmail = env('INITIAL_STAFF_EMAIL', 'staff@gmail.com');
$staffPass  = env('INITIAL_STAFF_PASSWORD', 'admin123');

// 2. Akun Inisial (dikonfigurasi melalui .env)
$accounts = [
    ['Nadya Aulia',  $adminEmail,  $adminPass, 'admin'],
    ['Budi Santoso', $staffEmail,  $staffPass, 'staff'],
];

foreach ($accounts as $acc) {
    [$nama, $email, $pass, $role] = $acc;

    // Cek duplikat
    $e   = mysqli_real_escape_string($koneksi, $email);
    $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$e'");
    if ($cek && mysqli_num_rows($cek) > 0) {
        $log[] = ['info', "Akun <b>$email</b> sudah ada — dilewati."];
        continue;
    }

    // Hash password di server ini (bcrypt)
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $n    = mysqli_real_escape_string($koneksi, $nama);
    $r    = mysqli_real_escape_string($koneksi, $role);
    $h    = mysqli_real_escape_string($koneksi, $hash);

    $ins = "INSERT INTO users (nama, email, password, role) VALUES ('$n','$e','$h','$r')";
    if (mysqli_query($koneksi, $ins)) {
        $log[] = ['ok', "Akun <b>$nama</b> ($email) berhasil dibuat."];
    } else {
        $log[] = ['err', "Gagal insert $email: " . mysqli_error($koneksi)];
    }
}

// 3. Verifikasi password bisa dipakai
$verify_ok = false;
$res = mysqli_query($koneksi, "SELECT password FROM users WHERE email='" . mysqli_real_escape_string($koneksi, $adminEmail) . "' LIMIT 1");
if ($res && $row = mysqli_fetch_assoc($res)) {
    $verify_ok = password_verify($adminPass, $row['password']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Seeder — Teh Kitta</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#f0faf2;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.card{background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.1);padding:36px 40px;max-width:500px;width:100%}
h2{font-size:1.3rem;color:#1a2130;margin-bottom:4px}
.sub{color:#8b96a5;font-size:.83rem;margin-bottom:24px}
.log{display:flex;flex-direction:column;gap:8px;margin-bottom:20px}
.msg{padding:10px 14px;border-radius:8px;font-size:.83rem;line-height:1.5}
.ok  {background:#f0faf2;color:#228234;border:1px solid #aee5ba}
.err {background:#fff5f5;color:#c53030;border:1px solid #fecaca}
.info{background:#fffbeb;color:#92400e;border:1px solid #fde68a}
.accounts{background:#f8fafc;border-radius:10px;padding:16px;font-size:.82rem;margin-bottom:16px}
.accounts h4{color:#1a2130;margin-bottom:10px;font-size:.85rem}
.row{display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid #eaecef}
.row:last-child{border-bottom:none}
.label{color:#8b96a5}.val{font-weight:700;font-family:monospace;color:#1a2130}
.verify{padding:10px 14px;border-radius:8px;font-size:.82rem;margin-bottom:16px}
.btn{display:inline-block;padding:12px 28px;background:#2da042;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;font-size:.88rem;transition:.2s}
.btn:hover{background:#228234}
.warn{margin-top:16px;padding:10px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;font-size:.75rem;color:#92400e}
</style>
</head>
<body>
<div class="card">
  <h2>Inisialisasi Basis Data & Akun</h2>
  <p class="sub">Membuat tabel <code>users</code> dan akun awal sistem</p>

  <div class="log">
    <?php foreach ($log as [$type, $msg]): ?>
      <div class="msg <?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>
  </div>

  <div class="verify <?= $verify_ok ? 'ok' : 'err' ?>">
    <?= $verify_ok
      ? 'Verifikasi enkripsi password berhasil. Akun siap digunakan.'
      : 'Verifikasi enkripsi password gagal. Periksa konfigurasi tabel users.' ?>
  </div>

  <div class="accounts">
    <h4>Akun Terdaftar (Sesuai Konfigurasi .env)</h4>
    <div class="row"><span class="label">Admin Email</span><span class="val"><?= htmlspecialchars($adminEmail) ?></span></div>
    <div class="row"><span class="label">Staff Email</span><span class="val"><?= htmlspecialchars($staffEmail) ?></span></div>
    <div class="row"><span class="label">Status Password</span><span class="val">Tersimpan dalam bentuk Hash Bcrypt</span></div>
  </div>

  <a href="../auth/login.php" class="btn">Pergi ke Halaman Login</a>

  <div class="warn">
    <b>PENTING:</b> Demi keamanan, setelah login berhasil, <b>hapus file seeder.php</b> ini dari server.
  </div>
</div>
</body>
</html>
