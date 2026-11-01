<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['simpan'])) {
    $id_petugas   = $_POST['id_petugas'];
    $nama_petugas = $_POST['nama_petugas'];

    $query = "INSERT INTO petugas (id_petugas, nama_petugas) 
              VALUES ('$id_petugas', '$nama_petugas')";

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

<div class="modal-overlay open" id="modal-petugas">
    <div class="modal" style="max-width: 400px;"> <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></div>
                    <span>Tambah Petugas</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Petugas <span>*</span></label>
                    <input type="text" name="id_petugas" class="form-input" placeholder="PTG-001" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Petugas <span>*</span></label>
                    <input type="text" name="nama_petugas" class="form-input" placeholder="Masukkan nama petugas" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>