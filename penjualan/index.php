<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

// Search & Pagination
$search = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$page   = max(1, (int)($_GET['page'] ?? 1));
$limit  = 10;
$offset = ($page - 1) * $limit;

$where = $search ? "WHERE p.id_penjualan LIKE '%$search%' OR c.nama_customer LIKE '%$search%' OR pt.nama_petugas LIKE '%$search%'" : '';

$total_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan p LEFT JOIN customer c ON p.id_customer=c.id_customer LEFT JOIN petugas pt ON p.id_petugas=pt.id_petugas $where"));
$total_data = (int)$total_row['total'];
$total_page = max(1, ceil($total_data / $limit));

$query = mysqli_query($koneksi, "
    SELECT p.*, c.nama_customer, pt.nama_petugas,
           COUNT(dp.id_produk) as jumlah_item
    FROM penjualan p
    LEFT JOIN customer c  ON p.id_customer = c.id_customer
    LEFT JOIN petugas  pt ON p.id_petugas  = pt.id_petugas
    LEFT JOIN detail_penjualan dp ON p.id_penjualan = dp.id_penjualan
    $where
    GROUP BY p.id_penjualan
    ORDER BY p.tanggal DESC, p.id_penjualan DESC
    LIMIT $limit OFFSET $offset
");

// Toast dari session
if (isset($_GET['success'])) { $_SESSION['toast'] = ['type'=>'success','msg'=>urldecode($_GET['success'])]; header('Location: index.php'); exit; }

include '../layouts/header.php';
?>

<div class="page-content active">

  <!-- Stats row -->
  <?php
  $stat = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total, SUM(sub_total) as omzet FROM penjualan WHERE MONTH(tanggal)=MONTH(CURDATE()) AND YEAR(tanggal)=YEAR(CURDATE())"));
  $stat_today = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total FROM penjualan WHERE DATE(tanggal)=CURDATE()"));
  ?>
  <div class="stats-row" style="margin-bottom:20px;">
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div></div>
      <div class="stat-value"><?= number_format($stat['total']) ?></div>
      <div class="stat-label">Transaksi Bulan Ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
      <div class="stat-value" style="font-size:1.1rem;">Rp <?= number_format($stat['omzet'] ?? 0, 0, ',', '.') ?></div>
      <div class="stat-label">Omzet Bulan Ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
      <div class="stat-value"><?= $stat_today['total'] ?></div>
      <div class="stat-label">Transaksi Hari Ini</div>
    </div>
    <div class="stat-card">
      <div class="stat-top"><div class="stat-icon-wrap" style="background:#f0faf2;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div></div>
      <div class="stat-value">Rp <?= $stat['total']>0 ? number_format(($stat['omzet']??0)/$stat['total'],0,',','.') : '0' ?></div>
      <div class="stat-label">Rata-rata per Transaksi</div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div>
        <div class="card-title">Data Penjualan</div>
        <div class="card-subtitle">Kelola seluruh transaksi penjualan</div>
      </div>
      <div class="card-toolbar">
        <form method="GET" style="display:flex;gap:8px;align-items:center;">
          <div class="search-field">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari ID / customer...">
          </div>
          <button type="submit" class="btn btn-outline btn-sm">Cari</button>
        </form>
        <a href="../laporan/penjualan.php" class="btn btn-outline btn-sm" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Laporan</a>
        <a href="tambah.php" class="btn btn-primary btn-sm" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah</a>
      </div>
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>No. Transaksi</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Petugas</th>
            <th>Item</th>
            <th>Total</th>
            <th style="text-align:right">Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($query) > 0):
          $no = $offset + 1;
          while ($d = mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code style="background:var(--green-50);color:var(--green-600);padding:2px 8px;border-radius:5px;font-size:.72rem;font-weight:700"><?= htmlspecialchars($d['id_penjualan']) ?></code></td>
            <td><?= date('d M Y', strtotime($d['tanggal'])) ?></td>
            <td><?= htmlspecialchars($d['nama_customer'] ?? '-') ?></td>
            <td><?= htmlspecialchars($d['nama_petugas'] ?? '-') ?></td>
            <td><span style="background:var(--green-100);color:var(--green-700);padding:2px 8px;border-radius:20px;font-size:.75rem;font-weight:600"><?= $d['jumlah_item'] ?> item</span></td>
            <td style="font-weight:700;color:var(--green-600)">Rp <?= number_format($d['sub_total'],0,',','.') ?></td>
            <td style="text-align:right">
              <div class="row-actions" style="justify-content:flex-end;gap:4px;">
                <a href="detail.php?id=<?= $d['id_penjualan'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;" title="Detail">👁 Detail</a>
                <a href="ubah.php?id=<?= $d['id_penjualan'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
                <a href="../laporan/cetak_penjualan.php?id=<?= $d['id_penjualan'] ?>" target="_blank" class="btn btn-xs btn-outline" style="text-decoration:none;" title="Cetak">🖨</a>
                <a href="hapus.php?id=<?= $d['id_penjualan'] ?>" class="btn btn-xs btn-danger-outline" style="text-decoration:none;" title="Hapus" onclick="return confirm('Hapus transaksi <?= $d['id_penjualan'] ?>?')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></a>
                            
              </div>
            </td>
          </tr>
          <?php endwhile; else: ?>
          <tr><td colspan="8"><div class="empty-state"><div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div><div class="empty-title">Belum ada transaksi penjualan</div><div class="empty-sub">Klik tombol Tambah untuk membuat transaksi baru</div></div></td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-foot" style="display:flex;justify-content:space-between;align-items:center;">
      <span>Menampilkan <?= mysqli_num_rows($query) ?> dari <?= $total_data ?> data</span>
      <?php if ($total_page > 1): ?>
      <div style="display:flex;gap:4px;">
        <?php for ($i=1; $i<=$total_page; $i++): ?>
          <a href="?page=<?=$i?>&q=<?=urlencode($search)?>" style="text-decoration:none;" class="btn btn-xs <?= $i==$page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include '../layouts/footer.php'; ?>
