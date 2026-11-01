<?php
/**
 * HALAMAN LOGIN — TehKitta
 * Design: Hijau modern, minimalis, clean
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../koneksi.php';

$showDemo  = (bool) env('SHOW_DEMO_CREDENTIALS', false);
$demoEmail = env('INITIAL_ADMIN_EMAIL', 'admin@gmail.com');
$demoPass  = env('INITIAL_ADMIN_PASSWORD', 'admin123');

// Jika sudah login, langsung ke dashboard
if (!empty($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit;
}

// Ambil error & alert dari session/query
$error = '';
$alert = '';

if (!empty($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if (isset($_GET['alert'])) {
    if ($_GET['alert'] === 'unauthorized') {
        $alert = 'Anda harus login terlebih dahulu untuk mengakses halaman tersebut.';
    } elseif ($_GET['alert'] === 'logged_out') {
        $alert = 'Anda telah keluar dari sistem. Sampai jumpa kembali.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — TehKitta</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<style>
/* ── RESET & TOKENS ── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
:root {
  --green-50:  #f0faf2;
  --green-100: #d8f3de;
  --green-200: #aee5ba;
  --green-300: #78d08a;
  --green-400: #4aba5e;
  --green-500: #2da042;
  --green-600: #228234;
  --green-700: #1a6328;
  --gray-50:   #f8fafc;
  --gray-100:  #eaecef;
  --gray-200:  #d8dde3;
  --gray-300:  #b8c0cc;
  --gray-400:  #8b96a5;
  --gray-500:  #5f6b7a;
  --gray-600:  #3d4756;
  --gray-700:  #2a3341;
  --gray-800:  #1a2130;
  --danger:    #e53e3e;
  --warning:   #d97706;
  --white:     #ffffff;
}
html, body {
  min-height: 100vh;
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--green-50);
}

/* ── LAYOUT ── */
.login-page {
  display: grid;
  grid-template-columns: 1fr 1fr;
  min-height: 100vh;
}
@media (max-width: 768px) {
  .login-page { grid-template-columns: 1fr; }
  .login-hero  { display: none; }
}

/* ── LEFT HERO PANEL ── */
.login-hero {
  background: linear-gradient(145deg, var(--green-700) 0%, var(--green-500) 50%, var(--green-400) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 50px;
  position: relative;
  overflow: hidden;
}
.login-hero::before {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 300px; height: 300px;
  border-radius: 50%;
  background: rgba(255,255,255,0.06);
}
.login-hero::after {
  content: '';
  position: absolute;
  bottom: -60px; left: -60px;
  width: 220px; height: 220px;
  border-radius: 50%;
  background: rgba(255,255,255,0.05);
}
.hero-blob {
  position: absolute;
  width: 180px; height: 180px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
  top: 40%; left: 60%;
  transform: translate(-50%, -50%);
}
.hero-logo {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 48px;
  position: relative; z-index: 2;
}
.hero-logo-icon {
  width: 52px; height: 52px;
  background: rgba(255,255,255,0.15);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.2);
}
.hero-logo-icon img { width: 36px; height: auto; filter: brightness(10); }
.hero-logo-text {
  font-size: 1.6rem;
  font-weight: 800;
  color: #fff;
  letter-spacing: -0.5px;
}
.hero-logo-text span { opacity: 0.75; }
.hero-title {
  font-size: 2rem;
  font-weight: 800;
  color: #fff;
  line-height: 1.2;
  margin-bottom: 16px;
  position: relative; z-index: 2;
  text-align: center;
}
.hero-title em {
  font-style: normal;
  font-family: 'Instrument Serif', serif;
  opacity: 0.85;
}
.hero-sub {
  color: rgba(255,255,255,0.7);
  font-size: 0.9rem;
  text-align: center;
  line-height: 1.6;
  position: relative; z-index: 2;
  max-width: 300px;
}
.hero-features {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 48px;
  position: relative; z-index: 2;
  width: 100%;
  max-width: 300px;
}
.hero-feat {
  display: flex;
  align-items: center;
  gap: 12px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.15);
  border-radius: 10px;
  padding: 12px 16px;
  backdrop-filter: blur(4px);
}
.feat-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  width: 22px; height: 22px; color: #fff;
}
.feat-icon svg { width: 18px; height: 18px; }
.feat-text { font-size: 0.82rem; color: rgba(255,255,255,0.85); font-weight: 500; }

