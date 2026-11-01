<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$search = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 10; $offset = ($page-1)*$limit;
$where  = $search ? "WHERE p.id_pembelian LIKE '%$search%' OR s.nama_supplier LIKE '%$search%'" : '';

$total_data = (int)mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as t FROM pembelian p LEFT JOIN supplier s ON p.id_supplier=s.id_supplier $where"))['t'];
$total_page = max(1, ceil($total_data/$limit));

$query = mysqli_query($koneksi,"
    SELECT p.*, s.nama_supplier, pt.nama_petugas, COUNT(dp.id_barang) as jumlah_item
    FROM pembelian p
    LEFT JOIN supplier s  ON p.id_supplier=s.id_supplier
    LEFT JOIN petugas  pt ON p.id_petugas=pt.id_petugas
    LEFT JOIN detail_pembelian dp ON p.id_pembelian=dp.id_pembelian
    $where GROUP BY p.id_pembelian
    ORDER BY p.tanggal DESC, p.id_pembelian DESC
    LIMIT $limit OFFSET $offset
");

include '../layouts/header.php';
?>
<div class="page-content active">
  <?php
  $stat = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total, SUM(sub_total) as pengeluaran FROM pembelian WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())"));
  ?>
  <div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div></div>
      <div class="stat-value"><?= number_format($stat['total']) ?></div>
      <div class="stat-label">Pembelian Bulan Ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#fffbeb;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
      <div class="stat-value" style="font-size:1.1rem;color:var(--warning)">Rp <?= number_format($stat['pengeluaran']??0,0,',','.') ?></div>
      <div class="stat-label">Total Pengeluaran</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div></div>
      <div class="stat-value"><?= mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(DISTINCT id_supplier) as t FROM pembelian WHERE MONTH(tanggal)=MONTH(CURDATE())"))['t'] ?></div>
      <div class="stat-label">Supplier Aktif</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div></div>
      <div class="stat-value">Rp <?= $stat['total']>0 ? number_format(($stat['pengeluaran']??0)/$stat['total'],0,',','.') : '0' ?></div>
      <div class="stat-label">Rata-rata Pembelian</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div><div class="card-title">Data Pembelian</div><div class="card-subtitle">Kelola transaksi pembelian barang</div></div>
      <div class="card-toolbar">
        <form method="GET" style="display:flex;gap:8px;">
          <div class="search-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari ID / supplier...">
          </div>
          <button type="submit" class="btn btn-outline btn-sm">Cari</button>
        </form>
        <a href="../laporan/pembelian.php" class="btn btn-outline btn-sm" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> Laporan</a>
        <a href="tambah.php" class="btn btn-primary btn-sm" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah</a>
      </div>
    </div>
    <div class="table-wrap">
      <table>
        <thead><tr><th>#</th><th>No. Pembelian</th><th>Tanggal</th><th>Supplier</th><th>Petugas</th><th>Item</th><th>Total</th><th style="text-align:right">Aksi</th></tr></thead>
        <tbody>
        <?php if(mysqli_num_rows($query)>0): $no=$offset+1;
          while($d=mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code style="background:#fffbeb;color:var(--warning);padding:2px 8px;border-radius:5px;font-size:.72rem;font-weight:700"><?= htmlspecialchars($d['id_pembelian']) ?></code></td>
            <td><?= date('d M Y',strtotime($d['tanggal'])) ?></td>
            <td><?= htmlspecialchars($d['nama_supplier']??'-') ?></td>
            <td><?= htmlspecialchars($d['nama_petugas']??'-') ?></td>
            <td><span style="background:#fffbeb;color:var(--warning);padding:2px 8px;border-radius:20px;font-size:.75rem;font-weight:600"><?= $d['jumlah_item'] ?> item</span></td>
            <td style="font-weight:700;color:var(--warning)">Rp <?= number_format($d['sub_total'],0,',','.') ?></td>
            <td style="text-align:right">
              <div class="row-actions" style="justify-content:flex-end;gap:4px;">
    <a href="detail.php?id=<?= $d['id_pembelian'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;">👁 Detail</a>
    <a href="ubah.php?id=<?= $d['id_pembelian'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
    <a href="../laporan/cetak_pembelian.php?id=<?= $d['id_pembelian'] ?>" target="_blank" class="btn btn-xs btn-outline" style="text-decoration:none;">🖨</a>
    <a href="hapus.php?id=<?= $d['id_pembelian'] ?>" class="btn btn-xs btn-danger-outline" style="text-decoration:none;" onclick="return confirm('Hapus pembelian <?= $d['id_pembelian'] ?>?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></a>
</div>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="8"><div class="empty-state"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div><div class="empty-title">Belum ada data pembelian</div></div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="table-foot" style="display:flex;justify-content:space-between;align-items:center;">
      <span>Menampilkan <?= mysqli_num_rows($query) ?> dari <?= $total_data ?> data</span>
      <?php if($total_page>1): ?>
      <div style="display:flex;gap:4px;">
        <?php for($i=1;$i<=$total_page;$i++): ?>
        <a href="?page=<?=$i?>&q=<?=urlencode($search)?>" class="btn btn-xs <?= $i==$page?'btn-primary':'btn-outline' ?>" style="text-decoration:none;"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include '../layouts/footer.php'; ?>
