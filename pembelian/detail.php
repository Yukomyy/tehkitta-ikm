<?php
require_once '../auth/middleware.php';
require_once '../koneksi.php';

$id = mysqli_real_escape_string($koneksi, $_GET['id'] ?? '');
if (!$id) { header('Location: index.php'); exit; }

function recalcSubtotal($koneksi, $id_pembelian) {
    $r = mysqli_fetch_assoc(mysqli_query($koneksi,
        "SELECT COALESCE(SUM(harga_total), 0) AS total
         FROM detail_pembelian WHERE id_pembelian='$id_pembelian'"
    ));
    $total = $r['total'] ?? 0;
    mysqli_query($koneksi,
        "UPDATE pembelian SET sub_total='$total' WHERE id_pembelian='$id_pembelian'"
    );
    return $total;
}

// ── ACTION: Tambah item ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'tambah_item') {
    $id_barang = mysqli_real_escape_string($koneksi, $_POST['id_barang'] ?? '');
    $qty       = max(1, (int)($_POST['qty'] ?? 1));

    if ($id_barang) {
        // Ambil harga_satuan dari tabel barang
        $barang_row = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT harga_satuan FROM barang WHERE id_barang='$id_barang'"
        ));
        $harga_beli  = (float)($barang_row['harga_satuan'] ?? 0);
        $harga_total = $harga_beli * $qty;

        $existing = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT * FROM detail_pembelian
             WHERE id_pembelian='$id' AND id_barang='$id_barang'"
        ));

        if ($existing) {
            $new_qty   = (int)$existing['qty'] + $qty;
            $new_total = $harga_beli * $new_qty;
            mysqli_query($koneksi,
                "UPDATE detail_pembelian
                 SET qty=$new_qty, harga_total=$new_total
                 WHERE id_pembelian='$id' AND id_barang='$id_barang'"
            );
            $msg = "Qty barang diperbarui (+$qty).";
        } else {
            mysqli_query($koneksi,
                "INSERT INTO detail_pembelian (id_pembelian, id_barang, qty, harga_total)
                 VALUES ('$id', '$id_barang', $qty, $harga_total)"
            );
            $msg = "Barang berhasil ditambahkan.";
        }

        recalcSubtotal($koneksi, $id);
        $_SESSION['toast'] = ['type' => 'success', 'msg' => $msg];
    } else {
        $_SESSION['toast'] = ['type' => 'error', 'msg' => 'Pilih barang terlebih dahulu.'];
    }
    header("Location: detail.php?id=$id"); exit;
}

// ── ACTION: Edit item ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit_item') {
    $id_barang = mysqli_real_escape_string($koneksi, $_POST['id_barang'] ?? '');
    $qty       = max(1, (int)($_POST['qty'] ?? 1));

    if ($id_barang) {
        $barang_row = mysqli_fetch_assoc(mysqli_query($koneksi,
            "SELECT harga_satuan FROM barang WHERE id_barang='$id_barang'"
        ));
        $harga_beli  = (float)($barang_row['harga_satuan'] ?? 0);
        $harga_total = $harga_beli * $qty;

        mysqli_query($koneksi,
            "UPDATE detail_pembelian
             SET qty=$qty, harga_total=$harga_total
             WHERE id_pembelian='$id' AND id_barang='$id_barang'"
        );
        recalcSubtotal($koneksi, $id);
        $_SESSION['toast'] = ['type' => 'success', 'msg' => "Item berhasil diperbarui."];
    }
    header("Location: detail.php?id=$id"); exit;
}

// ── ACTION: Hapus item ──
if (isset($_GET['hapus'])) {
    $id_barang = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi,
        "DELETE FROM detail_pembelian
         WHERE id_pembelian='$id' AND id_barang='$id_barang'"
    );
    recalcSubtotal($koneksi, $id);
    $_SESSION['toast'] = ['type' => 'success', 'msg' => "Item berhasil dihapus."];
    header("Location: detail.php?id=$id"); exit;
}

// ── Ambil data header pembelian ──
$pembelian = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT p.*, s.nama_supplier, s.alamat, s.no_telepon, pt.nama_petugas
    FROM pembelian p
    LEFT JOIN supplier s  ON p.id_supplier = s.id_supplier
    LEFT JOIN petugas  pt ON p.id_petugas  = pt.id_petugas
    WHERE p.id_pembelian = '$id'
"));
if (!$pembelian) { header('Location: index.php'); exit; }

