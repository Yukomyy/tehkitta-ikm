<?php
require_once '../auth/middleware.php';
include '../koneksi.php';

if (isset($_POST['hapus'])) {
    $id_supplier = $_POST['id_supplier'];

    $cek_beli = mysqli_query($koneksi, "SELECT * FROM pembelian WHERE id_supplier = '$id_supplier'");
    
    if (mysqli_num_rows($cek_beli) > 0) {
        echo "<script>
                alert('TIDAK BISA DIHAPUS! Supplier ini masih terikat dengan data transaksi pembelian.');
                window.location.href = 'index.php';
              </script>";
        exit;
    } else {
        $query = "DELETE FROM supplier WHERE id_supplier = '$id_supplier'";
        $hapus = mysqli_query($koneksi, $query);

        if ($hapus) {
            echo "<script>
                    alert('Data Supplier Berhasil Dihapus!');
                    window.location.href = 'index.php';
                  </script>";
            exit;
        } else {
            echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}

if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
$id = $_GET['id'];

include 'index.php'; 
?>

<div class="modal-overlay open" id="modal-del">
    <div class="modal" style="max-width:380px">
        <form action="" method="POST">
            <input type="hidden" name="id_supplier" value="<?= $id ?>">
            
            <div class="modal-body" style="padding:32px 28px;text-align:center;gap:12px">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--danger-light);display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin:0 auto"> <svg viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg> </div>
                <div style="font-size:1rem;font-weight:700;color:var(--gray-800)">Hapus Supplier?</div>
                <div style="font-size:0.82rem;color:var(--gray-500);line-height:1.6">Tindakan ini tidak dapat dibatalkan.<br>Data yang dihapus tidak bisa dikembalikan.</div>
                
                <div style="background:var(--warning-light);border:1px solid #fde68a;border-radius:var(--radius-md);padding:8px 12px;font-size:0.77rem;color:var(--warning)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> Jika data memiliki relasi ke tabel pembelian, penghapusan otomatis ditolak sistem.
                </div>
            </div>
            
            <div class="modal-footer" style="justify-content:center;gap:10px">
                <a href="index.php" class="btn btn-outline btn-sm" style="text-decoration:none;">Batal</a>
                <button type="submit" name="hapus" class="btn btn-danger-outline btn-sm">Hapus Sekarang</button>
            </div>
        </form>
    </div>
</div>