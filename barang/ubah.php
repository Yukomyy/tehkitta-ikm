<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

// LOGIKA UPDATE DATA
if (isset($_POST['update'])) {
    $id_barang    = $_POST['id_barang'];
    $nama_barang  = $_POST['nama_barang'];
    $satuan       = $_POST['satuan'];
    $harga_satuan = $_POST['harga_satuan'];

    $query = "UPDATE barang SET 
                nama_barang = '$nama_barang',
                satuan = '$satuan',
                harga_satuan = '$harga_satuan'
              WHERE id_barang = '$id_barang'";

    $update = mysqli_query($koneksi, $query);

    if ($update) {
        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');</script>";
    }
}

// AMBIL DATA LAMA
if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

$id = $_GET['id'];
$query_data = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang = '$id'");
$data_edit = mysqli_fetch_array($query_data);

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-barang">
    <div class="modal" style="max-width: 400px;">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
                    <span>Edit Barang</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Barang <span>*</span></label>
                    <input type="text" name="id_barang" class="form-input" value="<?= $data_edit['id_barang'] ?>" readonly style="background:var(--gray-50); color:var(--gray-500); cursor:not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Barang <span>*</span></label>
                    <input type="text" name="nama_barang" class="form-input" value="<?= $data_edit['nama_barang'] ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-input" value="<?= $data_edit['satuan'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Harga Satuan <span>*</span></label>
                    <input type="number" name="harga_satuan" class="form-input" value="<?= $data_edit['harga_satuan'] ?>" min="0" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="update" class="btn btn-primary btn-sm">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>