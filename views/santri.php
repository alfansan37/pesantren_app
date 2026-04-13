<?php
// Proses tambah, edit, hapus
$message = ''; $type = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $stmt = $pdo->prepare("INSERT INTO santri (nama, nis, alamat, tanggal_masuk, nama_orang_tua, nomer_hp_ortu) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$_POST['nama'], $_POST['nis'], $_POST['alamat'], $_POST['tanggal_masuk'], $_POST['nama_orang_tua'], $_POST['nomer_hp_ortu']]);
        $message = "Santri berhasil ditambahkan"; $type = "success";
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE santri SET nama=?, nis=?, alamat=?, tanggal_masuk=?, nama_orang_tua=?, nomer_hp_ortu=? WHERE id=?");
        $stmt->execute([$_POST['nama'], $_POST['nis'], $_POST['alamat'], $_POST['tanggal_masuk'], $_POST['nama_orang_tua'], $_POST['nomer_hp_ortu'], $_POST['id']]);
        $message = "Santri berhasil diupdate"; $type = "success";
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM santri WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "Santri berhasil dihapus"; $type = "success";
    }
}
$santri = $pdo->query("SELECT * FROM santri ORDER BY id DESC")->fetchAll();
?>
<h1 class="mb-4">Manajemen Santri</h1>
<?php if($message): ?><div class="alert alert-<?= $type ?>"><?= $message ?></div><?php endif; ?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalSantri" onclick="resetForm()">+ Tambah Santri</button>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark"><tr><th>ID</th><th>NIS</th><th>Nama</th><th>Alamat</th><th>Tgl Masuk</th><th>Orang Tua</th><th>No HP Orang Tua</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach($santri as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td><td><?= htmlspecialchars($s['nis']) ?></td><td><?= htmlspecialchars($s['nama']) ?></td>
                <td><?= htmlspecialchars($s['alamat']) ?></td><td><?= $s['tanggal_masuk'] ?></td><td><?= htmlspecialchars($s['nama_orang_tua']) ?></td>
                <td><?= htmlspecialchars($s['nomer_hp_ortu']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalSantri" onclick="editSantri(<?= htmlspecialchars(json_encode($s)) ?>)">Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="setDelete(<?= $s['id'] ?>)">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalSantri"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><div class="modal-header"><h5 class="modal-title" id="modalTitle">Tambah Santri</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="create"><input type="hidden" name="id" id="id">
        <div class="mb-3"><label>NIS</label><input type="text" name="nis" id="nis" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" id="nama" class="form-control" required></div>
        <div class="mb-3"><label>Alamat</label><textarea name="alamat" id="alamat" class="form-control"></textarea></div>
        <div class="mb-3"><label>Tanggal Masuk</label><input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control"></div>
        <div class="mb-3"><label>Nama Orang Tua</label><input type="text" name="nama_orang_tua" id="nama_orang_tua" class="form-control"></div>
        <div class="mb-3"><label>No HP Orang Tua</label><input type="text" name="nomer_hp_ortu" id="nomer_hp_ortu" class="form-control"></div>
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
function resetForm() { document.getElementById('action').value='create'; document.getElementById('modalTitle').innerText='Tambah Santri'; document.getElementById('formSantri')?.reset(); document.getElementById('id').value=''; }
function editSantri(data) { document.getElementById('action').value='edit'; document.getElementById('modalTitle').innerText='Edit Santri'; document.getElementById('id').value=data.id; document.getElementById('nis').value=data.nis; document.getElementById('nama').value=data.nama; document.getElementById('alamat').value=data.alamat; document.getElementById('tanggal_masuk').value=data.tanggal_masuk; document.getElementById('nama_orang_tua').value=data.nama_orang_tua; document.getElementById('nomer_hp_ortu').value=data.nomer_hp_ortu; }
function setDelete(id) { document.getElementById('delete_id').value=id; }
</script>