/* back to home */
.back-home {
  position: absolute; top: 26px; left: 28px; z-index: 5;
  display: inline-flex; align-items: center; gap: 7px;
  padding: 8px 15px; border-radius: 999px;
  background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22);
  color: #fff; font-size: 0.8rem; font-weight: 600; text-decoration: none;
  backdrop-filter: blur(6px); transition: all 0.2s;
}
.back-home:hover { background: rgba(255,255,255,0.22); transform: translateX(-3px); }

/* product cup illustration */
.login-cup {
  position: relative; width: 92px; height: 120px;
  margin-bottom: 36px; z-index: 2;
  animation: cupFloat 5s ease-in-out infinite;
}
@keyframes cupFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
.lc-body {
  position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
  width: 70px; height: 100px;
  background: linear-gradient(180deg, rgba(255,255,255,0.5), rgba(255,255,255,0.28));
  border: 2px solid rgba(255,255,255,0.65); border-radius: 6px 6px 16px 16px;
  overflow: hidden; box-shadow: inset 0 0 16px rgba(255,255,255,0.35);
}
.lc-tea {
  position: absolute; bottom: 0; left: 0; right: 0; height: 72%;
  background: linear-gradient(180deg, #d68a35, #8f4e16);
}
.lc-tea::after { content:''; position:absolute; top:0; left:0; right:0; height:6px; background:rgba(255,255,255,0.3); }
.lc-ice { position: absolute; background: rgba(255,255,255,0.55); border:1px solid rgba(255,255,255,0.7); border-radius:4px; }
.lc-ice.a { width:18px; height:16px; top:36%; left:20%; transform:rotate(16deg); }
.lc-ice.b { width:15px; height:14px; top:46%; right:18%; transform:rotate(-12deg); }
.lc-straw {
  position: absolute; top:-14px; left:56%; width:9px; height:84px;
  background: linear-gradient(90deg,#e23b3b,#c92b2b); border-radius:5px;
  transform: rotate(11deg); box-shadow:0 2px 5px rgba(0,0,0,0.2); z-index:2;
}

/* ── RIGHT FORM PANEL ── */
.login-form-panel {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px 32px;
  background: var(--white);
}
.login-card {
  width: 100%;
  max-width: 420px;
}
.card-eyebrow {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 32px;
}
.eyebrow-line {
  width: 28px; height: 3px;
  background: linear-gradient(90deg, var(--green-500), var(--green-300));
  border-radius: 3px;
}
.eyebrow-text {
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  color: var(--green-500);
}
.card-title {
  font-size: 1.7rem;
  font-weight: 800;
  color: var(--gray-800);
  letter-spacing: -0.5px;
  margin-bottom: 6px;
}
.card-sub {
  font-size: 0.85rem;
  color: var(--gray-400);
  margin-bottom: 32px;
}

/* ── ALERTS ── */
.alert {
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 0.82rem;
  margin-bottom: 20px;
  display: flex;
  align-items: flex-start;
  gap: 10px;
  line-height: 1.5;
}
.alert-error {
  background: #fff5f5;
  border: 1px solid #fecaca;
  color: #c53030;
}
.alert-info {
  background: var(--green-50);
  border: 1px solid var(--green-200);
  color: var(--green-700);
}
.alert-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }

/* ── FORM ELEMENTS ── */
.form-group {
  margin-bottom: 18px;
}
.form-label {
  display: block;
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--gray-600);
  margin-bottom: 6px;
  letter-spacing: 0.2px;
}
.input-wrap {
  position: relative;
}
.input-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 16px; height: 16px;
  color: var(--gray-300);
  pointer-events: none;
  transition: color 0.2s;
}
.form-input {
  width: 100%;
  background: var(--gray-50);
  border: 1.5px solid var(--gray-100);
  border-radius: 10px;
  padding: 11px 42px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.88rem;
  color: var(--gray-700);
  outline: none;
  transition: all 0.2s;
}
.form-input::placeholder { color: var(--gray-300); }
.form-input:hover { border-color: var(--gray-200); background: var(--white); }
.form-input:focus {
  border-color: var(--green-400);
  background: var(--white);
  box-shadow: 0 0 0 3px rgba(45,160,66,0.1);
}
.form-input:focus + .input-focus-border { opacity: 1; }
.input-wrap:focus-within .input-icon { color: var(--green-500); }

