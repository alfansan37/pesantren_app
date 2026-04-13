<?php
// Hitung total data dari masing-masing tabel
$total_santri = $pdo->query("SELECT COUNT(*) FROM santri")->fetchColumn();
$total_ustadz = $pdo->query("SELECT COUNT(*) FROM ustadz")->fetchColumn();
$total_alumni = $pdo->query("SELECT COUNT(*) FROM alumni")->fetchColumn();
?>
<h1 class="mb-4">Dashboard</h1>
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user-graduate"></i> Total Santri</h5>
                <h2 class="card-text"><?= $total_santri ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-chalkboard-user"></i> Total Ustadz</h5>
                <h2 class="card-text"><?= $total_ustadz ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Total Alumni</h5>
                <h2 class="card-text"><?= $total_alumni ?></h2>
            </div>
        </div>
    </div>
</div>