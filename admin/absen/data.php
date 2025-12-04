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
        <?php
        $sidebarCurrentPage = 'data_tidak_hadir';
        $sidebarRootPath = '../../';
        $sidebarAdminPath = '../';
        include __DIR__ . '/../sidebar.php';
        ?>
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
                        <a href="proses/proses_cetak_absen.php" target="_blank" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-55">
                                <i class="fas fa-print"></i>
                            </span>
                            <span class="text">Cetak Data</span>
                        </a>
                    </div><br>
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Siswa tidak Hadir</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table" width="100%" cellspacing="0">
                                    <thead>
										<tr align="center">
										 <th> No </th>
										 <th> Tanggal </th>
										 <th> NIS </th>
										 <th> Nama </th>
										 <th> Kelas </th>
										 <th> Status </th>
										 <th> Keterangan </th>
										 <th> Action </th>


										</tr></thead>
										 
                                        <tbody>
                                         <?php  
										$queri="Select * From absen INNER JOIN data_siswa ON absen.nis = data_siswa.nis" ;
										$hasil=MySQLi_query ($koneksi,$queri);
										$i = 1;
										while ($data = mysqli_fetch_array ($hasil)){
                                        $id = $data['id'];
										$nis = $data['nis'];
										 ?>
										  <tr>
										  <td align="center"><?=$i++?></td>
										  <td><?= $data['tanggal']; ?></td>
										  <td><a href="../siswa/detail.php?nis=<?=$nis;?>"><?= $data['nis']; ?></a></td>
										  <td><?= $data['nama']; ?></td>
										  <td><?= $data['kelas']; ?></td>
										  <td align="center"><?php
                                                  if ($data['status'] == 'Izin') {
                                                      echo '<div class ="badge badge-success" style="width: 70px;" > <b>Izin</b> </div>';
                                                    } else if ($data['status'] == 'Sakit') {
                                                      echo '<div class ="badge badge-warning" style="width: 70px;" > <b>Sakit</b> </div>';
                                                    } else if ($data['status'] == 'Alpha') {
                                                        echo '<div class ="badge badge-danger" style="width: 70px;" > <b>Alpha</b> </div>';
                                                    }
                                                    ?></td>
										  <td><?= $data['keterangan']; ?></td>
                                            <td align="center"><a data-toggle="modal" title ="hapus" data-target="#del<?= $id; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a></td>
										  </tr>

                                            <!-- The Modal -->
                                            <div class="modal fade" id="del<?=$id;?>">
                                                <div class="modal-dialog">
                                                <div class="modal-content">
                                                <form method="post">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Hapus Data</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus data ini?
                                                    <input type="hidden" name="id" value="<?=$id;?>">
                                                    </div>
                                                    
                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-success" name="hapus">Hapus</button>
                                                    </div>
                                                    </form>
                                                </div>
                                                </div>
                                            </div>
                                         <?php
                                            }
                                            if(isset($_POST['submit'])){
                                            $nis = $_POST['nis'];
                                            $tanggal = $_POST['tanggal'];
                                            $status = $_POST['status'];
                                            $keterangan = $_POST['keterangan'];

                                            $update = "INSERT INTO absen (nis, tanggal, status, keterangan) VALUES ('$nis','$tanggal', '$status' ,'$keterangan')";
                                            $hasil = mysqli_query($koneksi,$update);


                                            if ($hasil){
                                            //header ('location:view.php');
                                            echo " <div class='alert alert-success'>
                                            <strong>Success!</strong> Redirecting you back in 1 seconds.
                                            </div>
                                            <meta http-equiv='refresh' content='1; url= data_absen.php'/>  ";
                                            } else { echo "<div class='alert alert-warning'>
                                            <strong>Failed!</strong> Redirecting you back in 1 seconds.
                                            </div>
                                            <meta http-equiv='refresh' content='1; url= data_absen.php'/> ";
                                            }
                                        };

                                    if(isset($_POST['hapus'])){
                                        $id = $_POST['id'];
                                
                                        $delete = mysqli_query($koneksi,"delete from absen where id='$id'");
                                        if ($delete) {
                                        echo " <div class='alert alert-success'> <strong>Success!</strong> Redirecting you back in 1 seconds. </div>
                                    <meta http-equiv='refresh' content='1; url= data_absen.php'/>";
                                    } else { echo "<div class='alert alert-warning'> <strong>Failed!</strong> Redirecting you back in 1 seconds. </div>
                                    <meta http-equiv='refresh' content='1; url= data_absen.php'/>";
                                    }
                                    };
										?>
                                        </tbody>
                                        <form method ='POST'>
										 <tr>
											<td><center> # </center> </td>
											<td> <input type = "text" class="form-control" name = "tanggal" value="<?= $sekarang; ?>" readonly /> </td>
											<td> <input type = "text" class="form-control" name = "nis" id="nis" onkeyup="isi_otomatis()" placeholder="NIS" required /> </td>
											<td> <input type="text" class="form-control" name = "nama" id="nama" placeholder="Nama" readonly></td>
											<td> <input type="text" class="form-control" name = "kelas" id="kelas" placeholder="Kelas" readonly></td>
											<td> <select class="form-control" name = "status" >
                                                <option value="">Pilih</option>
                                                <option value="Izin">Izin</option>
                                                <option value="Sakit">Sakit</option>
                                                <option value="Alpha">Alpha</option>
                                                </select>
                                             </td>
											<td> <input type="text" class="form-control" name="keterangan" placeholder="Keterangan" required /> </td>
											<td align="center"> <button type="submit" name="submit" class="btn btn-primary" title="tambah data"><i class="fas fa-plus"></i></button></center></td>
                                            </tr>
										 </form>
                                        
										</table>
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
