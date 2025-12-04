<?php
include('../../koneksi.php');
$result = mysqli_query($koneksi, "SELECT * FROM login");
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
  $rows[] = $row;
}

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
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Absensi | Kelola Data Admin</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../../assets/img/smkmadya.png">
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php
        $sidebarCurrentPage = 'admin';
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
                <div class="container-fluid">

                    <!-- Page Heading -->
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        
                    </div>

                    <button style="margin-bottom:20px" data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-icon-split"><span class="icon text-white-55"><i class="fas fa-plus"></span></i><span class="text">Tambah Akun</span></button>                                      
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Kelola Data Akun</h6>
                        </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                            <tr align="center">
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Nama</th>
                                                <th>Sebagai</th>
                                                <th>Aksi</th>
												
											</tr></thead><tbody>
											<?php 
											$form=mysqli_query($koneksi,"SELECT * FROM login ORDER BY id ASC");
											$no=1;
											while($b=mysqli_fetch_array($form)){
                                                $id = $b['id'];
												?>
												
                                                
												<tr>
													<td align="center"><?php echo $no++ ?></td>
													<td><?php echo $b['username'] ?></td>
													<td><?php echo $b['nama'] ?></td>
													<td align="center">
                                                        <?php if($b['sebagai'] == 'admin') {
                                                                echo '<div class = "badge badge-success" style="width: 70px;"> <b>Admin</b> </div>';
                                                                }
                                                        ?>
                                                        <?php if($b['sebagai'] == 'user') {
                                                            echo '<div class = "badge badge-primary" style="width: 70px;"> <b>User</b> </div>';
                                                            }
                                                    ?></td>
                                                    <td align="center">
                                                        <a title="edit" class="btn btn-warning" href="edit.php?id=<?php echo $b['id']; ?>"><i class="fas fa-edit"></i></a>
                                                        <a title="hapus" class="btn btn-danger" href="proses/proses_hapus.php?id=<?php echo $b['id']; ?>" onclick="return confirm('Anda yakin akan menghapus data ini?')"><i class="fas fa-trash"></i></a>&nbsp;
                                                    </td>
												</tr>		
                                                
                                                <?php 
                                                };
											?>
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
            <!-- modal input -->
			<div id="myModal" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Tambah Akun Baru</h4>
						</div>
                        <form method="POST" action="proses/proses_tambah.php" enctype="multipart/form-data">
						<div class="modal-body">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" required="required" placeholder="Username" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" name="password" id="password" required="required" placeholder="Password" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" name="nama" id="nama" required="required" placeholder="Nama" autocomplete="off" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="sebagai">Sebagai</label>
                                <select name="sebagai" id="sebagai" class="form-control">
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</button>
                                <button type="reset" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
                                <a href="index.php" class="btn btn-sm btn-secondary"><i class="fa fa-reply"></i> Kembali</a>
                            </div>
						</form>
					</div>
				</div>
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