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
$sekarang=date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Absensi | Data Absen</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../../assets/img/smkmadya.png">
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#booking" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Kelola Data</span>
                </a>
                <div id="booking" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="../siswa/index.php">Siswa</a>
                        <a class="collapse-item" href="../akun/index.php">Admin</a>
                        <a class="collapse-item" href="../akun_siswa/index.php">Report Harian Masuk</a>
                        <a class="collapse-item" href="../akun_siswa/pulang.php">Report Harian Pulang</a>
                        <a class="collapse-item" href="../guru/index.php">Menu guru</a>
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
                        <a class="collapse-item" href="input.php">Masuk</a>
                        <a class="collapse-item" href="input_plg.php">Pulang</a>
                        <a class="collapse-item" href="barcode_umum.php">Barcode Masuk/Pulang</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data2" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Absensi</span>
                </a>
                <div id="data2" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item active" href="../absen/data_absen.php">Data Tidak Hadir</a>
                        <a class="collapse-item" href="../absen/data_masuk.php">Data Absen Masuk</a>
                        <a class="collapse-item" href="../absen/data_pulang.php">Data Absen Pulang</a>
                    </div>
                </div>
            </li>


            <!-- Divider -->
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
       <nav class="navbar navbar-expand navbar-light bg-white topbar mb-0 static-top shadow">
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
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['nama']; ?></span>
                                <img class="img-profile rounded-circle" src="../../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a href="../../logout.php" class="dropdown-item">
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

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    </div>


                    <!-- DataTales Example -->
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <br>
                        <div class="card-body">
                          
                        <div class="col-md-12">
    <p>Note : Harus Di Isi Kelas Dan Pilih Tanggal Yang Di Tuju</p>
    <div class="row">
        <div class="col-md-4">
            <b for="tgl1">Pilih Kelas</b>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text ti-calendar bg-white"></span>
                </div>
                <select class="form-control" id="datepicker1" name="class">
                    <option value="">Pilih...</option>
                    <option value="all">Semua</option>
                    <option value="X PPLG 1">X PPLG 1</option>
                    <option value="X PPLG 2">X PPLG 2</option>
                    <option value="XI PPLG 1">XI PPLG 1</option>
                    <option value="XI PPLG 2">XI PPLG 2</option>
                    <option value="XI PEMASARAN">XI PEMASARAN</option>
                    <option value="XI AKUNTANSI">XI AKUNTANSI</option>

                </select>
            </div>
        </div>
        <div class="col-md-4">
            <b for="tgl2">Pilih Tanggal</b>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text ti-calendar bg-white"></span>
                </div>
                <input type="date" name="tgl2" id="datepicker2" class="form-control" style="cursor: pointer;" placeholder="Ke Tanggal" value="<?php echo date('Y-m-d')?>">
            </div>
        </div>
    </div>
</div>

<!-- Tempat untuk menampilkan data siswa -->
<div id="result"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Function untuk ambil data secara otomatis
        function fetchData() {
            var kelas = $('#datepicker1').val();
            var tanggal = $('#datepicker2').val();

            // Cek jika kelas dan tanggal sudah diisi
            if(kelas && tanggal) {
                $.ajax({
                    url: 'proses/proses_ambil_data.php', // Ganti dengan URL ke file PHP yang akan memproses data
                    type: 'POST',
                    data: {class: kelas, tgl2: tanggal},
                    success: function(response){
                        // Tampilkan data hasilnya di div result
                        $('#result').html(response);
                    },
                    error: function(xhr, status, error){
                        console.error(error);
                    }
                });
            }
        }

        // Ketika kelas dipilih
        $('#datepicker1').change(function(){
            fetchData(); // Panggil fungsi fetchData setiap kali kelas dipilih
        });

        // Ketika tanggal diubah
        $('#datepicker2').change(function(){
            fetchData(); // Panggil fungsi fetchData setiap kali tanggal diubah
        });
    });
</script>

    
                            </div>

                    </div>
                    <!-- End of Main Content -->

                </div>
                <!-- End of Page Wrapper -->
                
                    </div>
                    <!-- End of Main Content -->

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

                <!-- Page level plugins -->
                <script src="../../assets/vendor/datatables/jquery.dataTables.min.js"></script>
                <script src="../../assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

                <!-- Page level custom scripts -->
                <script src="../../assets/js/demo/chart-area-demo.js"></script>
                <script src="../../assets/js/demo/chart-pie-demo.js"></script>


                <!-- Page level custom scripts -->
                <script src="../../assets/js/demo/datatables-demo.js"></script>


<script>
    $('#table').dataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    $('#table2').dataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
      </script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript">
            function isi_otomatis(){
                var nis = $("#nis").val();
                $.ajax({
                    url: 'proses/proses_ajax.php',
                    data:"nis="+nis ,
                }).success(function (data) {
                    var json = data,
                    obj = JSON.parse(json);
                    $('#nama').val(obj.nama);
                    $('#kelas').val(obj.kelas);
                });
            }
        </script>
</body>

</html>
