<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id_produk   = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];

    $query = "INSERT INTO produk (id_produk, nama_produk, harga) 
              VALUES ('$id_produk', '$nama_produk', '$harga')";

    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
    }
}
include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-produk">
    <div class="modal" style="max-width: 400px;">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                    <span>Tambah Produk</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Produk <span>*</span></label>
                    <input type="text" name="id_produk" class="form-input" placeholder="PRD-001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Produk <span>*</span></label>
                    <input type="text" name="nama_produk" class="form-input" placeholder="Masukkan nama produk" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span>*</span></label>
                    <input type="number" name="harga" class="form-input" placeholder="0" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>