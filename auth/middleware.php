<?php
/**
 * AUTH MIDDLEWARE — TehKitta
 * Fix: REQUEST_URI fallback + buffer output agar header() tidak gagal
 */

// Buffer output supaya warning tidak memblokir header()
if (ob_get_level() === 0) ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    // Simpan halaman tujuan (REQUEST_URI aman di semua server)
    $request_uri = '';
    if (isset($_SERVER['REQUEST_URI'])) {
        $request_uri = $_SERVER['REQUEST_URI'];
    } elseif (isset($_SERVER['PHP_SELF'])) {
        $request_uri = $_SERVER['PHP_SELF'];
        if (!empty($_SERVER['QUERY_STRING'])) {
            $request_uri .= '?' . $_SERVER['QUERY_STRING'];
        }
    }
    if (!empty($request_uri)) {
        $_SESSION['redirect_after_login'] = $request_uri;
    }

    // Bersihkan buffer lalu redirect
    ob_end_clean();
    header('Location: ../auth/login.php');
    exit;
}

/**
 * Ambil data user login dari session
 */
function auth_user(): array {
    $nama = $_SESSION['user_nama'] ?? 'Pengguna';
    return [
        'id'      => $_SESSION['user_id']    ?? 0,
        'nama'    => $nama,
        'email'   => $_SESSION['user_email'] ?? '',
        'role'    => $_SESSION['user_role']  ?? 'staff',
        'inisial' => _get_inisial($nama),
    ];
}

function _get_inisial(string $nama): string {
    $parts   = array_filter(explode(' ', trim($nama)));
    $inisial = '';
    foreach ($parts as $p) {
        $inisial .= strtoupper(mb_substr($p, 0, 1));
        if (strlen($inisial) >= 2) break;
    }
    return $inisial ?: 'U';
}
