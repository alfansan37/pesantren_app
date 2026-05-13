<?php
// Proses tambah, edit, hapus, lulus, keluar
$message = ''; $type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'create') {
        $stmt = $pdo->prepare("INSERT INTO santri (nama, nis, alamat, provinsi, kabupaten, kecamatan, tanggal_masuk, nama_orang_tua, nomer_hp_ortu) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $_POST['nama'], 
            $_POST['nis'], 
            $_POST['alamat'], 
            $_POST['provinsi'], 
            $_POST['kabupaten'], 
            $_POST['kecamatan'], 
            $_POST['tanggal_masuk'], 
            $_POST['nama_orang_tua'], 
            $_POST['nomer_hp_ortu']
        ]);
        $message = "Santri berhasil ditambahkan"; $type = "success";
    } elseif ($_POST['action'] == 'edit') {
        $stmt = $pdo->prepare("UPDATE santri SET nama=?, nis=?, alamat=?, provinsi=?, kabupaten=?, kecamatan=?, tanggal_masuk=?, nama_orang_tua=?, nomer_hp_ortu=? WHERE id=?");
        $stmt->execute([
            $_POST['nama'], 
            $_POST['nis'], 
            $_POST['alamat'], 
            $_POST['provinsi'], 
            $_POST['kabupaten'], 
            $_POST['kecamatan'], 
            $_POST['tanggal_masuk'], 
            $_POST['nama_orang_tua'], 
            $_POST['nomer_hp_ortu'], 
            $_POST['id']
        ]);
        $message = "Santri berhasil diupdate"; $type = "success";
    } elseif ($_POST['action'] == 'delete') {
        $stmt = $pdo->prepare("DELETE FROM santri WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "Santri berhasil dihapus"; $type = "success";
    } elseif ($_POST['action'] == 'graduate') {
        // Proses luluskan santri ke alumni
        $id_santri = $_POST['id'];
        $santri_data = $pdo->prepare("SELECT * FROM santri WHERE id=?");
        $santri_data->execute([$id_santri]);
        $santri = $santri_data->fetch(PDO::FETCH_ASSOC);
        
        if ($santri) {
            // Gabungkan alamat lengkap
            $alamat_lengkap = $santri['alamat'] . ", " . $santri['kecamatan'] . ", " . $santri['kabupaten'] . ", " . $santri['provinsi'];
            $tahun_lulus = date('Y');
            
            $stmt = $pdo->prepare("INSERT INTO alumni (nama, alamat, tahun_lulus, pekerjaan) VALUES (?,?,?,?)");
            $stmt->execute([$santri['nama'], $alamat_lengkap, $tahun_lulus, 'Baru Lulus']);
            
            // Hapus dari santri
            $stmt2 = $pdo->prepare("DELETE FROM santri WHERE id=?");
            $stmt2->execute([$id_santri]);
            
            $message = "Santri berhasil diluluskan dan dipindahkan ke Alumni"; $type = "success";
        } else {
            $message = "Data santri tidak ditemukan"; $type = "danger";
        }
    } elseif ($_POST['action'] == 'expel') {
        // Proses keluarkan santri (hapus dari santri tanpa pindah ke alumni)
        $id_santri = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM santri WHERE id=?");
        $stmt->execute([$id_santri]);
        $message = "Santri berhasil dikeluarkan"; $type = "warning";
    }
}

// Ambil semua data santri
$santri = $pdo->query("SELECT * FROM santri ORDER BY id DESC")->fetchAll();
?>

<h1 class="mb-4">Manajemen Santri</h1>

<?php if($message): ?>
<div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
    <?= $message ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalSantri" onclick="resetForm()">
    <i class="fas fa-plus"></i> Tambah Santri
</button>

