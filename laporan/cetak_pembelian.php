<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if (!$id) { header('Location: ../pembelian/index.php'); exit; }

$pembelian = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT p.*, s.nama_supplier, s.alamat, s.no_telepon, pt.nama_petugas
    FROM pembelian p
    LEFT JOIN supplier s  ON p.id_supplier = s.id_supplier
    LEFT JOIN petugas  pt ON p.id_petugas  = pt.id_petugas
    WHERE p.id_pembelian = '$id'
"));
if (!$pembelian) { header('Location: ../pembelian/index.php'); exit; }

$details = mysqli_query($koneksi, "
    SELECT dp.*, b.nama_barang, b.satuan,
           CASE WHEN dp.qty > 0 THEN dp.harga_total / dp.qty ELSE 0 END AS harga_satuan
    FROM detail_pembelian dp
    LEFT JOIN barang b ON dp.id_barang = b.id_barang
    WHERE dp.id_pembelian = '$id'
    ORDER BY b.nama_barang
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Form Pembelian <?= htmlspecialchars($id) ?></title>
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

/* ── Toolbar ── */
.toolbar {
  max-width: 760px;
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

/* ── Paper ── */
.page {
  max-width: 760px;
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
.info-label  { width: 130px; font-weight: 600; flex-shrink: 0; }
.info-colon  { width: 14px;  flex-shrink: 0; }
.info-value  { flex: 1; border-bottom: 1px solid #555; min-width: 180px; padding-bottom: 1px; }

.info-right            { display: flex; align-items: baseline; margin-left: 20px; }
.info-right .info-label { width: 90px; }
.info-right .info-value { min-width: 160px; }

.info-pair { display: flex; gap: 0; margin-bottom: 4px; }

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
  font-size: 11px;
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
  font-size: 10.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .3px;
}
.detail-table tbody tr:nth-child(even) td { background: #f9f9f9; }

.col-no     { width: 32px;  text-align: center; }
.col-barang { text-align: left; }
.col-satuan { width: 70px;  text-align: center; }
.col-qty    { width: 52px;  text-align: center; }
.col-harga  { width: 115px; text-align: right; }
.col-total  { width: 125px; text-align: right; }

.empty-row td { height: 22px; }

/* ── Subtotal ── */
.subtotal-row td {
  border-top: 2px solid #111;
  font-weight: 700;
  font-size: 12px;
}
.subtotal-label { text-align: right; padding-right: 10px; }
.subtotal-value { text-align: right; font-size: 12.5px; }

/* ── Dua tanda tangan bawah ── */
.ttd-section {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-top: 24px;
}
.ttd-box {
  flex: 1;
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
.ttd-sub  { font-size: 10px; color: #555; }
.ttd-space { height: 54px; }
.ttd-line  { border-top: 1px solid #333; margin: 0 10px 6px; }
.ttd-nama  { font-size: 10.5px; font-weight: 600; color: #333; padding-bottom: 6px; }

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
<div class="toolbar">
  <button onclick="window.print()" class="btn btn-print">🖨 Print</button>
  <a href="../pembelian/detail.php?id=<?= htmlspecialchars($id) ?>" class="btn btn-back">← Kembali</a>
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
  <div class="judul">Form Pembelian Bahan Baku</div>

  <!-- NO PEMBELIAN + TANGGAL -->
  <div class="info-section">
    <div class="info-pair">
      <div class="info-row" style="flex:1;">
        <span class="info-label">No. Pembelian</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($pembelian['id_pembelian']) ?></span>
      </div>
      <div class="info-row info-right" style="flex:1;">
        <span class="info-label">Tanggal</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= date('d/m/Y', strtotime($pembelian['tanggal'])) ?></span>
      </div>
    </div>
  </div>

  <!-- DATA SUPPLIER -->
  <div class="section-title">Data Supplier</div>
  <div class="info-section">
    <div class="info-pair">
      <div class="info-row" style="flex:1;">
        <span class="info-label">Nama Supplier</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($pembelian['nama_supplier'] ?? '') ?></span>
      </div>
      <div class="info-row info-right" style="flex:1;">
        <span class="info-label">No. Telepon</span>
        <span class="info-colon">:</span>
        <span class="info-value"><?= htmlspecialchars($pembelian['no_telepon'] ?? '') ?></span>
      </div>
    </div>
    <div class="info-row">
      <span class="info-label">Alamat</span>
      <span class="info-colon">:</span>
      <span class="info-value" style="max-width:530px;"><?= htmlspecialchars($pembelian['alamat'] ?? '') ?></span>
    </div>
  </div>

  <!-- DETAIL PEMBELIAN -->
  <div class="section-title">Detail Pembelian</div>
  <table class="detail-table">
    <thead>
      <tr>
        <th class="col-no">NO.</th>
        <th class="col-barang">NAMA BARANG</th>
        <th class="col-satuan">SATUAN</th>
        <th class="col-qty">QTY</th>
        <th class="col-harga">HARGA SATUAN</th>
        <th class="col-total">HARGA TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no   = 1;
      $rows = [];
      while ($d = mysqli_fetch_assoc($details)) $rows[] = $d;
      foreach ($rows as $d):
      ?>
      <tr>
        <td class="col-no"><?= $no++ ?></td>
        <td class="col-barang"><?= htmlspecialchars($d['nama_barang']) ?></td>
        <td class="col-satuan"><?= htmlspecialchars($d['satuan'] ?? '-') ?></td>
        <td class="col-qty"><?= $d['qty'] ?></td>
        <td class="col-harga">Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?></td>
        <td class="col-total">Rp <?= number_format($d['harga_total'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>

      <!-- SUB TOTAL -->
      <tr class="subtotal-row">
        <td colspan="5" class="subtotal-label">SUB TOTAL</td>
        <td class="col-total subtotal-value">
          Rp. <?= number_format($pembelian['sub_total'], 0, ',', '.') ?>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- DUA TANDA TANGAN -->
  <div class="ttd-section">

    <div class="ttd-box">
      <div class="ttd-title">Supplier</div>
      <div class="ttd-sub">Tanda Tangan Supplier</div>
      <div class="ttd-space"></div>
      <div class="ttd-line"></div>
      <div class="ttd-nama"><?= htmlspecialchars($pembelian['nama_supplier'] ?? '') ?></div>
    </div>

    <div class="ttd-box">
      <div class="ttd-title">Petugas</div>
      <div class="ttd-sub">Tanda Tangan Penerima</div>
      <div class="ttd-space"></div>
      <div class="ttd-line"></div>
      <div class="ttd-nama"><?= htmlspecialchars($pembelian['nama_petugas'] ?? '') ?></div>
    </div>

  </div>

</div><!-- /page -->
</body>
</html>
