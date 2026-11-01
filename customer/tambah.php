<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id_customer   = $_POST['id_customer'];
    $nama_customer = $_POST['nama_customer'];
    $alamat        = $_POST['alamat'];
    $no_telepon    = $_POST['no_telepon'];
    $query = "INSERT INTO customer (id_customer, nama_customer, alamat, no_telepon) 
              VALUES ('$id_customer', '$nama_customer', '$alamat', '$no_telepon')";

    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        header("Location: index.php"); 
        exit;
    }
}

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-customer">
    <div class="modal">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                    <span>Tambah Customer</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Customer <span>*</span></label>
                        <input type="text" name="id_customer" class="form-input" placeholder="CST-001" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-input" placeholder="08xx-xxxx-xxxx">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Customer <span>*</span></label>
                    <input type="text" name="nama_customer" class="form-input" placeholder="Masukkan nama customer" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-input" placeholder="Alamat lengkap (opsional)">
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>