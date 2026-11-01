<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if (!$id) { header('Location: ../penjualan/index.php'); exit; }

$penjualan = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT p.*, c.nama_customer, c.alamat, c.no_telepon, pt.nama_petugas
    FROM penjualan p
    LEFT JOIN customer c  ON p.id_customer = c.id_customer
    LEFT JOIN petugas  pt ON p.id_petugas  = pt.id_petugas
    WHERE p.id_penjualan = '$id'
"));
if (!$penjualan) { header('Location: ../penjualan/index.php'); exit; }

$details = mysqli_query($koneksi, "
    SELECT dp.*, pr.nama_produk, pr.harga
    FROM detail_penjualan dp
    LEFT JOIN produk pr ON dp.id_produk = pr.id_produk
    WHERE dp.id_penjualan = '$id'
    ORDER BY pr.nama_produk
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Transaksi Penjualan <?= htmlspecialchars($id) ?></title>
<style>
/* ── Reset ── */
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
html { font-size: 12px; }
body {
  font-family: Arial, Helvetica, sans-serif;
  background: #e8e8e8;
  padding: 24px 0 40px;
  color: #111;
}

/* ── Print button bar (hidden on print) ── */
.toolbar {
  max-width: 720px;
  margin: 0 auto 14px;
  display: flex;
  gap: 8px;
}
.btn {
  padding: 8px 18px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  border: none;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.btn-print { background: #2da042; color: #fff; }
.btn-back  { background: #fff; color: #333; border: 1.5px solid #ccc; }

/* ── Page / paper ── */
.page {
  max-width: 720px;
  margin: 0 auto;
  background: #fff;
  padding: 28px 36px 32px;
  box-shadow: 0 2px 12px rgba(0,0,0,.15);
}

/* ── KOP SURAT ── */
.kop {
  display: flex;
  align-items: center;
  gap: 16px;
  padding-bottom: 12px;
  border-bottom: 3px solid #111;
  margin-bottom: 14px;
}
.kop-logo {
  width: 64px;
  height: 64px;
  object-fit: contain;
  flex-shrink: 0;
}
.kop-text { line-height: 1.4; }
.kop-nama {
  font-size: 20px;
  font-weight: 900;
  color: #111;
  letter-spacing: .5px;
}
.kop-pt {
  font-size: 11px;
  font-weight: 700;
  color: #2da042;
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 2px;
}
.kop-alamat {
  font-size: 10px;
  color: #444;
  line-height: 1.5;
}

/* ── Judul ── */
.judul {
  text-align: center;
  font-size: 15px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin: 14px 0 16px;
  text-decoration: underline;
}

/* ── Info rows ── */
.info-section { margin-bottom: 6px; }
.info-row {
  display: flex;
  align-items: baseline;
  margin-bottom: 4px;
  font-size: 11.5px;
}
.info-label {
  width: 130px;
  font-weight: 600;
  flex-shrink: 0;
}
.info-colon {
  width: 14px;
  flex-shrink: 0;
}
.info-value {
  flex: 1;
  border-bottom: 1px solid #555;
  min-width: 180px;
  padding-bottom: 1px;
}
.info-right {
  display: flex;
  align-items: baseline;
  margin-left: 20px;
}
.info-right .info-label { width: 90px; }
.info-right .info-value { min-width: 160px; }

.info-pair {
  display: flex;
  gap: 0;
  margin-bottom: 4px;
}

.section-title {
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .5px;
  margin: 10px 0 6px;
}

/* ── Tabel detail ── */
.detail-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 0;
  font-size: 11.5px;
}
.detail-table th,
.detail-table td {
  border: 1px solid #111;
  padding: 6px 8px;
  vertical-align: middle;
}
.detail-table thead tr {
  background: #111;
  color: #fff;
}
.detail-table thead th {
  text-align: center;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .3px;
}
.detail-table tbody tr td { color: #111; }
.detail-table tbody tr:nth-child(even) td { background: #f9f9f9; }

.col-no     { width: 36px;  text-align: center; }
.col-produk { text-align: left; }
.col-qty    { width: 60px;  text-align: center; }
.col-harga  { width: 120px; text-align: right; }
.col-total  { width: 130px; text-align: right; }

/* Baris kosong pengisi */
.empty-row td { height: 22px; }

/* ── Subtotal row ── */
.subtotal-row td {
  border-top: 2px solid #111;
  font-weight: 700;
  font-size: 12px;
}
.subtotal-label {
  text-align: right;
  padding-right: 10px;
}
.subtotal-value {
  text-align: right;
  font-size: 12.5px;
}

/* ── Tanda tangan ── */
.ttd-section {
  display: flex;
  justify-content: flex-end;
  margin-top: 24px;
}
.ttd-box {
  width: 220px;
  border: 1px solid #111;
  text-align: center;
  padding: 8px 10px 0;
}
.ttd-title {
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: 2px;
}
.ttd-sub {
  font-size: 10px;
  color: #555;
  margin-bottom: 0;
}
.ttd-space { height: 54px; }
.ttd-line {
  border-top: 1px solid #333;
  margin: 0 10px 6px;
}
.ttd-nama {
  font-size: 10.5px;
  font-weight: 600;
  color: #333;
  padding-bottom: 6px;
}

/* ── Print ── */
@media print {
  body { background: #fff; padding: 0; }
  .toolbar { display: none !important; }
  .page { box-shadow: none; max-width: 100%; padding: 16px 24px; }
}
</style>
</head>
<body>

<!-- Toolbar -->
<div class="toolbar no-print">
  <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
  <a href="../penjualan/detail.php?id=<?= htmlspecialchars($id) ?>" class="btn btn-back">← Kembali</a>
</div>

<div class="page">

  <!-- KOP SURAT -->
  <div class="kop">
    <img src="../logo.png" alt="Logo" class="kop-logo"
         onerror="this.style.display='none'">
    <div class="kop-text">
      <div class="kop-nama">Teh Kitta</div>
      <div class="kop-pt">PT. RAFIKA MANDIRI</div>
      <div class="kop-alamat">
        Kp. Rawa Aren, Desa/Kel. Arenjaya, Kec. Bekasi Timur, Kota Bekasi, Jawa Barat<br>
        Email: talgnen@kanby.online
      </div>
    </div>
  </div>

  <!-- JUDUL -->
  <div class="judul">Transaksi Penjualan</div>

  <!-- NO PENJUALAN + TANGGAL -->
  <div class="info-section">
    <div class="info-pair">
      <div class="info-row" style="flex:1;">
        <span class="info-label">No. Penjualan</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($penjualan['id_penjualan']) ?></span>
      </div>
      <div class="info-row info-right" style="flex:1;">
        <span class="info-label">Tanggal</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= date('d/m/Y', strtotime($penjualan['tanggal'])) ?></span>
      </div>
    </div>
  </div>

  <!-- DATA CUSTOMER -->
  <div class="section-title">Data Customer</div>
  <div class="info-section">
    <div class="info-pair">
      <div class="info-row" style="flex:1;">
        <span class="info-label">Nama Customer</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($penjualan['nama_customer'] ?? '') ?></span>
      </div>
      <div class="info-row info-right" style="flex:1;">
        <span class="info-label">No. Telepon</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($penjualan['no_telepon'] ?? '') ?></span>
      </div>
    </div>
    <div class="info-row">
      <span class="info-label">Alamat</span>
      <span class="info-colon">:</span>
      <span class="info-value" style="max-width:500px;"><?= htmlspecialchars($penjualan['alamat'] ?? '') ?></span>
    </div>
  </div>

  <!-- DETAIL PESANAN -->
  <div class="section-title">Detail Pesanan</div>
  <table class="detail-table">
    <thead>
      <tr>
        <th class="col-no">NO.</th>
        <th class="col-produk">NAMA PRODUK</th>
        <th class="col-qty">QTY</th>
        <th class="col-harga">HARGA / PCS</th>
        <th class="col-total">HARGA TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no       = 1;
      $rows     = [];
      while ($d = mysqli_fetch_assoc($details)) $rows[] = $d;
      foreach ($rows as $d):
      ?>
      <tr>
        <td class="col-no"><?= $no++ ?></td>
        <td class="col-produk"><?= htmlspecialchars($d['nama_produk']) ?></td>
        <td class="col-qty"><?= $d['qty'] ?></td>
        <td class="col-harga">Rp <?= number_format($d['harga'], 0, ',', '.') ?></td>
        <td class="col-total">Rp <?= number_format($d['harga_total'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>

      <!-- SUB TOTAL -->
      <tr class="subtotal-row">
        <td colspan="4" class="subtotal-label">SUB TOTAL</td>
        <td class="col-total subtotal-value">
          Rp. <?= number_format($penjualan['sub_total'], 0, ',', '.') ?>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- TANDA TANGAN -->
  <div class="ttd-section">
    <div class="ttd-box">
      <div class="ttd-title">Petugas / Sales</div>
      <div class="ttd-sub">Tanda Tangan Penjual</div>
      <div class="ttd-space"></div>
      <div class="ttd-line"></div>
      <div class="ttd-nama"><?= htmlspecialchars($penjualan['nama_petugas'] ?? '') ?></div>
    </div>
  </div>

</div><!-- /page -->
</body>
</html>
