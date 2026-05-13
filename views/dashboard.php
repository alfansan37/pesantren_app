<?php
// Hitung total data dari masing-masing tabel
$total_santri = $pdo->query("SELECT COUNT(*) FROM santri")->fetchColumn();
$total_ustadz = $pdo->query("SELECT COUNT(*) FROM ustadz")->fetchColumn();
$total_alumni = $pdo->query("SELECT COUNT(*) FROM alumni")->fetchColumn();

// Hitung tambahan statistik (opsional)
// $santri_pria = $pdo->query("SELECT COUNT(*) FROM santri WHERE jenis_kelamin = 'Laki-laki'")->fetchColumn();
// $santri_wanita = $pdo->query("SELECT COUNT(*) FROM santri WHERE jenis_kelamin = 'Perempuan'")->fetchColumn();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card bg-primary text-white shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <i class="fas fa-user-graduate fa-3x"></i>
                    </div>
                    <div class="col-9">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Santri</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_santri ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?page=santri" class="text-white small text-decoration-none">Lihat detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card bg-success text-white shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <i class="fas fa-chalkboard-user fa-3x"></i>
                    </div>
                    <div class="col-9">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Ustadz</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_ustadz ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?page=ustadz" class="text-white small text-decoration-none">Lihat detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card bg-warning text-white shadow h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <div class="col-9">
                        <div class="text-xs font-weight-bold text-white-50 text-uppercase mb-1">Total Alumni</div>
                        <div class="h2 mb-0 font-weight-bold"><?= $total_alumni ?></div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="index.php?page=alumni" class="text-white small text-decoration-none">Lihat detail <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Baris kedua: Informasi tambahan (opsional) -->
<div class="row mt-4">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white">
                <i class="fas fa-chart-pie"></i> Statistik Santri
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h5 text-primary"><?= $santri_pria ?></div>
                        <div class="small text-muted">Santri Laki-laki</div>
                    </div>
                    <div class="col-6">
                        <div class="h5 text-success"><?= $santri_wanita ?></div>
                        <div class="small text-muted">Santri Perempuan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white">
                <i class="fas fa-info-circle"></i> Informasi Sistem
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Total Data:</strong> <?= $total_santri + $total_ustadz + $total_alumni ?> record</p>
                <p class="mb-1"><strong>Database:</strong> db_pesantren</p>
                <p class="mb-0"><strong>Versi:</strong> 1.0.0</p>
            </div>
        </div>
    </div>
</div>