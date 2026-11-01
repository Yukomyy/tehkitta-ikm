<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi,$tgl_awal);
$tb = mysqli_real_escape_string($koneksi,$tgl_akhir);
$where = "WHERE p.tanggal BETWEEN '$ta' AND '$tb'";

$summary = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total_trx, SUM(sub_total) as total_beli, AVG(sub_total) as avg_trx FROM pembelian p $where"));
$query = mysqli_query($koneksi,"
    SELECT p.*, s.nama_supplier, pt.nama_petugas, COUNT(dp.id_barang) as jumlah_item
    FROM pembelian p
    LEFT JOIN supplier s ON p.id_supplier=s.id_supplier
    LEFT JOIN petugas pt ON p.id_petugas=pt.id_petugas
    LEFT JOIN detail_pembelian dp ON p.id_pembelian=dp.id_pembelian
    $where GROUP BY p.id_pembelian ORDER BY p.tanggal DESC
");

// Rekap per supplier
$rekap_supp = mysqli_query($koneksi,"
    SELECT s.nama_supplier, COUNT(p.id_pembelian) as total_order, SUM(p.sub_total) as total_nilai
    FROM pembelian p
    JOIN supplier s ON p.id_supplier=s.id_supplier
    $where GROUP BY p.id_supplier ORDER BY total_nilai DESC LIMIT 5
");

include '../layouts/header.php';
?>
<div class="page-content active">
  <div class="card" style="margin-bottom:16px;">
    <div class="card-header" style="border-bottom:none;padding-bottom:0;">
      <div><div class="card-title">Laporan Pembelian</div><div class="card-subtitle">Filter berdasarkan rentang tanggal</div></div>
      <a href="cetak_laporan_beli.php?tgl_awal=<?= urlencode($tgl_awal) ?>&tgl_akhir=<?= urlencode($tgl_akhir) ?>" target="_blank" class="btn btn-outline btn-sm" style="text-decoration:none;">🖨 Cetak Laporan</a>
    </div>
    <form method="GET" style="padding:16px 24px;display:flex;gap:12px;align-items:flex-end;">
      <div class="form-group" style="margin:0;"><label class="form-label">Dari Tanggal</label><input type="date" name="tgl_awal" class="form-input" value="<?= htmlspecialchars($tgl_awal) ?>"></div>
      <div class="form-group" style="margin:0;"><label class="form-label">Sampai Tanggal</label><input type="date" name="tgl_akhir" class="form-input" value="<?= htmlspecialchars($tgl_akhir) ?>"></div>
      <button type="submit" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg> Filter</button>
      <a href="pembelian.php" class="btn btn-outline" style="text-decoration:none;">Reset</a>
    </form>
  </div>

  <div class="stats-row" style="margin-bottom:16px;">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div></div>
      <div class="stat-value"><?= number_format($summary['total_trx']) ?></div>
      <div class="stat-label">Total Pembelian</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
      <div class="stat-value" style="font-size:1.05rem;color:var(--warning);">Rp <?= number_format($summary['total_beli']??0,0,',','.') ?></div>
      <div class="stat-label">Total Pengeluaran</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div></div>
      <div class="stat-value" style="font-size:1.05rem;">Rp <?= number_format($summary['avg_trx']??0,0,',','.') ?></div>
      <div class="stat-label">Rata-rata / Pembelian</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div></div>
      <div class="stat-value"><?= mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(DISTINCT id_supplier) as t FROM pembelian p $where"))['t'] ?></div>
      <div class="stat-label">Jumlah Supplier</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:16px;">
    <div class="card">
      <div class="card-header"><div><div class="card-title">Daftar Pembelian</div><div class="card-subtitle"><?= date('d M Y',strtotime($tgl_awal)) ?> s/d <?= date('d M Y',strtotime($tgl_akhir)) ?></div></div></div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>No. Pembelian</th><th>Tanggal</th><th>Supplier</th><th>Item</th><th style="text-align:right">Total</th><th></th></tr></thead>
          <tbody>
          <?php if(mysqli_num_rows($query)>0): $no=1; while($d=mysqli_fetch_assoc($query)): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><code style="background:#fffbeb;color:var(--warning);padding:2px 7px;border-radius:5px;font-size:.72rem;font-weight:700"><?= htmlspecialchars($d['id_pembelian']) ?></code></td>
              <td><?= date('d M Y',strtotime($d['tanggal'])) ?></td>
              <td><?= htmlspecialchars($d['nama_supplier']??'-') ?></td>
              <td><span style="background:#fffbeb;color:var(--warning);padding:2px 8px;border-radius:20px;font-size:.75rem;"><?= $d['jumlah_item'] ?> item</span></td>
              <td style="text-align:right;font-weight:700;color:var(--warning)">Rp <?= number_format($d['sub_total'],0,',','.') ?></td>
              <td><a href="../pembelian/detail.php?id=<?= $d['id_pembelian'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;">Detail</a></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="7"><div class="empty-state"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div><div class="empty-title">Tidak ada data pada periode ini</div></div></td></tr>
          <?php endif; ?>
          </tbody>
          <?php if(mysqli_num_rows($query)>0): ?>
          <tfoot><tr style="background:#fffbeb;"><td colspan="5" style="text-align:right;font-weight:700;padding:12px 14px;color:var(--warning);">TOTAL</td><td style="text-align:right;font-weight:800;color:var(--warning);padding:12px 14px;">Rp <?= number_format($summary['total_beli']??0,0,',','.') ?></td><td></td></tr></tfoot>
          <?php endif; ?>
        </table>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div><div class="card-title">Rekap Supplier</div><div class="card-subtitle">Top 5 periode ini</div></div></div>
      <div style="padding:12px;">
        <?php $rank=1; while($s=mysqli_fetch_assoc($rekap_supp)): ?>
        <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;margin-bottom:4px;background:<?= $rank===1?'#fffbeb':'#f8fafc' ?>;">
          <div style="width:26px;height:26px;border-radius:50%;background:<?= $rank===1?'var(--warning)':'var(--gray-200)' ?>;color:<?= $rank===1?'#fff':'var(--gray-500)' ?>;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;flex-shrink:0;"><?= $rank++ ?></div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:.82rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($s['nama_supplier']) ?></div>
            <div style="font-size:.72rem;color:var(--gray-400);"><?= $s['total_order'] ?> order</div>
          </div>
          <div style="font-size:.8rem;font-weight:700;color:var(--warning);white-space:nowrap;">Rp <?= number_format($s['total_nilai'],0,',','.') ?></div>
        </div>
        <?php endwhile; ?>
        <?php if($rank===1): ?><div class="empty-state" style="padding:24px;"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><rect x="1" y="3" width="40" height="40"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div><div class="empty-title">Belum ada data</div></div><?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include '../layouts/footer.php'; ?>
