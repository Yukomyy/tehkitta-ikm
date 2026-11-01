<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';
$user = auth_user();

// ──────────────────────────────────────────────
// HANDLE POST — simpan header pembelian saja
// ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_supplier = trim($_POST['id_supplier'] ?? '');
    $id_petugas  = trim($_POST['id_petugas']  ?? '');
    $tanggal     = trim($_POST['tanggal']      ?? '');

    if (empty($id_supplier) || empty($id_petugas) || empty($tanggal)) {
        $error = 'Semua field wajib diisi.';
    } else {
        // Generate ID otomatis
        $last = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT id_pembelian FROM pembelian ORDER BY id_pembelian DESC LIMIT 1"
        ));
        $num          = $last ? (int)substr($last['id_pembelian'], 2) + 1 : 1;
        $id_pembelian = 'PB' . str_pad($num, 5, '0', STR_PAD_LEFT);

        $id_supplier = mysqli_real_escape_string($koneksi, $id_supplier);
        $id_petugas  = mysqli_real_escape_string($koneksi, $id_petugas);
        $tanggal     = mysqli_real_escape_string($koneksi, $tanggal);

        // Simpan ke tabel pembelian, sub_total default = 0
        $ok = mysqli_query($koneksi,
            "INSERT INTO pembelian (id_pembelian, id_supplier, id_petugas, tanggal, sub_total)
             VALUES ('$id_pembelian', '$id_supplier', '$id_petugas', '$tanggal', 0)"
        );

        if ($ok) {
            $_SESSION['toast'] = [
                'type' => 'success',
                'msg'  => "Pembelian $id_pembelian dibuat. Tambahkan item barang di bawah."
            ];
            header("Location: detail.php?id=$id_pembelian");
            exit;
        } else {
            $error = 'Gagal menyimpan: ' . mysqli_error($koneksi);
        }
    }
}

$suppliers = mysqli_query($koneksi, "SELECT * FROM supplier ORDER BY nama_supplier");
$petugass  = mysqli_query($koneksi, "SELECT * FROM petugas  ORDER BY nama_petugas");

include '../layouts/header.php';
?>

<div class="page-content active">
  <div class="card" style="max-width:640px;margin:0 auto;">

    <div class="card-header">
      <div>
        <div class="card-title">Buat Pembelian Baru</div>
        <div class="card-subtitle">Isi data pembelian, lalu tambahkan barang di halaman berikutnya</div>
      </div>
      <a href="index.php" class="btn btn-outline btn-sm" style="text-decoration:none;">← Kembali</a>
    </div>

    <?php if (!empty($error)): ?>
    <div style="margin:0 24px 16px;padding:12px 16px;background:var(--danger-light);border:1px solid #fecaca;border-radius:8px;color:var(--danger);font-size:.83rem;">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <!-- Flow indicator -->
    <div style="margin:16px 24px 0;padding:14px 20px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:10px;display:flex;align-items:center;gap:16px;">
      <div style="display:flex;align-items:center;gap:8px;">
        <div style="width:28px;height:28px;border-radius:50%;background:var(--warning);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;">1</div>
        <span style="font-size:.82rem;font-weight:700;color:#92400e;">Form Pembelian</span>
      </div>
      <div style="flex:1;height:2px;background:#fde68a;border-radius:2px;"></div>
      <div style="display:flex;align-items:center;gap:8px;opacity:.5;">
        <div style="width:28px;height:28px;border-radius:50%;background:#fde68a;color:#92400e;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;">2</div>
        <span style="font-size:.82rem;font-weight:700;color:#92400e;">Input Item Barang</span>
      </div>
    </div>

    <form method="POST" id="formPembelian">
      <div style="padding:20px 24px;display:flex;flex-direction:column;gap:16px;">

        <!-- ID Pembelian preview -->
        <div class="form-group">
          <label class="form-label">ID Pembelian</label>
          <input type="text" class="form-input" value="(Otomatis dibuat sistem)" disabled
                 style="background:var(--gray-50);color:var(--gray-400);cursor:not-allowed;">
          <div style="font-size:.72rem;color:var(--gray-400);margin-top:4px;">Format: PB00001, PB00002, dst.</div>
        </div>

        <!-- Supplier -->
        <div class="form-group">
          <label class="form-label">Supplier <span style="color:var(--danger);">*</span></label>
          <select name="id_supplier" class="form-select" required>
            <option value="">— Pilih Supplier —</option>
            <?php while ($s = mysqli_fetch_assoc($suppliers)): ?>
            <option value="<?= htmlspecialchars($s['id_supplier']) ?>"
              <?= isset($_POST['id_supplier']) && $_POST['id_supplier'] === $s['id_supplier'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['id_supplier']) ?> — <?= htmlspecialchars($s['nama_supplier']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Petugas -->
        <div class="form-group">
          <label class="form-label">Petugas <span style="color:var(--danger);">*</span></label>
          <select name="id_petugas" class="form-select" required>
            <option value="">— Pilih Petugas —</option>
            <?php while ($p = mysqli_fetch_assoc($petugass)): ?>
            <option value="<?= htmlspecialchars($p['id_petugas']) ?>"
              <?= isset($_POST['id_petugas']) && $_POST['id_petugas'] === $p['id_petugas'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['id_petugas']) ?> — <?= htmlspecialchars($p['nama_petugas']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <!-- Tanggal -->
        <div class="form-group">
          <label class="form-label">Tanggal Pembelian <span style="color:var(--danger);">*</span></label>
          <input type="date" name="tanggal" class="form-input"
                 value="<?= isset($_POST['tanggal']) ? htmlspecialchars($_POST['tanggal']) : date('Y-m-d') ?>" required>
        </div>

        <!-- Sub total info -->
        <div class="form-group">
          <label class="form-label">Sub Total</label>
          <input type="text" class="form-input" value="Rp 0 (akan dihitung otomatis dari item barang)" disabled
                 style="background:var(--gray-50);color:var(--gray-400);cursor:not-allowed;">
        </div>

        <!-- Info -->
        <div style="padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:8px;font-size:.8rem;color:#92400e;display:flex;align-items:flex-start;gap:10px;">
          <span style="flex-shrink:0;">💡</span>
          <span>Setelah menyimpan, Anda akan diarahkan ke halaman <strong>Detail Pembelian</strong> untuk menambahkan item barang beserta harga beli dan qty. Sub total akan dihitung otomatis.</span>
        </div>

      </div>

      <div style="padding:0 24px 24px;display:flex;justify-content:flex-end;gap:10px;">
        <a href="index.php" class="btn btn-outline" style="text-decoration:none;">Batal</a>
        <button type="submit" class="btn btn-primary">
          Simpan & Lanjut Input Barang →
        </button>
      </div>
    </form>

  </div>
</div>

<?php include '../layouts/footer.php'; ?>