/* Toggle password visibility */
.toggle-pass {
  position: absolute;
  right: 13px;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  cursor: pointer;
  color: var(--gray-300);
  padding: 2px;
  transition: color 0.2s;
  display: flex; align-items: center;
}
.toggle-pass:hover { color: var(--gray-500); }

/* Remember row */
.form-row-check {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}
.check-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
}
.check-wrap input[type="checkbox"] {
  width: 16px; height: 16px;
  accent-color: var(--green-500);
  cursor: pointer;
}
.check-label {
  font-size: 0.8rem;
  color: var(--gray-500);
  cursor: pointer;
}
.forgot-link {
  font-size: 0.8rem;
  color: var(--green-600);
  text-decoration: none;
  font-weight: 600;
}
.forgot-link:hover { text-decoration: underline; }

/* Submit button */
.btn-login {
  width: 100%;
  padding: 13px;
  background: linear-gradient(135deg, var(--green-500), var(--green-600));
  color: #fff;
  border: none;
  border-radius: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.9rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  letter-spacing: 0.2px;
  box-shadow: 0 4px 14px rgba(45,160,66,0.35);
}
.btn-login:hover {
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(45,160,66,0.45);
  background: linear-gradient(135deg, var(--green-400), var(--green-500));
}
.btn-login:active {
  transform: translateY(0);
  box-shadow: 0 2px 8px rgba(45,160,66,0.3);
}
.btn-login.loading { opacity: 0.7; cursor: not-allowed; }

/* Divider */
.divider {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 24px 0;
}
.divider-line { flex: 1; height: 1px; background: var(--gray-100); }
.divider-text { font-size: 0.73rem; color: var(--gray-300); font-weight: 500; }

/* Demo hint */
.demo-hint {
  background: var(--green-50);
  border: 1px dashed var(--green-300);
  border-radius: 10px;
  padding: 14px 16px;
}
.demo-hint-title {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--green-700);
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 6px;
}
.demo-account {
  display: flex;
  justify-content: space-between;
  font-size: 0.78rem;
  color: var(--green-600);
  padding: 3px 0;
}
.demo-val { font-weight: 700; font-family: monospace; }

/* Footer */
.form-footer {
  text-align: center;
  margin-top: 28px;
  font-size: 0.75rem;
  color: var(--gray-400);
}

