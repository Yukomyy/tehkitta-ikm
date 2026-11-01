<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id_supplier   = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat        = $_POST['alamat'];
    $no_telepon    = $_POST['no_telepon'];
    $query = "INSERT INTO supplier (id_supplier, nama_supplier, alamat, no_telepon) 
              VALUES ('$id_supplier', '$nama_supplier', '$alamat', '$no_telepon')";

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

<div class="modal-overlay open" id="modal-supplier">
    <div class="modal">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                    <span>Tambah Supplier</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Supplier <span>*</span></label>
                        <input type="text" name="id_supplier" class="form-input" placeholder="SPL-001" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-input" placeholder="08xx-xxxx-xxxx">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Supplier <span>*</span></label>
                    <input type="text" name="nama_supplier" class="form-input" placeholder="Nama perusahaan / pemasok" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-input" placeholder="Alamat lengkap supplier">
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>