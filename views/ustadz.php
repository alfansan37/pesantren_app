<?php
// Proses tambah, edit, hapus
$message = ''; $type = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $stmt = $pdo->prepare("INSERT INTO ustadz (nama, niu, alamat, spesialisasi, no_hp_ustadz) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['nama'], $_POST['niu'], $_POST['alamat'], $_POST['spesialisasi'], $_POST['no_hp_ustadz']]);
        $message = "ustadz berhasil ditambahkan"; $type = "success";
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE ustadz SET nama=?, niu=?, alamat=?, spesialisasi=?, no_hp_ustadz=? WHERE id=?");
        $stmt->execute([$_POST['nama'], $_POST['niu'], $_POST['alamat'], $_POST['spesialisasi'], $_POST['no_hp_ustadz'], $_POST['id']]);
        $message = "ustadz berhasil diupdate"; $type = "success";
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM ustadz WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "ustadz berhasil dihapus"; $type = "success";
    }
}
$ustadz = $pdo->query("SELECT * FROM ustadz ORDER BY id DESC")->fetchAll();
?>
<h1 class="mb-4">Manajemen Ustadz</h1>
<?php if($message): ?><div class="alert alert-<?= $type ?>"><?= $message ?></div><?php endif; ?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalustadz" onclick="resetForm()">+ Tambah ustadz</button>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark"><tr><th>ID</th><th>niu</th><th>Nama</th><th>Alamat</th><th>Spesialisasi</th><th>No HP</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach($ustadz as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['niu']) ?></td>
                <td><?= htmlspecialchars($s['nama']) ?></td>
                <td><?= htmlspecialchars($s['alamat']) ?></td>
                <td><?= htmlspecialchars($s['spesialisasi']) ?></td>
                <td><?= htmlspecialchars($s['no_hp_ustadz']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalustadz" onclick="editustadz(<?= htmlspecialchars(json_encode($s)) ?>)">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="setDelete(<?= $s['id'] ?>)">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalustadz"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><div class="modal-header"><h5 class="modal-title" id="modalTitle">Tambah ustadz</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="create"><input type="hidden" name="id" id="id">
        <div class="mb-3"><label>NIU</label><input type="text" name="niu" id="niu" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" id="nama" class="form-control" required></div>
        <div class="mb-3"><label>Alamat</label><textarea name="alamat" id="alamat" class="form-control"></textarea></div>
        <div class="mb-3"><label>spesialisasi</label><input type="text" name="spesialisasi" id="spesialisasi" class="form-control"></div>
        <div class="mb-3"><label>No. HP</label><input type="text" name="no_hp_ustadz" id="no_hp_ustadz" class="form-control"></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<!-- Modal Hapus -->
<div class="modal fade" id="modalDelete"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="delete_id"><p>Yakin hapus data ini?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Hapus</button></div>
    </form>
</div></div></div>

<script>
function resetForm() { document.getElementById('action').value='create'; document.getElementById('modalTitle').innerText='Tambah ustadz'; document.getElementById('formustadz')?.reset(); document.getElementById('id').value=''; }
function editustadz(data) { 
    document.getElementById('action').value='edit'; 
    document.getElementById('modalTitle').innerText='Edit ustadz'; 
    document.getElementById('id').value=data.id; 
    document.getElementById('niu').value=data.niu; 
    document.getElementById('nama').value=data.nama; 
    document.getElementById('alamat').value=data.alamat; 
    document.getElementById('spesialisasi').value=data.spesialisasi; 
    document.getElementById('no_hp_ustadz').value=data.no_hp_ustadz; }
function setDelete(id) { document.getElementById('delete_id').value=id; }
</script>