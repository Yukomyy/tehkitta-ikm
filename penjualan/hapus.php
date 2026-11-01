<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';
$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if ($id) {
    mysqli_query($koneksi,"DELETE FROM detail_penjualan WHERE id_penjualan='$id'");
    mysqli_query($koneksi,"DELETE FROM penjualan WHERE id_penjualan='$id'");
    $_SESSION['toast'] = ['type'=>'success','msg'=>"Transaksi $id berhasil dihapus."];
}
header('Location: index.php'); exit;
