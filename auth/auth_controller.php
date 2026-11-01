<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once '../koneksi.php';
_auto_setup($koneksi);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') _handle_login();
if (($_GET['action'] ?? '') === 'logout') _handle_logout();

function _auto_setup($koneksi): void {
    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS `users` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `nama` VARCHAR(100) NOT NULL,
      `email` VARCHAR(100) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `role` ENUM('admin','staff') NOT NULL DEFAULT 'staff',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    // Add harga_beli to detail_pembelian if missing
    $cols = mysqli_query($koneksi, "SHOW COLUMNS FROM detail_pembelian LIKE 'harga_beli'");
    if ($cols && mysqli_num_rows($cols) === 0) {
        mysqli_query($koneksi, "ALTER TABLE detail_pembelian ADD COLUMN `harga_beli` decimal(15,2) DEFAULT 0 AFTER `id_barang`");
    }
    // Add stok to barang if missing
    $cols2 = mysqli_query($koneksi, "SHOW COLUMNS FROM barang LIKE 'stok'");
    if ($cols2 && mysqli_num_rows($cols2) === 0) {
        mysqli_query($koneksi, "ALTER TABLE barang ADD COLUMN `stok` int(11) DEFAULT 0 AFTER `satuan`");
    }
    $cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
    if ($cek && (int)mysqli_fetch_assoc($cek)['total'] === 0) {
        foreach ([['Nadya Aulia','admin@gmail.com','admin'],['Budi Santoso','staff@gmail.com','staff']] as [$n,$e,$r]) {
            $h = mysqli_real_escape_string($koneksi, password_hash('admin123', PASSWORD_BCRYPT));
            $n = mysqli_real_escape_string($koneksi, $n);
            $e = mysqli_real_escape_string($koneksi, $e);
            mysqli_query($koneksi, "INSERT IGNORE INTO users (nama,email,password,role) VALUES ('$n','$e','$h','$r')");
        }
    }
}
function _handle_login(): void {
    global $koneksi;
    $email = trim($_POST['email'] ?? ''); $password = trim($_POST['password'] ?? '');
    if (empty($email) || empty($password)) _redirect_error('Email dan password wajib diisi.');
    $e = mysqli_real_escape_string($koneksi, $email);
    $res = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$e' LIMIT 1");
    if (!$res || mysqli_num_rows($res) === 0) _redirect_error('Email atau password salah.');
    $user = mysqli_fetch_assoc($res);
    if (!password_verify($password, $user['password'])) _redirect_error('Email atau password salah.');
    $_SESSION['user_id'] = $user['id']; $_SESSION['user_nama'] = $user['nama'];
    $_SESSION['user_email'] = $user['email']; $_SESSION['user_role'] = $user['role'];
    $_SESSION['login_time'] = time();
    if (isset($_POST['remember'])) { $l=60*60*24*30; session_set_cookie_params($l); setcookie(session_name(),session_id(),time()+$l,'/'); }
    session_regenerate_id(true);
    $_SESSION['toast'] = ['type'=>'success','msg'=>'Selamat datang, '.htmlspecialchars($user['nama']).'!'];
    $redirect = $_SESSION['redirect_after_login'] ?? '../dashboard/index.php';
    unset($_SESSION['redirect_after_login']);
    header('Location: '.$redirect); exit;
}
function _handle_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); }
    session_destroy(); header('Location: ../auth/login.php?alert=logged_out'); exit;
}
function _redirect_error(string $msg): void { $_SESSION['login_error']=$msg; header('Location: ../auth/login.php'); exit; }
