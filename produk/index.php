<?php
require_once '../auth/middleware.php'; 
include '../koneksi.php'; 

include '../layouts/header.php'; 
?>

<div class="page-content active">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Master Produk</div>
                <div class="card-subtitle">Menampilkan data produk yang akan dijual</div>
            </div>
            <div class="card-toolbar">
                <div class="search-field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input placeholder="Cari produk...">
                </div>
                <a href="tambah.php" class="btn btn-primary btn-sm" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah</a>
            </div>
        </div>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil data dari tabel produk
                    $query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY id_produk DESC");
                    $no = 1;

                    if(mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><code style="background:var(--green-50);color:var(--green-600);padding:2px 7px;border-radius:4px;font-size:0.72rem;font-weight:600"><?= $data['id_produk'] ?></code></td>
                        <td style="font-weight:500; color:var(--gray-800)"><?= $data['nama_produk'] ?></td>
                        <td style="font-weight:600;color:var(--gray-700)">Rp <?= number_format($data['harga'], 0, ',', '.') ?></td>
                        <td style="text-align:right">
                            <div class="row-actions" style="justify-content:flex-end">
                                <a href="ubah.php?id=<?= $data['id_produk'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit</a>
                                <a href="hapus.php?id=<?= $data['id_produk'] ?>" class="btn btn-xs btn-danger-outline" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg> </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                                <div class="empty-title">Belum ada data produk</div>
                                <div class="empty-sub">Klik tombol Tambah untuk memasukkan data produk baru</div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
        <div class="table-foot">
            <span>Menampilkan <?= mysqli_num_rows($query); ?> data</span>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>