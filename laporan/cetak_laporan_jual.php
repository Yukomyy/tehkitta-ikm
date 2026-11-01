<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';
$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi,$tgl_awal);
$tb = mysqli_real_escape_string($koneksi,$tgl_akhir);
$where = "WHERE p.tanggal BETWEEN '$ta' AND '$tb'";
$summary = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) as total_trx, SUM(sub_total) as total_omzet FROM penjualan p $where"));
$rows = mysqli_query($koneksi,"SELECT p.*, c.nama_customer, pt.nama_petugas FROM penjualan p LEFT JOIN customer c ON p.id_customer=c.id_customer LEFT JOIN petugas pt ON p.id_petugas=pt.id_petugas $where ORDER BY p.tanggal ASC");
?><!DOCTYPE html>
<html lang="id"><head><meta charset="UTF-8"><title>Laporan Penjualan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}body{font-family:'Plus Jakarta Sans',sans-serif;background:#f0faf2;padding:20px;}
.wrap{max-width:800px;margin:0 auto;background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(0,0,0,.1);overflow:hidden;}
.rpt-hdr{background:linear-gradient(135deg,#1a6328,#2da042);color:#fff;padding:28px 36px;}
.rpt-title{font-size:1.2rem;font-weight:800;margin-bottom:4px;}
.rpt-period{font-size:.8rem;opacity:.8;}
.rpt-body{padding:24px 36px;}
.sum-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;}
.sum-box{background:#f0faf2;border-radius:10px;padding:14px 16px;text-align:center;}
.sum-val{font-size:1.1rem;font-weight:800;color:#2da042;font-family:'Plus Jakarta Sans',sans-serif;}
.sum-lbl{font-size:.72rem;color:#8b96a5;margin-top:3px;}
table{width:100%;border-collapse:collapse;margin-bottom:20px;}
th{font-size:.72rem;font-weight:700;color:#8b96a5;text-transform:uppercase;letter-spacing:.5px;padding:10px 12px;border-bottom:2px solid #f0faf2;text-align:left;}
th:last-child{text-align:right;}
td{padding:10px 12px;border-bottom:1px solid #f8fafc;font-size:.82rem;color:#2a3341;}
td:last-child{text-align:right;font-weight:700;color:#2da042;}
.tfoot-row td{font-weight:800;background:#f0faf2;font-size:.9rem;color:#1a6328;}
.footer-note{font-size:.72rem;color:#b8c0cc;text-align:center;border-top:1px solid #f0faf2;padding-top:16px;}
@media print{body{background:#fff;padding:0;}.wrap{box-shadow:none;border-radius:0;}.noprint{display:none!important;}}
.pb{display:flex;gap:10px;justify-content:center;margin-bottom:16px;}
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:8px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.83rem;font-weight:700;cursor:pointer;text-decoration:none;border:none;}
.bg{background:#2da042;color:#fff;}.bo{background:#fff;color:#2a3341;border:1.5px solid #d8dde3;}
</style></head><body>
<div class="noprint pb">
  <button onclick="window.print()" class="btn bg">🖨 Print Laporan</button>
  <a href="penjualan.php?tgl_awal=<?=urlencode($tgl_awal)?>&tgl_akhir=<?=urlencode($tgl_akhir)?>" class="btn bo">← Kembali</a>
</div>
<div class="wrap">
  <div class="rpt-hdr">
    <div class="rpt-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Laporan Penjualan — TehKitta</div>
    <div class="rpt-period">Periode: <?= date('d M Y',strtotime($tgl_awal)) ?> s/d <?= date('d M Y',strtotime($tgl_akhir)) ?> &nbsp;|&nbsp; Dicetak: <?= date('d M Y H:i') ?> WIB</div>
  </div>
  <div class="rpt-body">
    <div class="sum-row">
      <div class="sum-box"><div class="sum-val"><?= number_format($summary['total_trx']) ?></div><div class="sum-lbl">Total Transaksi</div></div>
      <div class="sum-box"><div class="sum-val">Rp <?= number_format($summary['total_omzet']??0,0,',','.') ?></div><div class="sum-lbl">Total Omzet</div></div>
      <div class="sum-box"><div class="sum-val">Rp <?= $summary['total_trx']>0?number_format(($summary['total_omzet']??0)/$summary['total_trx'],0,',','.'):0 ?></div><div class="sum-lbl">Rata-rata / Transaksi</div></div>
    </div>
    <table>
      <thead><tr><th>#</th><th>No. Transaksi</th><th>Tanggal</th><th>Customer</th><th>Petugas</th><th>Total</th></tr></thead>
      <tbody>
        <?php $no=1; $total=0; while($d=mysqli_fetch_assoc($rows)): $total+=$d['sub_total']; ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($d['id_penjualan']) ?></td>
          <td><?= date('d/m/Y',strtotime($d['tanggal'])) ?></td>
          <td><?= htmlspecialchars($d['nama_customer']??'-') ?></td>
          <td><?= htmlspecialchars($d['nama_petugas']??'-') ?></td>
          <td>Rp <?= number_format($d['sub_total'],0,',','.') ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
      <tfoot><tr class="tfoot-row"><td colspan="5" style="text-align:right;padding:12px;">TOTAL KESELURUHAN</td><td>Rp <?= number_format($total,0,',','.') ?></td></tr></tfoot>
    </table>
    <div class="footer-note">Laporan ini digenerate otomatis oleh Sistem TehKitta &nbsp;•&nbsp; <?= date('d F Y H:i:s') ?> WIB</div>
  </div>
</div>
</body></html>
