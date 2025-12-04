<?php
$sidebarCurrentPage = $sidebarCurrentPage ?? '';
$sidebarRootPath = $sidebarRootPath ?? '../';
$sidebarUserPath = $sidebarUserPath ?? '';

if (!function_exists('userSidebarActive')) {
    function userSidebarActive(string $current, array $targets): string
    {
        return in_array($current, $targets, true) ? 'active' : '';
    }
}

if (!function_exists('userSidebarShow')) {
    function userSidebarShow(string $current, array $targets): string
    {
        return in_array($current, $targets, true) ? 'show' : '';
    }
}

$absensiTargets = ['absen_masuk', 'absen_pulang'];
$dataAbsensiTargets = ['data_absen_masuk', 'data_absen_pulang', 'report_harian'];
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <div class="sticky-top">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
            <div>
                <img src="<?= $sidebarRootPath ?>assets/img/madep.png" alt="logo" width="40px">
                <span class="brand-text">Absensi</span>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Divider -->
        <hr class="sidebar-divider">
        <li class="nav-item <?= userSidebarActive($sidebarCurrentPage, $absensiTargets) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true"
                aria-controls="data">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Absensi</span>
            </a>
            <div id="data" class="collapse <?= userSidebarShow($sidebarCurrentPage, $absensiTargets) ?>"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= userSidebarActive($sidebarCurrentPage, ['absen_masuk']) ?>" href="<?= $sidebarUserPath ?>index.php">Masuk</a>
                    <a class="collapse-item <?= userSidebarActive($sidebarCurrentPage, ['absen_pulang']) ?>" href="<?= $sidebarUserPath ?>input_plg.php">Pulang</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider">
        <li class="nav-item <?= userSidebarActive($sidebarCurrentPage, $dataAbsensiTargets) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data2" aria-expanded="true"
                aria-controls="data2">
                <i class="fas fa-fw fa-table"></i>
                <span>Data Absensi</span>
            </a>
            <div id="data2" class="collapse <?= userSidebarShow($sidebarCurrentPage, $dataAbsensiTargets) ?>"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= userSidebarActive($sidebarCurrentPage, ['data_absen_masuk']) ?>" href="<?= $sidebarUserPath ?>data_masuk.php">Data Absen Masuk</a>
                    <a class="collapse-item <?= userSidebarActive($sidebarCurrentPage, ['data_absen_pulang']) ?>" href="<?= $sidebarUserPath ?>data_pulang.php">Data Absen Pulang</a>
                    <a class="collapse-item <?= userSidebarActive($sidebarCurrentPage, ['report_harian']) ?>" href="<?= $sidebarUserPath ?>akun_siswa/index.php">Report Harian</a>
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <li class="nav-item">
            <a class="nav-link" href="<?= $sidebarRootPath ?>logout.php">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray"></i>
                <span>Logout</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

    </div>
</ul>
