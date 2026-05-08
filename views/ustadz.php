<?php
// Sama seperti santri.php, sesuaikan tabel dan field
$message = ''; $type = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $stmt = $pdo->prepare("INSERT INTO ustadz (nama, niu, alamat, spesialisasi, no_hp) VALUES (?,?,?,?,?)");
        $stmt->execute([$_POST['nama'], $_POST['niu'], $_POST['alamat'], $_POST['spesialisasi'], $_POST['no_hp']]);
        $message = "Ustadz berhasil ditambahkan"; $type = "success";
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE ustadz SET nama=?, niu=?, alamat=?, spesialisasi=?, no_hp=? WHERE id=?");
        $stmt->execute([$_POST['nama'], $_POST['niu'], $_POST['alamat'], $_POST['spesialisasi'], $_POST['no_hp'], $_POST['id']]);
        $message = "Ustadz berhasil diupdate"; $type = "success";
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM ustadz WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "Ustadz berhasil dihapus"; $type = "success";
    }
}
$data = $pdo->query("SELECT * FROM ustadz ORDER BY id DESC")->fetchAll();
?>
<h1 class="mb-4">Manajemen Ustadz</h1>
<?php if($message): ?><div class="alert alert-<?= $type ?>"><?= $message ?></div><?php endif; ?>
<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalUstadz" onclick="resetForm()">+ Tambah Ustadz</button>
<div class="table-responsive">
    <table id="tableUstadz" class="table table-bordered table-striped">
        <thead class="table-dark"><tr><th>ID</th><th>NIU</th><th>Nama</th><th>Alamat</th><th>Spesialisasi</th><th>No HP</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach($data as $d): ?>
            <tr>
                <td><?= $d['id'] ?></td><td><?= htmlspecialchars($d['niu']) ?></td><td><?= htmlspecialchars($d['nama']) ?></td>
                <td><?= htmlspecialchars($d['alamat']) ?></td><td><?= htmlspecialchars($d['spesialisasi']) ?></td><td><?= htmlspecialchars($d['no_hp']) ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalUstadz" onclick='editData(<?= json_encode($d) ?>)'>Edit</button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="setDelete(<?= $d['id'] ?>)">Hapus</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal form Ustadz (salin dari santri dengan field sesuai) -->
<div class="modal fade" id="modalUstadz" data-bs-backdrop="static"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><div class="modal-header"><h5 class="modal-title" id="modalTitle">Tambah Ustadz</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
        <input type="hidden" name="action" id="action" value="create"><input type="hidden" name="id" id="id">
        <div class="mb-3"><label>NIU</label><input type="text" name="niu" id="niu" class="form-control" required></div>
        <div class="mb-3"><label>Nama</label><input type="text" name="nama" id="nama" class="form-control" required></div>
        <div class="mb-3"><label>Alamat</label><textarea name="alamat" id="alamat" class="form-control"></textarea></div>
        <div class="mb-3"><label>Spesialisasi</label><input type="text" name="spesialisasi" id="spesialisasi" class="form-control"></div>
        <div class="mb-3"><label>No HP</label><input type="text" name="no_hp" id="no_hp" class="form-control"></div>
    </div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<!-- Modal Delete (sama seperti santri) -->
<div class="modal fade" id="modalDelete"><div class="modal-dialog"><div class="modal-content">
    <form method="POST"><div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" id="delete_id"><p>Yakin hapus?</p></div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-danger">Hapus</button></div>
    </form>
</div></div></div>

<script>
function resetForm() { 
    document.getElementById('action').value='create'; 
    document.getElementById('modalTitle').innerText='Tambah Ustadz'; 
    document.getElementById('id').value=''; 
    document.getElementById('niu').value=''; 
    document.getElementById('nama').value=''; 
    document.getElementById('alamat').value=''; 
    document.getElementById('spesialisasi').value=''; 
    document.getElementById('no_hp').value=''; 
}
function editData(d) { 
    document.getElementById('action').value='edit'; 
    document.getElementById('modalTitle').innerText='Edit Ustadz'; 
    document.getElementById('id').value=d.id; 
    document.getElementById('niu').value=d.niu; 
    document.getElementById('nama').value=d.nama; 
    document.getElementById('alamat').value=d.alamat; 
    document.getElementById('spesialisasi').value=d.spesialisasi; 
    document.getElementById('no_hp').value=d.no_hp; 
}
function setDelete(id) { document.getElementById('delete_id').value=id; }
</script>