// Ambil detail items — harga_satuan langsung dari tabel barang
$details = mysqli_query($koneksi, "
    SELECT dp.*, b.nama_barang, b.satuan, b.harga_satuan
    FROM detail_pembelian dp
    LEFT JOIN barang b ON dp.id_barang = b.id_barang
    WHERE dp.id_pembelian = '$id'
    ORDER BY b.nama_barang
");

// Semua barang untuk dropdown modal — sertakan harga_satuan
$all_barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang");
$barang_arr = [];
while ($b = mysqli_fetch_assoc($all_barang)) $barang_arr[] = $b;

include '../layouts/header.php';
?>

<style>
.modal-overlay {
  display:none;position:fixed;inset:0;background:rgba(10,20,30,.45);
  backdrop-filter:blur(4px);z-index:500;align-items:center;justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:#fff;border-radius:16px;box-shadow:var(--shadow-xl);
  width:100%;max-width:440px;overflow:hidden;
  animation:modalIn .2s ease;
}
@keyframes modalIn {
  from { opacity:0;transform:scale(.94) translateY(12px); }
  to   { opacity:1;transform:scale(1)   translateY(0); }
}
.modal-head {
  padding:18px 22px 14px;border-bottom:1px solid var(--gray-100);
  display:flex;align-items:center;justify-content:space-between;
}
.modal-head h3 { font-size:.95rem;font-weight:800;color:var(--gray-800); }
.modal-close {
  width:28px;height:28px;border-radius:50%;border:none;background:var(--gray-100);
  cursor:pointer;font-size:.9rem;display:flex;align-items:center;justify-content:center;
  color:var(--gray-500);transition:all .15s;
}
.modal-close:hover { background:var(--danger-light);color:var(--danger); }
.modal-body { padding:20px 22px; }
.modal-foot {
  padding:14px 22px 18px;display:flex;gap:10px;justify-content:flex-end;
  border-top:1px solid var(--gray-100);
}
.info-bar {
  display:flex;flex-wrap:wrap;gap:10px;
  padding:12px 16px;background:var(--white);border:1.5px solid var(--gray-100);
  border-radius:10px;margin-bottom:16px;
}
.info-chip {
  display:flex;align-items:center;gap:6px;
  padding:4px 12px;border-radius:var(--radius-full);background:var(--gray-50);
  font-size:.78rem;font-weight:600;color:var(--gray-600);
}
.info-chip .label { font-weight:400;color:var(--gray-400); }
.btn-icon {
  width:30px;height:30px;border-radius:8px;border:1px solid var(--gray-200);
  background:var(--white);cursor:pointer;display:inline-flex;align-items:center;
  justify-content:center;font-size:.85rem;transition:all .15s;
}
.btn-icon:hover          { background:var(--gray-50); }
.btn-icon.danger:hover   { background:var(--danger-light);border-color:#fca5a5;color:var(--danger); }
.btn-icon.edit:hover     { background:#fffbeb;border-color:#fde68a;color:var(--warning); }
.total-bar {
  padding:16px 22px;
  background:linear-gradient(135deg,#d97706 0%,#b45309 100%);
  border-radius:12px;display:flex;align-items:center;justify-content:space-between;
  color:#fff;margin-top:16px;
}
.total-bar .label  { font-size:.8rem;font-weight:600;opacity:.85; }
.total-bar .amount { font-size:1.5rem;font-weight:800;font-family:'Instrument Serif',serif; }
.empty-products { padding:40px 20px;text-align:center; }
.empty-products .icon  { font-size:2.5rem;margin-bottom:10px; }
.empty-products .title { font-size:.9rem;font-weight:700;color:var(--gray-700);margin-bottom:6px; }
.empty-products .sub   { font-size:.8rem;color:var(--gray-400); }
.badge-warning {
  background:#fffbeb;color:var(--warning);
  padding:3px 10px;border-radius:var(--radius-full);font-size:.78rem;font-weight:700;
}
</style>

<div class="page-content active">

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
    <div>
      <div style="font-size:.72rem;color:var(--gray-400);margin-bottom:2px;">
        <a href="index.php" style="text-decoration:none;color:var(--gray-400);">Pembelian</a>
        <span style="margin:0 6px;">›</span>
        <span style="color:var(--warning);font-weight:600;"><?= htmlspecialchars($id) ?></span>
      </div>
      <h2 style="font-size:1.1rem;font-weight:800;color:var(--gray-800);">Detail Pembelian</h2>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
      <a href="../laporan/cetak_pembelian.php?id=<?= $id ?>" target="_blank"
         class="btn btn-outline btn-sm" style="text-decoration:none;">🖨 Cetak Nota</a>
      <a href="index.php" class="btn btn-outline btn-sm" style="text-decoration:none;">← Daftar</a>
    </div>
  </div>

  <div class="info-bar">
    <div class="info-chip"><span class="label">ID</span><?= htmlspecialchars($id) ?></div>
    <div class="info-chip"><span class="label">Tanggal</span><?= date('d M Y', strtotime($pembelian['tanggal'])) ?></div>
    <div class="info-chip"><span class="label">Supplier</span><?= htmlspecialchars($pembelian['nama_supplier'] ?? '-') ?></div>
    <div class="info-chip"><span class="label">Petugas</span><?= htmlspecialchars($pembelian['nama_petugas'] ?? '-') ?></div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:16px;align-items:start;">

    <!-- LEFT: Tabel item barang -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Item Barang</div>
          <div class="card-subtitle">Tambah, edit, atau hapus item dalam pembelian ini</div>
        </div>
        <button onclick="openModal('modalTambah')" class="btn btn-primary btn-sm">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Barang
        </button>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Barang</th>
              <th>Satuan</th>
              <th style="text-align:right">Harga Satuan</th>
              <th style="text-align:center;width:80px">Qty</th>
              <th style="text-align:right">Total</th>
              <th style="text-align:center;width:80px">Aksi</th>
            </tr>
          </thead>
          <tbody id="detailTbody">
          <?php
          $no        = 1;
          $total_row = 0;
          while ($d = mysqli_fetch_assoc($details)):
            $total_row++;
          ?>
            <tr id="tr-<?= htmlspecialchars($d['id_barang']) ?>">
              <td><?= $no++ ?></td>
              <td>
                <div style="font-weight:600;color:var(--gray-800);"><?= htmlspecialchars($d['nama_barang']) ?></div>
                <div style="font-size:.72rem;color:var(--gray-400);"><?= htmlspecialchars($d['id_barang']) ?></div>
              </td>
              <td>
                <span style="background:var(--gray-100);color:var(--gray-500);padding:2px 8px;border-radius:var(--radius-full);font-size:.75rem;">
                  <?= htmlspecialchars($d['satuan'] ?? '-') ?>
                </span>
              </td>
              <td style="text-align:right;color:var(--gray-600);">
                Rp <?= number_format($d['harga_satuan'], 0, ',', '.') ?>
              </td>
              <td style="text-align:center;">
                <span class="badge-warning"><?= $d['qty'] ?></span>
              </td>
              <td style="text-align:right;font-weight:700;color:var(--warning);">
                Rp <?= number_format($d['harga_total'], 0, ',', '.') ?>
              </td>
              <td style="text-align:center;">
                <button
                  class="btn-icon edit"
                  title="Edit item"
                  onclick="openEdit(
                    '<?= htmlspecialchars($d['id_barang']) ?>',
                    '<?= htmlspecialchars($d['nama_barang']) ?>',
                    '<?= htmlspecialchars($d['satuan'] ?? '') ?>',
                    <?= (int)$d['qty'] ?>,
                    <?= number_format($d['harga_satuan'], 2, '.', '') ?>
                  )"
                ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                <a href="detail.php?id=<?= $id ?>&hapus=<?= $d['id_barang'] ?>"
                   class="btn-icon danger"
                   title="Hapus item"
                   onclick="return confirm('Hapus item <?= htmlspecialchars(addslashes($d['nama_barang'])) ?>?')"
                ><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg></a>
              </td>
            </tr>
          <?php endwhile; ?>
          <?php if ($total_row === 0): ?>
            <tr id="emptyRow">
              <td colspan="7">
                <div class="empty-products">
                  <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></div>
                  <div class="title">Belum ada item barang</div>
                  <div class="sub">Klik "Tambah Barang" untuk menambahkan item</div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <div style="padding:0 20px 20px;">
        <div class="total-bar">
          <div class="label">Total Pembelian (<?= $total_row ?> item)</div>
          <div class="amount" id="grandTotal">
            Rp <?= number_format($pembelian['sub_total'], 0, ',', '.') ?>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: Info + Actions -->
    <div style="display:flex;flex-direction:column;gap:12px;">

      <div class="card" style="padding:18px 20px;">
        <div style="font-size:.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px;">Info Supplier</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">Nama</div>
            <div style="font-weight:700;color:var(--gray-800);"><?= htmlspecialchars($pembelian['nama_supplier'] ?? '-') ?></div>
          </div>
          <?php if (!empty($pembelian['alamat'])): ?>
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">Alamat</div>
            <div style="font-size:.83rem;color:var(--gray-600);"><?= htmlspecialchars($pembelian['alamat']) ?></div>
          </div>
          <?php endif; ?>
          <?php if (!empty($pembelian['no_telepon'])): ?>
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">Telepon</div>
            <div style="font-size:.83rem;"><?= htmlspecialchars($pembelian['no_telepon']) ?></div>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card" style="padding:18px 20px;">
        <div style="font-size:.72rem;font-weight:700;color:var(--gray-400);text-transform:uppercase;letter-spacing:.8px;margin-bottom:14px;">Info Pembelian</div>
        <div style="display:flex;flex-direction:column;gap:10px;">
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">No. Pembelian</div>
            <code style="font-size:.82rem;font-weight:700;color:var(--warning);background:#fffbeb;padding:2px 8px;border-radius:6px;"><?= $id ?></code>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">Tanggal</div>
            <div style="font-weight:600;"><?= date('d F Y', strtotime($pembelian['tanggal'])) ?></div>
          </div>
          <div>
            <div style="font-size:.7rem;color:var(--gray-400);margin-bottom:2px;">Petugas</div>
            <div style="font-weight:600;"><?= htmlspecialchars($pembelian['nama_petugas'] ?? '-') ?></div>
          </div>
        </div>
      </div>

      <div style="padding:16px 18px;background:linear-gradient(135deg,#d97706,#b45309);border-radius:12px;text-align:center;color:#fff;">
        <div style="font-size:.75rem;font-weight:600;opacity:.85;margin-bottom:6px;">Total Pembelian</div>
        <div style="font-size:1.4rem;font-weight:800;font-family:'Instrument Serif',serif;" id="sideTotal">
          Rp <?= number_format($pembelian['sub_total'], 0, ',', '.') ?>
        </div>
      </div>

      <a href="../laporan/cetak_pembelian.php?id=<?= $id ?>" target="_blank"
         class="btn btn-outline" style="text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;">
        🖨 Cetak Nota
      </a>
      <a href="hapus.php?id=<?= $id ?>"
         class="btn btn-outline" style="text-decoration:none;display:flex;align-items:center;justify-content:center;gap:8px;color:var(--danger);border-color:var(--danger);"
         onclick="return confirm('Hapus seluruh pembelian <?= $id ?>? Semua item akan ikut terhapus.')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg> Hapus Pembelian
      </a>

    </div>
  </div>
</div>

<!-- MODAL: TAMBAH ITEM -->
<div class="modal-overlay" id="modalTambah">
  <div class="modal-box">
    <div class="modal-head">
      <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Barang</h3>
      <button class="modal-close" onclick="closeModal('modalTambah')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="detail.php?id=<?= $id ?>">
      <input type="hidden" name="action" value="tambah_item">
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">

        <div class="form-group" style="margin:0;">
          <label class="form-label">Barang <span style="color:var(--danger);">*</span></label>
          <select name="id_barang" id="tambahBarangSel" class="form-select" required
                  onchange="updateTambahInfo(this)">
            <option value="">— Pilih Barang —</option>
            <?php foreach ($barang_arr as $b): ?>
            <option value="<?= htmlspecialchars($b['id_barang']) ?>"
                    data-harga="<?= (float)$b['harga_satuan'] ?>"
                    data-satuan="<?= htmlspecialchars($b['satuan'] ?? '') ?>"
                    data-nama="<?= htmlspecialchars($b['nama_barang']) ?>">
              <?= htmlspecialchars($b['nama_barang']) ?>
              (Rp <?= number_format($b['harga_satuan'], 0, ',', '.') ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group" style="margin:0;">
          <label class="form-label">Qty <span style="color:var(--danger);">*</span></label>
          <input type="number" name="qty" id="tambahQty" class="form-input"
                 min="1" value="1" required oninput="updateTambahPreview()">
        </div>

        <!-- Preview subtotal -->
        <div id="tambahPreview" style="display:none;padding:12px 14px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:8px;">
          <div style="font-size:.72rem;color:#92400e;font-weight:600;margin-bottom:4px;">Preview</div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.82rem;color:var(--gray-600);" id="previewDesc">-</span>
            <span style="font-size:.95rem;font-weight:800;color:var(--warning);" id="previewTotal">Rp 0</span>
          </div>
        </div>

      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalTambah')">Batal</button>
        <button type="submit" class="btn btn-primary">Tambahkan</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: EDIT ITEM -->
<div class="modal-overlay" id="modalEdit">
  <div class="modal-box">
    <div class="modal-head">
      <h3><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit Item</h3>
      <button class="modal-close" onclick="closeModal('modalEdit')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="15" height="15"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    </div>
    <form method="POST" action="detail.php?id=<?= $id ?>">
      <input type="hidden" name="action" value="edit_item">
      <input type="hidden" name="id_barang" id="editIdBarang">
      <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">

        <div>
          <div style="font-size:.72rem;color:var(--gray-400);margin-bottom:3px;">Barang</div>
          <div style="font-weight:700;color:var(--gray-800);" id="editNamaBarang"></div>
          <div style="font-size:.75rem;color:var(--gray-400);margin-top:2px;" id="editSatuan"></div>
          <div style="font-size:.78rem;color:var(--warning);font-weight:600;margin-top:4px;" id="editHargaInfo"></div>
        </div>

        <div class="form-group" style="margin:0;">
          <label class="form-label">Qty <span style="color:var(--danger);">*</span></label>
          <input type="number" name="qty" id="editQty" class="form-input"
                 min="1" required oninput="updateEditPreview()">
        </div>

        <!-- Preview -->
        <div id="editPreview" style="padding:12px 14px;background:#fffbeb;border:1.5px solid #fde68a;border-radius:8px;">
          <div style="font-size:.72rem;color:#92400e;font-weight:600;margin-bottom:4px;">Preview Total</div>
          <div style="display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:.82rem;color:var(--gray-600);" id="editPreviewDesc">-</span>
            <span style="font-size:.95rem;font-weight:800;color:var(--warning);" id="editPreviewTotal">Rp 0</span>
          </div>
        </div>

      </div>
      <div class="modal-foot">
        <button type="button" class="btn btn-outline" onclick="closeModal('modalEdit')">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id) {
  document.getElementById(id).classList.add('open');
  if (id === 'modalTambah') {
    document.getElementById('tambahBarangSel').value = '';
    document.getElementById('tambahQty').value       = 1;
    document.getElementById('tambahPreview').style.display = 'none';
  }
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}
document.querySelectorAll('.modal-overlay').forEach(function(el) {
  el.addEventListener('click', function(e) {
    if (e.target === el) el.classList.remove('open');
  });
});

function updateTambahInfo(sel) {
  updateTambahPreview();
}

function updateTambahPreview() {
  var selectEl = document.getElementById('tambahBarangSel');
  var opt      = selectEl.selectedOptions[0];
  var qty      = parseInt(document.getElementById('tambahQty').value) || 1;

  if (!opt || !opt.value) {
    document.getElementById('tambahPreview').style.display = 'none';
    return;
  }
  var harga = parseFloat(opt.dataset.harga) || 0;
  var nama  = opt.dataset.nama || opt.text.split('(')[0].trim();
  var total = harga * qty;

  document.getElementById('previewDesc').textContent  = nama + ' × ' + qty;
  document.getElementById('previewTotal').textContent = 'Rp ' + formatNum(total);
  document.getElementById('tambahPreview').style.display = 'block';
}

// editHarga disimpan sebagai variabel JS, tidak perlu input lagi
var _editHarga = 0;

function openEdit(idBarang, namaBarang, satuan, qty, harga) {
  _editHarga = harga;
  document.getElementById('editIdBarang').value         = idBarang;
  document.getElementById('editNamaBarang').textContent = namaBarang;
  document.getElementById('editSatuan').textContent     = satuan ? 'Satuan: ' + satuan : '';
  document.getElementById('editHargaInfo').textContent  = 'Harga satuan: Rp ' + formatNum(harga);
  document.getElementById('editQty').value              = qty;
  updateEditPreview();
  openModal('modalEdit');
}

function updateEditPreview() {
  var qty   = parseInt(document.getElementById('editQty').value) || 1;
  var total = _editHarga * qty;
  document.getElementById('editPreviewDesc').textContent  = 'Rp ' + formatNum(_editHarga) + ' × ' + qty;
  document.getElementById('editPreviewTotal').textContent = 'Rp ' + formatNum(total);
}

function formatNum(n) {
  return parseFloat(n).toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', function() {
  var pt = document.getElementById('pageTitle');
  var pb = document.getElementById('pageBreadcrumb');
  if (pt) pt.textContent = 'Detail Pembelian';
  if (pb) pb.textContent = 'Pembelian › <?= htmlspecialchars($id) ?>';
});
</script>

<?php include '../layouts/footer.php'; ?>