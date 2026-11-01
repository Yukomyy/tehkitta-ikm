<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['update'])) {
    $id_supplier   = $_POST['id_supplier'];
    $nama_supplier = $_POST['nama_supplier'];
    $alamat        = $_POST['alamat'];
    $no_telepon    = $_POST['no_telepon'];

    $query = "UPDATE supplier SET 
                nama_supplier = '$nama_supplier',
                alamat = '$alamat',
                no_telepon = '$no_telepon'
              WHERE id_supplier = '$id_supplier'";

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}
if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

$id = $_GET['id'];
$query_data = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id_supplier = '$id'");
$data_edit = mysqli_fetch_array($query_data);

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-supplier">
    <div class="modal">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                    <span>Edit Supplier</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Supplier <span>*</span></label>
                        <input type="text" name="id_supplier" class="form-input" value="<?= $data_edit['id_supplier'] ?>" readonly style="background:var(--gray-50); color:var(--gray-500); cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-input" value="<?= $data_edit['no_telepon'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Supplier <span>*</span></label>
                    <input type="text" name="nama_supplier" class="form-input" value="<?= $data_edit['nama_supplier'] ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat</label>
                    <input type="text" name="alamat" class="form-input" value="<?= $data_edit['alamat'] ?>">
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="update" class="btn btn-primary btn-sm">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>