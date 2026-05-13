<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesantren</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <div class="bg-primary text-white" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4">
            <i class="fas fa-mosque fa-2x"></i>
            <h5 class="mt-2">Manajemen Pesantren</h5>
        </div>
        <div class="list-group list-group-flush">
            <a href="index.php?page=dashboard" class="list-group-item list-group-item-action bg-primary text-white <?= ($page == 'dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="index.php?page=santri" class="list-group-item list-group-item-action bg-primary text-white <?= ($page == 'santri') ? 'active' : '' ?>">
                <i class="fas fa-user-graduate"></i> Santri
            </a>
            <a href="index.php?page=ustadz" class="list-group-item list-group-item-action bg-primary text-white <?= ($page == 'ustadz') ? 'active' : '' ?>">
                <i class="fas fa-chalkboard-user"></i> Ustadz
            </a>
            <a href="index.php?page=alumni" class="list-group-item list-group-item-action bg-primary text-white <?= ($page == 'alumni') ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Alumni
            </a>
        </div>
    </div>
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <button class="btn btn-primary" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto">
                    <span class="navbar-text">
                        <i class="fas fa-calendar-alt"></i> <?= date('d F Y') ?>
                    </span>
                </div>
            </div>
        </nav>
        <div class="container-fluid px-4 py-3">