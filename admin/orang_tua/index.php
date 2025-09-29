<?php
include('../../koneksi.php');

session_start();
if (!isset($_SESSION['sebagai'])) {
    header("Location: ../../index.php");
}

if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'user') {
        header('Location: ../../index.php');
        exit;
    }
}

$orangTua = [];
$orangTuaQuery = mysqli_query(
    $koneksi,
    "SELECT orang_tua.*, data_siswa.nama AS nama_siswa, data_siswa.nis AS nis_siswa FROM orang_tua LEFT JOIN data_siswa ON orang_tua.id_siswa = data_siswa.nis ORDER BY orang_tua.nama ASC"
);
while ($row = mysqli_fetch_assoc($orangTuaQuery)) {
    $orangTua[] = $row;
}

$siswaList = [];
$siswaQuery = mysqli_query($koneksi, "SELECT nis, nama FROM data_siswa ORDER BY nama ASC");
while ($row = mysqli_fetch_assoc($siswaQuery)) {
    $siswaList[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Absensi | Kelola Data Orang Tua</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../../assets/img/smkmadya.png">
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: calc(1.5em + .75rem + 2px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + .75rem);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + .75rem);
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <div class="sticky-top">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../index.php">
                <div>
               <img src="../../assets/img/madep.png" alt="logo" width="40px">
               <span class="brand-text">Absensi</span>
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">



            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#booking" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Kelola Data</span>
                </a>
                <div id="booking" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../siswa/index.php">Siswa</a>
                        <a class="collapse-item" href="../akun/index.php">Admin</a>
                        <a class="collapse-item" href="../akun_siswa/index.php">Report Harian Masuk</a>
                        <a class="collapse-item" href="../akun_siswa/pulang.php">Report Harian Pulang</a>
                        <a class="collapse-item" href="../guru/index.php">Menu guru</a>
                        <a class="collapse-item active" href="index.php">Orang Tua</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-receipt"></i>
                    <span>Absensi</span>
                </a>
                    <div id="data" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="../absen/input.php">Masuk</a>
                            <a class="collapse-item" href="../absen/input_plg.php">Pulang</a>
                            <a class="collapse-item" href="../absen/barcode_umum.php">Barcode Masuk/Pulang</a>
                        </div>
                    </div>
                </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data2" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Absensi</span>
                </a>
                <div id="data2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../absen/data_absen.php">Data Tidak Hadir</a>
                        <a class="collapse-item" href="../absen/data_masuk.php">Data Absen Masuk</a>
                        <a class="collapse-item" href="../absen/data_pulang.php">Data Absen Pulang</a>
                    </div>
                </div>
            </li>


            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link" href="../../logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray"></i>
                    <span>Logout</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">


            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <div class="text-center d-none d-md-inline">
                <a class="btn" id="sidebarToggle"><i class="fas fa-bars"></i></a>

            </div>
                    <!-- profile info & task notification -->
                    <div class="col-md-0 col-sm-0 clearfix">
                        <ul class="navbar-nav pull-left">
                            <li><h4><div class="date">
                                                                <script type='text/javascript'>
                                                <!--
                                                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                                var myDays = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                                var date = new Date();
                                                var day = date.getDate();
                                                var month = date.getMonth();
                                                var thisDay = date.getDay(),
                                                        thisDay = myDays[thisDay];
                                                var yy = date.getYear();
                                                var year = (yy < 1000) ? yy + 1900 : yy;
                                                document.write(thisDay + ', ' + day + ' ' + months[month] + ' ' + year);
                                                //-->
                                                </script></b></div></h4>

                                                </li>
                        </ul>
                    </div>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['nama']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="../../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a href="../../logout.php" class="dropdown-item" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4"></div>

                    <button style="margin-bottom:20px" data-toggle="modal" data-target="#modalTambah" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-55"><i class="fas fa-plus"></i></span>
                        <span class="text">Tambah Orang Tua</span>
                    </button>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Kelola Data Orang Tua</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr align="center">
                                            <th>No</th>
                                            <th>Nama Orang Tua</th>
                                            <th>NIK</th>
                                            <th>Data Siswa</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($orangTua as $row) : ?>
                                            <tr>
                                                <td align="center"><?= $no++; ?></td>
                                                <td><?= $row['nama']; ?></td>
                                                <td><?= $row['nik']; ?></td>
                                                <td><?= $row['nama_siswa'] ? $row['nis_siswa'] . ' - ' . $row['nama_siswa'] : '-'; ?></td>
                                                <td align="center">
                                                    <a title="edit" class="btn btn-warning" href="edit.php?id=<?= $row['id']; ?>"><i class="fas fa-edit"></i></a>
                                                    <a title="hapus" class="btn btn-danger" href="proses/proses_hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Anda yakin akan menghapus data ini?')"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Modal Tambah -->
            <div id="modalTambah" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Data Orang Tua</h4>
                        </div>
                        <form method="POST" action="proses/proses_tambah.php">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama">Nama Orang Tua</label>
                                    <input type="text" name="nama" id="nama" required="required" placeholder="Nama Orang Tua" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="nik">NIK</label>
                                    <input type="text" name="nik" id="nik" required="required" placeholder="NIK" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="pw">Password</label>
                                    <input type="text" name="pw" id="pw" required="required" placeholder="Password" autocomplete="off" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="id_siswa">Data Siswa</label>
                                    <select name="id_siswa" id="id_siswa" required="required" class="form-control js-siswa-select" data-placeholder="Cari siswa berdasarkan NIS / Nama">
                                        <option value="">Pilih Siswa</option>
                                        <?php foreach ($siswaList as $siswa) : ?>
                                            <option value="<?= $siswa['nis']; ?>"><?= $siswa['nis']; ?> - <?= $siswa['nama']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                                    <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
                                    <a href="index.php" class="btn btn-sm btn-secondary"><i class="fa fa-reply"></i> Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>



        <!-- Bootstrap core JavaScript-->
        <script src="../../assets/vendor/jquery/jquery.min.js"></script>
        <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="../../assets/js/sb-admin-2.min.js"></script>

        <!-- Page level plugins -->
        <script src="../../assets/vendor/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="../../assets/js/demo/chart-area-demo.js"></script>
        <script src="../../assets/js/demo/chart-pie-demo.js"></script>

        <!-- Page level plugins -->
        <script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
        <script src="../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.js-siswa-select').each(function() {
                    var $this = $(this);
                    var parentModal = $this.closest('.modal');
                    $this.select2({
                        width: '100%',
                        placeholder: $this.data('placeholder') || 'Pilih Siswa',
                        allowClear: true,
                        dropdownParent: parentModal.length ? parentModal : $(document.body)
                    });
                });
            });
        </script>

        <!-- Page level custom scripts -->
        <script src="../../assets/js/demo/datatables-demo.js"></script>

</body>

</html>
