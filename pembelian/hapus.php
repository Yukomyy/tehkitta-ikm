<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';
$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if ($id) {
    // Kembalikan stok
    $details = mysqli_query($koneksi,"SELECT id_barang,qty FROM detail_pembelian WHERE id_pembelian='$id'");
    while ($d = mysqli_fetch_assoc($details)) {
        $idb = mysqli_real_escape_string($koneksi,$d['id_barang']);
        $qty = (int)$d['qty'];
        mysqli_query($koneksi,"UPDATE barang SET stok=GREATEST(COALESCE(stok,0)-$qty,0) WHERE id_barang='$idb'");
    }
    mysqli_query($koneksi,"DELETE FROM detail_pembelian WHERE id_pembelian='$id'");
    mysqli_query($koneksi,"DELETE FROM pembelian WHERE id_pembelian='$id'");
    $_SESSION['toast'] = ['type'=>'success','msg'=>"Pembelian $id dihapus & stok disesuaikan."];
}
header('Location: index.php'); exit;
