<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_nama  = $_SESSION['user_nama']  ?? 'Pengguna';
$user_email = $_SESSION['user_email'] ?? '';
$user_role  = $_SESSION['user_role']  ?? 'staff';
$parts = array_filter(explode(' ', trim($user_nama)));
$inisial = '';
foreach ($parts as $p) { $inisial .= strtoupper(mb_substr($p,0,1)); if(strlen($inisial)>=2) break; }
$inisial = $inisial ?: 'U';
$toast = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);
$cur = $_SERVER['PHP_SELF'];
function nav_active(string $path): string { global $cur; return str_contains($cur,$path) ? ' active' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TehKitta — Sistem Kasir</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../style.css">
<style>
.nav-group { margin-bottom: 4px; }
.nav-group-toggle { display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:10px;cursor:pointer;transition:all .2s;margin-bottom:2px;border:none;background:none;width:100%;text-align:left;color:var(--gray-500);font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:500; }
.nav-group-toggle:hover { background:var(--gray-50);color:var(--gray-700); }
.nav-group-toggle.open { color:var(--accent); }
.nav-group-arrow { margin-left:auto;font-size:.65rem;transition:transform .2s; }
.nav-group-toggle.open .nav-group-arrow { transform:rotate(180deg); }
.nav-sub { display:none;flex-direction:column;gap:2px;padding-left:14px; }
.nav-sub.open { display:flex; }
.nav-sub .nav-item { font-size:.8rem; padding:6px 10px; }
</style>
</head>
<body>
<?php if ($toast): ?>
<div class="toast-stack" id="toastStack">
  <div class="toast" id="mainToast">
    <div class="toast-bar" style="background:<?= $toast['type']==='success'?'#2da042':'#e53e3e' ?>;"></div>
    <div style="flex:1;">
      <div style="font-weight:700;font-size:.82rem;color:#1a2130;margin-bottom:2px;display:flex;align-items:center;gap:6px;"><?php if($toast['type']==='success'): ?><svg viewBox="0 0 24 24" fill="none" stroke="#2da042" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg> Berhasil<?php else: ?><svg viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg> Error<?php endif; ?></div>
      <div style="font-size:.78rem;color:#5f6b7a;"><?= htmlspecialchars($toast['msg']) ?></div>
    </div>
    <button onclick="document.getElementById('mainToast').remove()" style="background:none;border:none;cursor:pointer;color:#b8c0cc;padding:2px 4px;display:flex;align-items:center;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" width="14" height="14"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
  </div>
</div>
<?php endif; ?>

<aside class="sidebar">
  <div class="green-strip"></div>
  <div class="sidebar-logo" style="gap:12px;">
    <div class="logo-mark">
      <img src="../logo.png" alt="Logo" style="width:60px;height:auto;object-fit:contain;" onerror="this.style.display='none';this.parentElement.textContent='TK';">
    </div>
    <span class="logo-text">Teh<span> Kitta</span></span>
  </div>

  <div style="overflow-y:auto;flex:1;padding-bottom:8px;">
    <!-- Dashboard -->
    <div class="sidebar-section">
      <div class="sidebar-section-label">Menu Utama</div>
      <a href="../dashboard/index.php" class="nav-item<?= nav_active('/dashboard/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div> Dashboard
      </a>
    </div>

    <!-- Transaksi -->
    <div class="sidebar-section">
      <div class="sidebar-section-label">Transaksi</div>
      <a href="../penjualan/index.php" class="nav-item<?= nav_active('/penjualan/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div> Penjualan
      </a>
      <a href="../pembelian/index.php" class="nav-item<?= nav_active('/pembelian/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div> Pembelian
      </a>
    </div>

    <!-- Master Data -->
    <div class="sidebar-section">
      <div class="sidebar-section-label">Master Data</div>
      <a href="../customer/index.php" class="nav-item<?= nav_active('/customer/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div> Customer
      </a>
      <a href="../petugas/index.php" class="nav-item<?= nav_active('/petugas/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></div> Petugas
      </a>
      <a href="../supplier/index.php" class="nav-item<?= nav_active('/supplier/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div> Supplier
      </a>
      <a href="../produk/index.php" class="nav-item<?= nav_active('/produk/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div> Produk
      </a>
      <a href="../barang/index.php" class="nav-item<?= nav_active('/barang/') ?>" style="text-decoration:none;">
        <div class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg></div> Barang
      </a>
    </div>
  </div>

  <div class="sidebar-footer">
    <div class="user-card" onclick="toggleSideUser()" style="position:relative;">
      <div class="user-avatar"><?= htmlspecialchars($inisial) ?></div>
      <div class="user-info">
        <div class="user-name"><?= htmlspecialchars($user_nama) ?></div>
        <div class="user-role"><?= ucfirst(htmlspecialchars($user_role)) ?></div>
      </div>
      <span class="user-more">▾</span>
      <div id="sideUserDrop" style="display:none;position:absolute;bottom:calc(100% + 6px);left:0;right:0;background:#fff;border:1px solid #eaecef;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);padding:8px;z-index:200;">
        <div style="padding:8px 10px 8px;border-bottom:1px solid #eaecef;margin-bottom:4px;">
          <div style="font-size:.8rem;font-weight:700;color:#1a2130;"><?= htmlspecialchars($user_nama) ?></div>
          <div style="font-size:.72rem;color:#8b96a5;margin-top:2px;"><?= htmlspecialchars($user_email) ?></div>
        </div>
        <a href="../auth/auth_controller.php?action=logout" style="display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:8px;text-decoration:none;font-size:.8rem;font-weight:600;color:#e53e3e;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='transparent'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Logout
        </a>
      </div>
    </div>
  </div>
</aside>

<header class="topbar">
  <div class="topbar-title">
    <h2 id="pageTitle">Teh Kitta</h2>
    <div class="breadcrumb" id="pageBreadcrumb">Sistem Kasir</div>
  </div>
  <div class="topbar-actions">
    <div class="topbar-profile" onclick="toggleTopUser()" style="display:flex;align-items:center;gap:10px;cursor:pointer;margin-left:12px;padding:4px 14px 4px 4px;border-radius:50px;border:1px solid #e2e8f0;background:#f8fafc;transition:all .2s;position:relative;" onmouseover="this.style.borderColor='#aee5ba';this.style.background='#f0faf2';" onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#f8fafc';">
      <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4aba5e,#228234);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;"><?= htmlspecialchars($inisial) ?></div>
      <div style="display:flex;flex-direction:column;">
        <span style="font-size:.85rem;font-weight:700;color:#1e293b;line-height:1.2;"><?= htmlspecialchars($user_nama) ?></span>
        <span style="font-size:.7rem;color:#8b96a5;"><?= ucfirst(htmlspecialchars($user_role)) ?></span>
      </div>
      <span style="color:#94a3b8;margin-left:4px;display:flex;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" width="12" height="12"><polyline points="6 9 12 15 18 9"/></svg></span>
      <div id="topUserDrop" style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:200px;background:#fff;border:1px solid #eaecef;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.1);padding:8px;z-index:300;">
        <div style="padding:8px 10px;border-bottom:1px solid #eaecef;margin-bottom:4px;">
          <div style="font-size:.8rem;font-weight:700;color:#1a2130;"><?= htmlspecialchars($user_nama) ?></div>
          <div style="font-size:.72rem;color:#8b96a5;"><?= htmlspecialchars($user_email) ?></div>
        </div>
        <a href="../auth/auth_controller.php?action=logout" style="display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:8px;text-decoration:none;font-size:.8rem;font-weight:600;color:#e53e3e;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='transparent'">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Logout
        </a>
      </div>
    </div>
  </div>
</header>
<main>