/* Spinner */
.spinner {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  display: none;
}
.btn-login.loading .spinner { display: block; }
.btn-login.loading .btn-text { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>

<div class="login-page">

  <!-- ── LEFT HERO ── -->
  <div class="login-hero">
    <div class="hero-blob"></div>

    <a href="../landing.php" class="back-home" title="Kembali ke beranda">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Beranda
    </a>

    <div class="hero-logo">
      <div class="hero-logo-icon">
        <img src="../logo.png" alt="Logo Teh Kitta" onerror="this.style.display='none';this.parentElement.textContent='TK';">
      </div>
      <span class="hero-logo-text">Teh<span>Kitta</span></span>
    </div>

    <!-- product illustration: gelas teh -->
    <div class="login-cup">
      <div class="lc-straw"></div>
      <div class="lc-body"><div class="lc-tea"></div><span class="lc-ice a"></span><span class="lc-ice b"></span></div>
    </div>

    <h1 class="hero-title">Selamat datang kembali,<br><em>Mitra Teh Kitta</em></h1>
    <p class="hero-sub">Pantau penjualan harian, kelola stok, dan lihat perkembangan outlet Anda dalam satu tempat.</p>

    <div class="hero-features">
      <div class="hero-feat">
        <span class="feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></span>
        <span class="feat-text">Ringkasan penjualan harian</span>
      </div>
      <div class="hero-feat">
        <span class="feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span>
        <span class="feat-text">Catat transaksi & pembelian</span>
      </div>
      <div class="hero-feat">
        <span class="feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>
        <span class="feat-text">Kelola produk & stok bahan</span>
      </div>
      <div class="hero-feat">
        <span class="feat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>
        <span class="feat-text">Laporan rapi & siap cetak</span>
      </div>
    </div>
  </div>

  <!-- ── RIGHT FORM ── -->
  <div class="login-form-panel">
    <div class="login-card">

      <div class="card-eyebrow">
        <div class="eyebrow-line"></div>
        <span class="eyebrow-text">Portal Mitra</span>
      </div>

      <h2 class="card-title">Masuk ke Akun Anda</h2>
      <p class="card-sub">Gunakan email dan kata sandi mitra untuk membuka dashboard outlet Anda.</p>

      <!-- Alert error -->
      <?php if ($error): ?>
      <div class="alert alert-error">
        <span class="alert-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></span>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <!-- Alert info (logout / unauthorized) -->
      <?php if ($alert): ?>
      <div class="alert alert-info">
        <span class="alert-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></span>
        <?= htmlspecialchars($alert) ?>
      </div>
      <?php endif; ?>

      <!-- Form login -->
      <form method="POST" action="../auth/auth_controller.php" id="loginForm" novalidate>
        <input type="hidden" name="action" value="login">

        <!-- Email -->
        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2"/>
              <path d="m22 7-10 7L2 7"/>
            </svg>
            <input
              type="email"
              id="email"
              name="email"
              class="form-input"
              placeholder="admin@gmail.com"
              value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
              autocomplete="email"
              required
            >
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrap">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="11" width="18" height="11" rx="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            <input
              type="password"
              id="password"
              name="password"
              class="form-input"
              placeholder="Masukkan password"
              autocomplete="current-password"
              required
            >
            <button type="button" class="toggle-pass" onclick="togglePassword()" title="Tampilkan / Sembunyikan">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Remember me -->
        <div class="form-row-check">
          <label class="check-wrap">
            <input type="checkbox" name="remember" id="remember">
            <span class="check-label">Ingat saya selama 30 hari</span>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn-login" id="loginBtn">
          <div class="spinner"></div>
          <span class="btn-text">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="16" height="16">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Masuk Sekarang
          </span>
        </button>
      </form>

      <?php if ($showDemo): ?>
      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">Akun Demo</span>
        <div class="divider-line"></div>
      </div>

      <!-- Demo hint -->
      <div class="demo-hint">
        <div class="demo-hint-title">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          Gunakan akun berikut untuk mencoba:
        </div>
        <div class="demo-account">
          <span>Email</span>
          <span class="demo-val" onclick="fillDemo('email')" style="cursor:pointer;" title="Klik untuk isi otomatis"><?= htmlspecialchars($demoEmail) ?></span>
        </div>
        <div class="demo-account">
          <span>Password</span>
          <span class="demo-val" onclick="fillDemo('password')" style="cursor:pointer;" title="Klik untuk isi otomatis"><?= htmlspecialchars($demoPass) ?></span>
        </div>
        <div style="margin-top:10px;">
          <button type="button" onclick="fillDemo('all')" style="width:100%;padding:8px;background:var(--green-500);color:#fff;border:none;border-radius:7px;font-family:'Plus Jakarta Sans',sans-serif;font-size:0.78rem;font-weight:700;cursor:pointer;transition:background 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;" onmouseover="this.style.background='#228234'" onmouseout="this.style.background='#2da042'">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="14" height="14"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg> Isi Otomatis &amp; Login
          </button>
        </div>
      </div>
      <?php endif; ?>

      <p class="form-footer">
        © <?= date('Y') ?> Teh Kitta · Sistem Manajemen Penjualan Mitra
      </p>

    </div>
  </div>

</div>

<script>
// Toggle password visibility
function togglePassword() {
  const inp  = document.getElementById('password');
  const icon = document.getElementById('eyeIcon');
  const isHidden = inp.type === 'password';
  inp.type = isHidden ? 'text' : 'password';
  icon.innerHTML = isHidden
    ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}

<?php if ($showDemo): ?>
// Fill demo credentials
function fillDemo(field) {
  if (field === 'email' || field === 'all') {
    document.getElementById('email').value    = <?= json_encode($demoEmail) ?>;
  }
  if (field === 'password' || field === 'all') {
    document.getElementById('password').value = <?= json_encode($demoPass) ?>;
  }
  if (field === 'all') {
    document.getElementById('loginForm').submit();
    document.getElementById('loginBtn').classList.add('loading');
  }
}
<?php endif; ?>

// Loading state on submit
document.getElementById('loginForm').addEventListener('submit', function(e) {
  const email    = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  if (!email || !password) return; // biarkan HTML5 validation tampil
  document.getElementById('loginBtn').classList.add('loading');
});
</script>
</body>
</html>
