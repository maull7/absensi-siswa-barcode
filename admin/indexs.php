<?php
include '../koneksi.php';
session_start();
if (!isset($_SESSION['sebagai'])) {
    header("Location: ../index.php");
}

if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'user') {
        header('Location: ../index.php');
        exit;
    }
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
    <title>Absensi | Homepage</title>
    <!-- Custom fonts for this template-->
    <link rel="icon" href="../assets/img/smkmadya.png">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        $sidebarCurrentPage = 'dashboard';
        $sidebarRootPath = '../';
        $sidebarAdminPath = '';
        include __DIR__ . '/sidebar.php';
        ?>
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
                                    src="../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a href="../logout.php" class="dropdown-item" >
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->            
                <?php
                    $a = 0;
                    $query  = "SELECT count(nis) AS tm FROM data_siswa WHERE nis";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $a  = $data['tm'];
                    }
                
                    $b = 0;
                    $query  = "SELECT count(id) AS lg FROM login WHERE id";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $b  = $data['lg'];
                    }

                    $c = 0;
                    $query  = "SELECT count(nis) AS am FROM masuk WHERE id";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $c  = $data['am'];
                    }
                
                    $d = 0;
                    $query  = "SELECT count(id) AS ap FROM pulang WHERE id";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $d  = $data['ap'];
                    }

                    $e = 0;
                    $query  = "SELECT count(nis) AS si FROM absen WHERE status='Izin'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $e  = $data['si'];
                    }
                
                    $f = 0;
                    $query  = "SELECT count(id) AS ss FROM absen WHERE status='Sakit'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $f  = $data['ss'];
                    }

                    $g = 0;
                    $query  = "SELECT count(nis) AS sa FROM absen WHERE status='Alpha'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $g  = $data['sa'];
                    }
                ?>
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4"></div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Dashboard</h6>
                            </div>
                        <div class="card-body">
                        
                        <!-- Content Row -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xl font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Siswa</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($a); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xl font-weight-bold text-warning text-uppercase mb-1">
                                                    Total Pengguna</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($b); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xl font-weight-bold text-success text-uppercase mb-1">
                                                    Absen Masuk</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($c); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xl font-weight-bold text-danger text-uppercase mb-1">
                                                    Absen Pulang</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($d); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                       

                            <div class="col-xl-4 col-md-6 mb-4">
                                            <div class="card border-left-success shadow h-100 py-2">
                                                <div class="card-body">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-xl font-weight-bold text-success text-uppercase mb-1">
                                                            Total Siswa Izin</div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($e); ?></div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-address-book fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    

                            <div class="col-xl-4 col-md-6 mb-4">
                                            <div class="card border-left-warning shadow h-100 py-2">
                                                <div class="card-body">
                                                    <div class="row no-gutters align-items-center">
                                                        <div class="col mr-2">
                                                            <div class="text-xl font-weight-bold text-warning text-uppercase mb-1">
                                                            Total Siswa Sakit</div>
                                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($f); ?></div>
                                                        </div>
                                                        <div class="col-auto">
                                                            <i class="fas fa-address-book fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card border-left-danger shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xl font-weight-bold text-danger text-uppercase mb-1">
                                                    Total Siswa Alpha</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($g); ?></div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-address-book fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   

                    </div>

                </div>
                <!-- End of Main Content -->


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../assets/js/demo/chart-area-demo.js"></script>
    <script src="../assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>