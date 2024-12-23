<?php
// memanggil file koneksi.php untuk membuat koneksi
include '../../koneksi.php';

// mengecek apakah di url ada nilai GET id
if (isset($_GET['nis'])) {
  // ambil nilai id dari url dan disimpan dalam variabel $id
  $nis = ($_GET["nis"]);

  // menampilkan data dari database yang mempunyai id=$id
  $query = "SELECT * FROM data_siswa WHERE nis='$nis'";
  $result = mysqli_query($koneksi, $query);
  // jika data gagal diambil maka akan tampil error berikut
  if (!$result) {
    die("Query Error: " . mysqli_errno($koneksi) .
      " - " . mysqli_error($koneksi));
  }
  // mengambil data dari database
  $data = mysqli_fetch_assoc($result);
  // apabila data tidak ada pada database maka akan dijalankan perintah ini
  if (!count($data)) {
    echo "<script>alert('Data tidak ditemukan pada database');window.location='index.php';</script>";
  }
} else {
  // apabila tidak ada data GET id pada akan di redirect ke index.php
  echo "<script>alert('Masukkan data id.');window.location='index.php';</script>";
}
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
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
  <title>Absensi | Edit Data Siswa</title>
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
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
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
                        <a class="collapse-item active" href="index.php">Siswa</a>
                        <a class="collapse-item" href="../akun/index.php">Admin</a>
                        <a class="collapse-item" href="../akun_siswa/index.php">Report Harian</a>
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
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['username']; ?></span>
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

           <!-- Content Row -->
<br>
          <div class="row">

            <div class="col-sm-8">
              <div class="card shadow">
                <div class="card-header">
                  <h6 class="m-0 font-weight-bold text-primary">Ubah Data Siswa</h6>
                </div>
                <div class="card-body">
                  <form method="POST" action="proses/proses_edit.php" enctype="multipart/form-data">
                    <section class="base">
                    <!-- menampung nilai id  yang akan di edit -->
                    <input name="nis" value="<?php echo $data['nis']; ?>" hidden />
                      <div class="row">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="nis">NIS</label>
                            <input type="text" value="<?php echo $data['nis']; ?>" name="nis" id="nis" required="required" autocomplete="off" class="form-control" readonly>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <label for="nama">Nama Siswa</label>
                            <input type="text" value="<?php echo $data['nama']; ?>" name="nama" id="nama" required="required" autocomplete="off" class="form-control" readonly>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <input type="text" value="<?php echo $data['jenis_kelamin']; ?>" name="jenis_kelamin" id="jenis_kelamin" required="required" autocomplete="off" class="form-control" readonly>
                          </div>
                        </div>
                    </div>
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="tempat_l">Tempat Lahir</label>
                            <input type="text" value="<?php echo $data['tempat_l']; ?>" name="tempat_l" id="tempat_l" required="required" autocomplete="off" class="form-control" readonly>
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label for="tanggal_l">Tanggal Lahir</label>
                            <input type="text" value="<?php echo $data['tanggal_l']; ?>" name="tanggal_l" id="tanggal_l" required="required" autocomplete="off" class="form-control" readonly>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="kelas">Kelas</label>
                            <input type="text" value="<?php echo $data['kelas']; ?>" name="kelas" id="kelas" required="required" autocomplete="off" class="form-control" >
                          </div>
                        </div>
                        <div class="col">
                          <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <input type="text" value="<?php echo $data['jurusan']; ?>" name="jurusan" id="jurusan" required="required" autocomplete="off" class="form-control" >
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" value="<?php echo $data['alamat']; ?>" name="alamat" id="alamat" required="required" autocomplete="off" class="form-control" >
                      </div>
                      <label for="img">Upload Barcode</label>
                    <div class="form-group">
                    <div class="custom-file">
                      <input type="file" value="../assets/images/<?php echo $data['img']; ?>" name="img" class="custom-file-input" id="img" style="cursor: pointer;">
                      <label class="custom-file-label" for="img">Pilih foto...</label>
                    </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-sm btn-primary" name="ubah"><i class="fa fa-pen"></i> Ubah</button>
                      <a href="index.php" class="btn btn-sm btn-secondary"><i class="fa fa-reply"></i> Kembali</a>
                    </div>
                    </section>
                  </form>
                </div>
              </div>
            </div>
            
	<div class="col-md-4">
		<div class="card card-success">
			<div class="card-header">
				<center>
				<h5 class="m-0 font-weight-bold text-primary">Barcode</h5>
				</center>
			  <div class="card-tools"></div>
			</div>
			<div class="card-body">
				<div class="text-center">
					<img src="../../assets/images/<?= $data['img']; ?>" width="160px" />
				</div>
				<h5 class="m-2 font-weight-bold text-center text-primary">
					<?php echo $data['nama']; ?>
				</h5>
			</div>
		</div>
	</div>

          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->


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

    <!-- Page level custom scripts -->
    <script src="../../assets/js/demo/datatables-demo.js"></script>


</body>

</html>