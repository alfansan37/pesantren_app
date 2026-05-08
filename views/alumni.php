<?php
// Proses tambah, edit, hapus alumni
$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $stmt = $pdo->prepare("INSERT INTO alumni (nama, alamat, tahun_lulus, pekerjaan) VALUES (?,?,?,?)");
        $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['tahun_lulus'], $_POST['pekerjaan']]);
        $message = "Alumni berhasil ditambahkan";
        $type = "success";
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE alumni SET nama=?, alamat=?, tahun_lulus=?, pekerjaan=? WHERE id=?");
        $stmt->execute([$_POST['nama'], $_POST['alamat'], $_POST['tahun_lulus'], $_POST['pekerjaan'], $_POST['id']]);
        $message = "Alumni berhasil diupdate";
        $type = "success";
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM alumni WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "Alumni berhasil dihapus";
        $type = "success";
    }
}

// Ambil semua data alumni
$alumni = $pdo->query("SELECT * FROM alumni ORDER BY id DESC")->fetchAll();
?>

<h1 class="mb-4">Manajemen Alumni</h1>

<?php if ($message): ?>
    <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
        <?= $message ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalAlumni" onclick="resetForm()">
    <i class="fas fa-plus"></i> Tambah Alumni
</button>

<div class="table-responsive">
    <table id="tableAlumni" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Tahun Lulus</th>
                <th>Pekerjaan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumni as $a): ?>
                <tr>
                    <td><?= $a['id'] ?></td>
                    <td><?= htmlspecialchars($a['nama']) ?></td>
                    <td><?= htmlspecialchars($a['alamat']) ?></td>
                    <td><?= htmlspecialchars($a['tahun_lulus']) ?></td>
                    <td><?= htmlspecialchars($a['pekerjaan']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalAlumni" onclick='editAlumni(<?= json_encode($a) ?>)'>
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="setDelete(<?= $a['id'] ?>)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Tambah/Edit Alumni -->
<div class="modal fade" id="modalAlumni" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Alumni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="create">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" id="tahun_lulus" class="form-control" min="1900" max="2099">
                    </div>
                    <div class="mb-3">
                        <label>Pekerjaan</label>
                        <input type="text" name="pekerjaan" id="pekerjaan" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="delete_id">
                    <p>Yakin ingin menghapus data alumni ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('action').value = 'create';
    document.getElementById('modalTitle').innerText = 'Tambah Alumni';
    document.getElementById('id').value = '';
    document.getElementById('nama').value = '';
    document.getElementById('alamat').value = '';
    document.getElementById('tahun_lulus').value = '';
    document.getElementById('pekerjaan').value = '';
}

function editAlumni(data) {
    document.getElementById('action').value = 'edit';
    document.getElementById('modalTitle').innerText = 'Edit Alumni';
    document.getElementById('id').value = data.id;
    document.getElementById('nama').value = data.nama;
    document.getElementById('alamat').value = data.alamat;
    document.getElementById('tahun_lulus').value = data.tahun_lulus;
    document.getElementById('pekerjaan').value = data.pekerjaan;
}

function setDelete(id) {
    document.getElementById('delete_id').value = id;
}
</script>