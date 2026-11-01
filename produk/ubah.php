<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['update'])) {
    $id_produk   = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];

    $query = "UPDATE produk SET 
                nama_produk = '$nama_produk',
                harga = '$harga'
              WHERE id_produk = '$id_produk'";

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
$query_data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id'");
$data_edit = mysqli_fetch_array($query_data);

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-produk">
    <div class="modal" style="max-width: 400px;">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                    <span>Edit Produk</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Produk <span>*</span></label>
                    <input type="text" name="id_produk" class="form-input" value="<?= $data_edit['id_produk'] ?>" readonly style="background:var(--gray-50); color:var(--gray-500); cursor:not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Produk <span>*</span></label>
                    <input type="text" name="nama_produk" class="form-input" value="<?= $data_edit['nama_produk'] ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span>*</span></label>
                    <input type="number" name="harga" class="form-input" value="<?= $data_edit['harga'] ?>" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="update" class="btn btn-primary btn-sm">Perbarui Data</button>
            </div>
        </form>
    </div>
</div><?php
include '../koneksi.php';

if (isset($_POST['update'])) {
    $id_produk   = $_POST['id_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = $_POST['harga'];

    $query = "UPDATE produk SET 
                nama_produk = '$nama_produk',
                harga = '$harga'
              WHERE id_produk = '$id_produk'";

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
$query_data = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$id'");
$data_edit = mysqli_fetch_array($query_data);

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-produk">
    <div class="modal" style="max-width: 400px;">
        <form action="" method="POST">
            <div class="modal-header">
                <div class="modal-title">
                    <div class="modal-title-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                    <span>Edit Produk</span>
                </div>
                <a href="index.php" class="modal-x" style="text-decoration:none;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">ID Produk <span>*</span></label>
                    <input type="text" name="id_produk" class="form-input" value="<?= $data_edit['id_produk'] ?>" readonly style="background:var(--gray-50); color:var(--gray-500); cursor:not-allowed;">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Produk <span>*</span></label>
                    <input type="text" name="nama_produk" class="form-input" value="<?= $data_edit['nama_produk'] ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga (Rp) <span>*</span></label>
                    <input type="number" name="harga" class="form-input" value="<?= $data_edit['harga'] ?>" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <a href="index.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="update" class="btn btn-primary btn-sm">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>