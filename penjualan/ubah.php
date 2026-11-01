<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if (!$id) { header('Location: index.php'); exit; }

// ── Ambil data penjualan yang akan diedit ──
$penjualan = mysqli_fetch_assoc(mysqli_query($koneksi,
    "SELECT * FROM penjualan WHERE id_penjualan = '$id'"
));
if (!$penjualan) { header('Location: index.php'); exit; }

// ── HANDLE POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_customer = trim($_POST['id_customer'] ?? '');
    $id_petugas  = trim($_POST['id_petugas']  ?? '');
    $tanggal     = trim($_POST['tanggal']      ?? '');

    if (empty($id_customer) || empty($id_petugas) || empty($tanggal)) {
        $error = 'Semua field wajib diisi.';
    } else {
        $id_customer = mysqli_real_escape_string($koneksi, $id_customer);
        $id_petugas  = mysqli_real_escape_string($koneksi, $id_petugas);
        $tanggal     = mysqli_real_escape_string($koneksi, $tanggal);

        $ok = mysqli_query($koneksi,
            "UPDATE penjualan SET
                id_customer = '$id_customer',
                id_petugas  = '$id_petugas',
                tanggal     = '$tanggal'
             WHERE id_penjualan = '$id'"
        );

        if ($ok) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'msg'  => "Penjualan $id berhasil diperbarui."
            ];
            header("Location: detail.php?id=$id");
            exit;
        } else {
            $error = 'Gagal menyimpan: ' . mysqli_error($koneksi);
        }
    }
}

$customers = mysqli_query($koneksi, "SELECT * FROM customer ORDER BY nama_customer");
$petugass  = mysqli_query($koneksi, "SELECT * FROM petugas  ORDER BY nama_petugas");

include '../layouts/header.php';
?>

<div class="page-content active">
  <div class="card" style="max-width:640px;margin:0 auto;">

    <div class="card-header">
      <div>
        <div class="card-title">Edit Penjualan</div>
        <div class="card-subtitle">Ubah data header penjualan <?= htmlspecialchars($id) ?></div>
      </div>
      <a href="detail.php?id=<?= $id ?>" class="btn btn-outline btn-sm" style="text-decoration:none;">← Kembali</a>
    </div>

    <?php if (!empty($error)): ?>
    <div style="margin:0 24px 16px;padding:12px 16px;background:var(--danger-light);border:1px solid #fecaca;border-radius:8px;color:var(--danger);font-size:.83rem;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST">
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:16px;">

        <!-- ID Penjualan (readonly) -->
        <div class="form-group">
          <label class="form-label">ID Penjualan</label>
          <input type="text" class="form-input" value="<?= htmlspecialchars($id) ?>" disabled
                 style="background:var(--gray-50);color:var(--gray-500);cursor:not-allowed;font-weight:600;">
        </div>

        <!-- Customer -->
        <div class="form-group">
          <label class="form-label">Customer <span style="color:var(--danger);">*</span></label>
          <select name="id_customer" class="form-select" required>
            <option value="">— Pilih Customer —</option>
            <?php while ($c = mysqli_fetch_assoc($customers)):
              $sel = (($_POST['id_customer'] ?? $penjualan['id_customer']) === $c['id_customer']) ? 'selected' : '';
            ?>
            <option value="<?= htmlspecialchars($c['id_customer']) ?>" <?= $sel ?>>
              <?= htmlspecialchars($c['id_customer']) ?> — <?= htmlspecialchars($c['nama_customer']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Petugas -->
        <div class="form-group">
          <label class="form-label">Petugas <span style="color:var(--danger);">*</span></label>
          <select name="id_petugas" class="form-select" required>
            <option value="">— Pilih Petugas —</option>
            <?php while ($p = mysqli_fetch_assoc($petugass)):
              $sel = (($_POST['id_petugas'] ?? $penjualan['id_petugas']) === $p['id_petugas']) ? 'selected' : '';
            ?>
            <option value="<?= htmlspecialchars($p['id_petugas']) ?>" <?= $sel ?>>
              <?= htmlspecialchars($p['id_petugas']) ?> — <?= htmlspecialchars($p['nama_petugas']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Tanggal -->
        <div class="form-group">
          <label class="form-label">Tanggal Penjualan <span style="color:var(--danger);">*</span></label>
          <input type="date" name="tanggal" class="form-input"
                 value="<?= htmlspecialchars($_POST['tanggal'] ?? $penjualan['tanggal']) ?>" required>
        </div>

        <!-- Subtotal (readonly) -->
        <div class="form-group">
          <label class="form-label">Subtotal</label>
          <input type="text" class="form-input"
                 value="Rp <?= number_format($penjualan['sub_total'], 0, ',', '.') ?>" disabled
                 style="background:var(--gray-50);color:var(--gray-400);cursor:not-allowed;">
          <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">Dihitung otomatis dari item produk.</div>
        </div>

        <!-- Info -->
        <div style="padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;font-size:.8rem;color:#92400e;display:flex;align-items:flex-start;gap:10px;">
          <span style="flex-shrink:0;">💡</span>
          <span>Halaman ini hanya mengubah data header penjualan (customer, petugas, tanggal). Untuk mengubah item produk, gunakan halaman <strong>Detail Penjualan</strong>.</span>
        </div>

      </div>

      <div style="padding:0 24px 24px;display:flex;justify-content:flex-end;gap:10px;">
        <a href="detail.php?id=<?= $id ?>" class="btn btn-outline" style="text-decoration:none;">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>

  </div>
</div>

<?php include '../layouts/footer.php'; ?>