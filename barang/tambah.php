<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

// LOGIKA SIMPAN DATA
if (isset($_POST['simpan'])) {
    $id_barang    = $_POST['id_barang'];
    $nama_barang  = $_POST['nama_barang'];
    $satuan       = $_POST['satuan'];
    $harga_satuan = $_POST['harga_satuan'];

    $query = "INSERT INTO barang (id_barang, nama_barang, satuan, harga_satuan) 
              VALUES ('$id_barang', '$nama_barang', '$satuan', '$harga_satuan')";

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

<div class="modal-overlay open" id="modal-barang">
    <div class="modal" style="max-width: 400px;">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
                    <span>Tambah Barang</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Barang <span>*</span></label>
                    <input type="text" name="id_barang" class="form-input" placeholder="BRG-001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Barang <span>*</span></label>
                    <input type="text" name="nama_barang" class="form-input" placeholder="Masukkan nama barang" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-input" placeholder="Contoh: Pcs, Kg, Dus">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Satuan <span>*</span></label>
                    <input type="number" name="harga_satuan" class="form-input" placeholder="Contoh: 5000" min="0" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>