<?php
require_once '../auth/middleware.php'; 
include '../koneksi.php'; 

include '../layouts/header.php'; 
?>

<div class="page-content active">
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Master Barang</div>
                <div class="card-subtitle">Menampilkan data barang (persediaan/bahan baku)</div>
            </div>
            <div class="card-toolbar">
                <div class="search-field">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input placeholder="Cari barang...">
                </div>
                <a href="tambah.php" class="btn btn-primary btn-sm" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah</a>
            </div>
        </div>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Satuan</th>
                        <th style="text-align:right">Harga Satuan</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY id_barang DESC");
                    $no = 1;

                    if(mysqli_num_rows($query) > 0) {
                        while ($data = mysqli_fetch_array($query)) {
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><code style="background:var(--green-50);color:var(--green-600);padding:2px 7px;border-radius:4px;font-size:0.72rem;font-weight:600"><?= $data['id_barang'] ?></code></td>
                        <td style="font-weight:500; color:var(--gray-800)"><?= $data['nama_barang'] ?></td>
                        <td>
                            <span class="badge" style="background:var(--gray-100); color:var(--gray-700);"><?= $data['satuan'] ?></span>
                        </td>
                        <td style="text-align:right; font-weight:500; color:var(--gray-800)">
                            Rp <?= number_format($data['harga_satuan'], 0, ',', '.') ?>
                        </td>
                        <td style="text-align:right">
                            <div class="row-actions" style="justify-content:flex-end">
                                <a href="ubah.php?id=<?= $data['id_barang'] ?>" class="btn btn-xs btn-outline" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit</a>
                                <a href="hapus.php?id=<?= $data['id_barang'] ?>" class="btn btn-xs btn-danger-outline" style="text-decoration:none;"> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg> </a>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        } 
                    } else { 
                    ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-emoji"><svg viewBox="0 0 24 24" fill="none" stroke="#cbd5d0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" width="40" height="40"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></div>
                                <div class="empty-title">Belum ada data barang</div>
                                <div class="empty-sub">Klik tombol Tambah untuk memasukkan data barang baru</div>
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