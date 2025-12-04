<?php
$sidebarCurrentPage = $sidebarCurrentPage ?? '';
$sidebarRootPath = $sidebarRootPath ?? '../';
$sidebarAdminPath = $sidebarAdminPath ?? '';

if (!function_exists('adminSidebarActive')) {
    function adminSidebarActive(string $current, array $targets): string
    {
        return in_array($current, $targets, true) ? 'active' : '';
    }
}

if (!function_exists('adminSidebarShow')) {
    function adminSidebarShow(string $current, array $targets): string
    {
        return in_array($current, $targets, true) ? 'show' : '';
    }
}

$kelolaTargets = ['siswa', 'admin', 'report_masuk', 'report_pulang', 'guru', 'orang_tua', 'report_bulanan'];
$absensiTargets = ['absen_masuk', 'absen_pulang', 'barcode'];
$dataAbsensiTargets = ['data_tidak_hadir', 'data_absen_masuk', 'data_absen_pulang'];
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <div class="sticky-top">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $sidebarAdminPath ?>index.php">
            <div>
                <img src="<?= $sidebarRootPath ?>assets/img/madep.png" alt="logo" width="40px">
                <span class="brand-text">Absensi</span>
            </div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item <?= adminSidebarActive($sidebarCurrentPage, ['dashboard']) ?>">
            <a class="nav-link" href="<?= $sidebarAdminPath ?>index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Nav Item - Kelola Data -->
        <li class="nav-item <?= adminSidebarActive($sidebarCurrentPage, $kelolaTargets) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#booking" aria-expanded="true"
                aria-controls="booking">
                <i class="fas fa-fw fa-table"></i>
                <span>Kelola Data</span>
            </a>
            <div id="booking" class="collapse <?= adminSidebarShow($sidebarCurrentPage, $kelolaTargets) ?>"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['siswa']) ?>" href="<?= $sidebarAdminPath ?>siswa/index.php">Siswa</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['admin']) ?>" href="<?= $sidebarAdminPath ?>akun/index.php">Admin</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['report_masuk']) ?>" href="<?= $sidebarAdminPath ?>akun_siswa/index.php">Report Harian Masuk</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['report_pulang']) ?>" href="<?= $sidebarAdminPath ?>akun_siswa/pulang.php">Report Harian Pulang</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['guru']) ?>" href="<?= $sidebarAdminPath ?>guru/index.php">Menu guru</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['orang_tua']) ?>" href="<?= $sidebarAdminPath ?>orang_tua/index.php">Orang Tua</a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider">

        <!-- Nav Item - Absensi -->
        <li class="nav-item <?= adminSidebarActive($sidebarCurrentPage, $absensiTargets) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true"
                aria-controls="data">
                <i class="fas fa-fw fa-receipt"></i>
                <span>Absensi</span>
            </a>
            <div id="data" class="collapse <?= adminSidebarShow($sidebarCurrentPage, $absensiTargets) ?>"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['absen_masuk']) ?>" href="<?= $sidebarAdminPath ?>absen/input.php">Masuk</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['absen_pulang']) ?>" href="<?= $sidebarAdminPath ?>absen/input_plg.php">Pulang</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['barcode']) ?>" href="<?= $sidebarAdminPath ?>absen/barcode_umum.php">Barcode Masuk/Pulang</a>
                </div>
            </div>
        </li>
        <hr class="sidebar-divider">

        <!-- Nav Item - Data Absensi -->
        <li class="nav-item <?= adminSidebarActive($sidebarCurrentPage, $dataAbsensiTargets) ?>">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data2" aria-expanded="true"
                aria-controls="data2">
                <i class="fas fa-fw fa-table"></i>
                <span>Data Absensi</span>
            </a>
            <div id="data2" class="collapse <?= adminSidebarShow($sidebarCurrentPage, $dataAbsensiTargets) ?>"
                aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['data_tidak_hadir']) ?>" href="<?= $sidebarAdminPath ?>absen/data_absen.php">Data Tidak Hadir</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['data_absen_masuk']) ?>" href="<?= $sidebarAdminPath ?>absen/data_masuk.php">Data Absen Masuk</a>
                    <a class="collapse-item <?= adminSidebarActive($sidebarCurrentPage, ['data_absen_pulang']) ?>" href="<?= $sidebarAdminPath ?>absen/data_pulang.php">Data Absen Pulang</a>
                </div>
            </div>
        </li>

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
