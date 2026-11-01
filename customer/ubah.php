<?php
require_once '../auth/middleware.php';
include '../koneksi.php';
if (isset($_POST['update'])) {
    $id_customer   = $_POST['id_customer']; // Primary Key
    $nama_customer = $_POST['nama_customer'];
    $alamat        = $_POST['alamat'];
    $no_telepon    = $_POST['no_telepon'];

    // Query untuk memperbarui data customer
    $query = "UPDATE customer SET 
                nama_customer = '$nama_customer',
                alamat = '$alamat',
                no_telepon = '$no_telepon'
              WHERE id_customer = '$id_customer'";

    $update = mysqli_query($koneksi, $query);

    // Cek apakah berhasil
    if ($update) {
        // Pindah ke index.php otomatis TANPA alert (localsays)
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
$query_data = mysqli_query($koneksi, "SELECT * FROM customer WHERE id_customer = '$id'");
$data_edit = mysqli_fetch_array($query_data);

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-customer">
    <div class="modal">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                    <span>Edit Customer</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ID Customer <span>*</span></label>
                        <input type="text" name="id_customer" class="form-input" value="<?= $data_edit['id_customer'] ?>" readonly style="background:var(--gray-50); color:var(--gray-500); cursor:not-allowed;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="no_telepon" class="form-input" value="<?= $data_edit['no_telepon'] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Customer <span>*</span></label>
                    <input type="text" name="nama_customer" class="form-input" value="<?= $data_edit['nama_customer'] ?>" required>
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