<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';
$user = auth_user();

// Stats
$omzet_bulan  = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COALESCE(SUM(sub_total),0) as val FROM penjualan WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())"))['val'];
$beli_bulan   = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COALESCE(SUM(sub_total),0) as val FROM pembelian WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())"))['val'];
$trx_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as val FROM penjualan WHERE DATE(tanggal)=CURDATE()"))['val'];
$total_produk = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as val FROM produk"))['val'];
$total_cust   = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as val FROM customer"))['val'];
$total_supp   = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as val FROM supplier"))['val'];

// Transaksi terbaru
$recent_jual = mysqli_query($koneksi,"SELECT p.*, c.nama_customer FROM penjualan p LEFT JOIN customer c ON p.id_customer=c.id_customer ORDER BY p.tanggal DESC, p.id_penjualan DESC LIMIT 5");
// Stok rendah
$stok_rendah = mysqli_query($koneksi,"SELECT * FROM barang WHERE stok IS NOT NULL AND stok < 10 ORDER BY stok ASC LIMIT 5");

include '../layouts/header.php';
?>
<div class="page-content active">

  <!-- Greeting -->
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;padding:20px 24px;background:linear-gradient(135deg,#f0faf2,#d8f3de);border-radius:14px;border:1px solid var(--green-200);">
    <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,#4aba5e,#228234);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.3rem;flex-shrink:0;"><?= htmlspecialchars($user['inisial']) ?></div>
    <div>
      <div style="font-size:1rem;font-weight:800;color:#1a2130;">Halo, <?= htmlspecialchars($user['nama']) ?> — selamat datang kembali</div>
      <div style="font-size:.8rem;color:#5f6b7a;margin-top:2px;">Hari ini, <?= date('l, d F Y') ?> &nbsp;·&nbsp; <?= ucfirst($user['role']) ?></div>
    </div>
    <div style="margin-left:auto;text-align:right;">
      <div style="font-size:.72rem;color:var(--green-700);font-weight:600;">Transaksi Hari Ini</div>
      <div style="font-size:1.6rem;font-weight:800;color:var(--green-600);font-family:'Instrument Serif',serif;"><?= $trx_hari_ini ?></div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><div class="stat-change up">Bulan Ini</div></div>
      <div class="stat-value">Rp <?= number_format($omzet_bulan,0,',','.') ?></div>
      <div class="stat-label">Total Omzet</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;color:#d97706;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div><div class="stat-change down">Bulan Ini</div></div>
      <div class="stat-value">Rp <?= number_format($beli_bulan,0,',','.') ?></div>
      <div class="stat-label">Total Pembelian</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg></div></div>
      <div class="stat-value"><?= $total_produk ?></div>
      <div class="stat-label">Produk Aktif</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div></div>
      <div class="stat-value"><?= $total_cust ?></div>
      <div class="stat-label">Total Customer</div>
    </div>
  </div>

  <div class="home-grid" style="margin-top:16px;">
    <!-- Recent Transaksi -->
    <div class="card">
      <div class="card-header">
        <div><div class="card-title">Transaksi Terbaru</div><div class="card-subtitle">5 penjualan terakhir</div></div>
        <a href="../penjualan/index.php" class="btn btn-outline btn-sm" style="text-decoration:none;">Lihat Semua</a>
      </div>
      <?php if(mysqli_num_rows($recent_jual)>0): while($d=mysqli_fetch_assoc($recent_jual)): ?>
      <div class="activity-item">
        <div class="act-icon" style="background:var(--green-50);color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
        <div class="act-text">
          <b><?= htmlspecialchars($d['nama_customer']??'Customer') ?></b>
          <div style="color:var(--gray-400);font-size:.75rem;"><?= $d['id_penjualan'] ?> · <?= date('d M Y',strtotime($d['tanggal'])) ?></div>
        </div>
        <div style="font-weight:700;color:var(--green-600);font-size:.85rem;">Rp <?= number_format($d['sub_total'],0,',','.') ?></div>
      </div>
      <?php endwhile; else: ?>
      <div class="empty-state"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#aee5ba" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div><div class="empty-title">Belum ada transaksi</div></div>
      <?php endif; ?>
    </div>

    <!-- Quick Links + Stok -->
    <div style="display:flex;flex-direction:column;gap:16px;">
      <div class="card">
        <div class="card-header"><div><div class="card-title">Menu Cepat</div></div></div>
        <div class="quick-grid">
          <a href="../penjualan/tambah.php" class="qlink" style="text-decoration:none;"><div class="qlink-icon" style="background:var(--green-50);color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="17" height="17"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div><span class="qlink-text">Tambah Penjualan</span><span class="qlink-arr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span></a>
          <a href="../pembelian/tambah.php" class="qlink" style="text-decoration:none;"><div class="qlink-icon" style="background:#fffbeb;color:#d97706;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="17" height="17"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div><span class="qlink-text">Tambah Pembelian</span><span class="qlink-arr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span></a>
          <a href="../laporan/penjualan.php" class="qlink" style="text-decoration:none;"><div class="qlink-icon" style="background:var(--green-50);color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="17" height="17"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div><span class="qlink-text">Laporan Penjualan</span><span class="qlink-arr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span></a>
          <a href="../laporan/pembelian.php" class="qlink" style="text-decoration:none;"><div class="qlink-icon" style="background:#fffbeb;color:#d97706;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="17" height="17"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div><span class="qlink-text">Laporan Pembelian</span><span class="qlink-arr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span></a>
          <a href="../customer/tambah.php" class="qlink" style="text-decoration:none;"><div class="qlink-icon" style="background:var(--green-50);color:#228234;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="17" height="17"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><span class="qlink-text">Tambah Customer</span><span class="qlink-arr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span></a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../layouts/footer.php'; ?>
