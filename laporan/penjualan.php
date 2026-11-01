<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$tgl_awal_esc  = mysqli_real_escape_string($koneksi, $tgl_awal);
$tgl_akhir_esc = mysqli_real_escape_string($koneksi, $tgl_akhir);

$where = "WHERE p.tanggal BETWEEN '$tgl_awal_esc' AND '$tgl_akhir_esc'";

$summary = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT COUNT(*) as total_trx, SUM(sub_total) as total_omzet, AVG(sub_total) as avg_trx
    FROM penjualan p $where
"));
$query = mysqli_query($koneksi,"
    SELECT p.*, c.nama_customer, pt.nama_petugas, COUNT(dp.id_produk) as jumlah_item
    FROM penjualan p
    LEFT JOIN customer c ON p.id_customer=c.id_customer
    LEFT JOIN petugas pt ON p.id_petugas=pt.id_petugas
    LEFT JOIN detail_penjualan dp ON p.id_penjualan=dp.id_penjualan
    $where GROUP BY p.id_penjualan ORDER BY p.tanggal DESC
");

// Top produk
$top_produk = mysqli_query($koneksi,"
    SELECT pr.nama_produk, SUM(dp.qty) as total_qty, SUM(dp.harga_total) as total_nilai
    FROM detail_penjualan dp
    JOIN produk pr ON dp.id_produk=pr.id_produk
    JOIN penjualan p ON dp.id_penjualan=p.id_penjualan
    $where GROUP BY dp.id_produk ORDER BY total_qty DESC LIMIT 5
");

include '../layouts/header.php';
?>
<div class="page-content active">

  <!-- Filter -->
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header" style="border-bottom:none;padding-bottom:0;">
      <div><div class="card-title">Laporan Penjualan</div><div class="card-subtitle">Filter berdasarkan rentang tanggal</div></div>
      <div style="display:flex;gap:8px;">
        <a href="cetak_laporan_jual.php?tgl_awal=<?= urlencode($tgl_awal) ?>&tgl_akhir=<?= urlencode($tgl_akhir) ?>" target="_blank" class="btn btn-outline btn-sm" style="text-decoration:none;">🖨 Cetak Laporan</a>
      </div>
    </div>
    <form method="GET" style="padding:16px 24px;display:flex;gap:12px;align-items:flex-end;">
      <div class="form-group" style="margin:0;">
        <label class="form-label">Dari Tanggal</label>
        <input type="date" name="tgl_awal" class="form-input" value="<?= htmlspecialchars($tgl_awal) ?>">
      </div>
      <div class="form-group" style="margin:0;">
        <label class="form-label">Sampai Tanggal</label>
        <input type="date" name="tgl_akhir" class="form-input" value="<?= htmlspecialchars($tgl_akhir) ?>">
      </div>
      <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Filter</button>
      <a href="penjualan.php" class="btn btn-outline" style="text-decoration:none;">Reset</a>
    </form>
  </div>

  <!-- Summary cards -->
  <div class="stats-row" style="margin-bottom:16px;">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:var(--green-50);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div></div>
      <div class="stat-value"><?= number_format($summary['total_trx']) ?></div>
      <div class="stat-label">Total Transaksi</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:var(--green-50);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
      <div class="stat-value" style="font-size:1.05rem;">Rp <?= number_format($summary['total_omzet']??0,0,',','.') ?></div>
      <div class="stat-label">Total Omzet</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:var(--green-50);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div></div>
      <div class="stat-value" style="font-size:1.05rem;">Rp <?= number_format($summary['avg_trx']??0,0,',','.') ?></div>
      <div class="stat-label">Rata-rata / Transaksi</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:var(--green-50);"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
      <div class="stat-value"><?= (new DateTime($tgl_awal))->diff(new DateTime($tgl_akhir))->days + 1 ?></div>
      <div class="stat-label">Hari Periode</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:16px;">
    <!-- Tabel transaksi -->
    <div class="card">
      <div class="card-header"><div><div class="card-title">Daftar Transaksi</div><div class="card-subtitle"><?= date('d M Y',strtotime($tgl_awal)) ?> s/d <?= date('d M Y',strtotime($tgl_akhir)) ?></div></div></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>No. Transaksi</th><th>Tanggal</th><th>Customer</th><th>Item</th><th style="text-align:right">Total</th><th></th></tr></thead>
          <tbody>
          <?php if(mysqli_num_rows($query)>0): $no=1; while($d=mysqli_fetch_assoc($query)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><code style="background:var(--green-50);color:var(--green-600);padding:2px 7px;border-radius:5px;font-size:.72rem;font-weight:700"><?= htmlspecialchars($d['id_penjualan']) ?></code></td>
              <td><?= date('d M Y',strtotime($d['tanggal'])) ?></td>
              <td><?= htmlspecialchars($d['nama_customer']??'-') ?></td>
              <td><span style="background:var(--green-100);color:var(--green-700);padding:2px 8px;border-radius:20px;font-size:.75rem;"><?= $d['jumlah_item'] ?> item</span></td>
              <td style="text-align:right;font-weight:700;color:var(--green-600)">Rp <?= number_format($d['sub_total'],0,',','.') ?></td>
              <td><a href="../penjualan/detail.php?id=<?= $d['id_penjualan'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;">Detail</a></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="7"><div class="empty-state"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div><div class="empty-title">Tidak ada data pada periode ini</div></div></td></tr>
          <?php endif; ?>
          </tbody>
          <?php if(mysqli_num_rows($query)>0): ?>
          <tfoot>
            <tr style="background:var(--green-50);">
              <td colspan="5" style="text-align:right;font-weight:700;padding:12px 14px;color:var(--green-700);">TOTAL</td>
              <td style="text-align:right;font-weight:800;color:var(--green-600);padding:12px 14px;">Rp <?= number_format($summary['total_omzet']??0,0,',','.') ?></td>
              <td></td>
            </tr>
          </tfoot>
          <?php endif; ?>
        </table>
      </div>
    </div>

    <!-- Top produk -->
    <div class="card">
      <div class="card-header"><div><div class="card-title">Top 5 Produk</div><div class="card-subtitle">Terlaris periode ini</div></div></div>
      <div style="padding:12px;">
        <?php $rank=1; while($p=mysqli_fetch_assoc($top_produk)): ?>
        <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;margin-bottom:4px;background:<?= $rank===1?'var(--green-50)':'#f8fafc' ?>;">
          <div style="width:26px;height:26px;border-radius:50%;background:<?= $rank===1?'var(--green-500)':'var(--gray-200)' ?>;color:<?= $rank===1?'#fff':'var(--gray-500)' ?>;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0;"><?= $rank++ ?></div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:.82rem;font-weight:600;color:var(--gray-800);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($p['nama_produk']) ?></div>
            <div style="font-size:.72rem;color:var(--gray-400);"><?= number_format($p['total_qty']) ?> terjual</div>
          </div>
          <div style="font-size:.8rem;font-weight:700;color:var(--green-600);white-space:nowrap;">Rp <?= number_format($p['total_nilai'],0,',','.') ?></div>
        </div>
        <?php endwhile; ?>
        <?php if($rank===1): ?><div class="empty-state" style="padding:24px;"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div><div class="empty-title">Belum ada data</div></div><?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include '../layouts/footer.php'; ?>