<div class="table-responsive">
    <table id="tableSantri" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 15%;">Nama</th>
                <th style="width: 20%;">Alamat Lengkap</th>
                <th style="width: 10%;">Tgl Masuk</th>
                <th style="width: 15%;">Orang Tua</th>
                <th style="width: 12%;">No HP Orang Tua</th>
                <th style="width: 13%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach($santri as $s): ?>
            <?php 
            // Gabungkan alamat lengkap
            $alamat_lengkap = $s['alamat'];
            if(!empty($s['kecamatan'])) $alamat_lengkap .= ", " . $s['kecamatan'];
            if(!empty($s['kabupaten'])) $alamat_lengkap .= ", " . $s['kabupaten'];
            if(!empty($s['provinsi'])) $alamat_lengkap .= ", " . $s['provinsi'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($s['nis']) ?></td>
                <td><?= htmlspecialchars($s['nama']) ?></td>
                <td><?= htmlspecialchars($alamat_lengkap) ?></td>
                <td><?= $s['tanggal_masuk'] ?></td>
                <td><?= htmlspecialchars($s['nama_orang_tua']) ?></td>
                <td><?= htmlspecialchars($s['nomer_hp_ortu']) ?></td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalSantri" onclick='editSantri(<?= json_encode($s) ?>)'>
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fas fa-cog"></i> Lainnya
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button type="button" class="dropdown-item text-success" onclick="confirmGraduate(<?= $s['id'] ?>, '<?= htmlspecialchars($s['nama']) ?>')">
                                        <i class="fas fa-graduation-cap"></i> Luluskan
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item text-danger" onclick="confirmExpel(<?= $s['id'] ?>, '<?= htmlspecialchars($s['nama']) ?>')">
                                        <i class="fas fa-sign-out-alt"></i> Keluarkan
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Tambah/Edit Santri -->
<div class="modal fade" id="modalSantri" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Santri</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="action" value="create">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NIS</label>
                            <input type="text" name="nis" id="nis" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label>Alamat <small class="text-muted">(contoh: Jl. Raya Kranji Gg. Masjid)</small></label>
                        <input type="text" name="alamat" id="alamat" class="form-control" placeholder="Jl. Raya Kranji Gg. Masjid">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" class="form-control" placeholder="Jawa Barat">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Kabupaten/Kota</label>
                            <input type="text" name="kabupaten" id="kabupaten" class="form-control" placeholder="Bekasi">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" id="kecamatan" class="form-control" placeholder="Kranji">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Orang Tua/Wali</label>
                            <input type="text" name="nama_orang_tua" id="nama_orang_tua" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No HP Orang Tua/Wali</label>
                            <input type="text" name="nomer_hp_ortu" id="nomer_hp_ortu" class="form-control">
                        </div>
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
                    <p>Yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Lulus -->
<div class="modal fade" id="modalGraduate">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-graduation-cap"></i> Konfirmasi Luluskan Santri</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="graduate">
                    <input type="hidden" name="id" id="graduate_id">
                    <p>Yakin ingin meluluskan santri <strong id="graduate_nama"></strong>?</p>
                    <p class="text-muted small">Data akan dipindahkan ke halaman Alumni dengan tahun lulus <?= date('Y') ?>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Luluskan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Keluarkan -->
<div class="modal fade" id="modalExpel">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-sign-out-alt"></i> Konfirmasi Keluarkan Santri</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="expel">
                    <input type="hidden" name="id" id="expel_id">
                    <p>Yakin ingin mengeluarkan santri <strong id="expel_nama"></strong>?</p>
                    <p class="text-muted small">Data akan dihapus dari sistem dan tidak dipindahkan ke Alumni.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Keluarkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() { 
    document.getElementById('action').value = 'create'; 
    document.getElementById('modalTitle').innerText = 'Tambah Santri'; 
    document.getElementById('id').value = ''; 
    document.getElementById('nis').value = ''; 
    document.getElementById('nama').value = ''; 
    document.getElementById('alamat').value = ''; 
    document.getElementById('provinsi').value = ''; 
    document.getElementById('kabupaten').value = ''; 
    document.getElementById('kecamatan').value = ''; 
    document.getElementById('tanggal_masuk').value = ''; 
    document.getElementById('nama_orang_tua').value = ''; 
    document.getElementById('nomer_hp_ortu').value = ''; 
}

function editSantri(data) { 
    document.getElementById('action').value = 'edit'; 
    document.getElementById('modalTitle').innerText = 'Edit Santri'; 
    document.getElementById('id').value = data.id; 
    document.getElementById('nis').value = data.nis; 
    document.getElementById('nama').value = data.nama; 
    document.getElementById('alamat').value = data.alamat || ''; 
    document.getElementById('provinsi').value = data.provinsi || ''; 
    document.getElementById('kabupaten').value = data.kabupaten || ''; 
    document.getElementById('kecamatan').value = data.kecamatan || ''; 
    document.getElementById('tanggal_masuk').value = data.tanggal_masuk; 
    document.getElementById('nama_orang_tua').value = data.nama_orang_tua || ''; 
    document.getElementById('nomer_hp_ortu').value = data.nomer_hp_ortu || ''; 
}

function setDelete(id) { 
    document.getElementById('delete_id').value = id; 
}

function confirmGraduate(id, nama) {
    document.getElementById('graduate_id').value = id;
    document.getElementById('graduate_nama').innerText = nama;
    var modal = new bootstrap.Modal(document.getElementById('modalGraduate'));
    modal.show();
}

function confirmExpel(id, nama) {
    document.getElementById('expel_id').value = id;
    document.getElementById('expel_nama').innerText = nama;
    var modal = new bootstrap.Modal(document.getElementById('modalExpel'));
    modal.show();
}
</script>