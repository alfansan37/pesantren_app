<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
require_once 'config/database.php';
include 'views/layouts/header.php';

switch($page) {
    case 'dashboard': include 'views/dashboard.php'; break;
    case 'santri':    include 'views/santri.php'; break;
    case 'ustadz':    include 'views/ustadz.php'; break;
    case 'alumni':    include 'views/alumni.php'; break;
    default:          include 'views/dashboard.php'; break;
}

include 'views/layouts/footer.php';
?>