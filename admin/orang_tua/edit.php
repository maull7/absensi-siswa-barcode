<?php
// memanggil file koneksi.php untuk membuat koneksi
include '../../koneksi.php';

// mengecek apakah di url ada nilai GET id
if (isset($_GET['id'])) {
  // ambil nilai id dari url dan disimpan dalam variabel $id
  $id = ($_GET["id"]);

  // menampilkan data dari database yang mempunyai id=$id
  $query = "SELECT * FROM orang_tua WHERE id='$id'";
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
if (!isset($_SESSION['sebagai'])) {
    header("Location: ../../index.php");
}

if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'user') {
        header('Location: ../../index.php');
        exit;
    }
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

  <title>Absensi | Ubah Data Orang Tua</title>

  <!-- Custom fonts for this template-->
  <link rel="icon" href="../../assets/img/smkmadya.png">
  <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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
        <?php
        $sidebarCurrentPage = 'orang_tua';
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

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['nama']; ?></span>
                <img class="img-profile rounded-circle"
               src="../../assets/img/undraw_profile.svg">
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
            <h1 class="h3 mb-0 text-gray-800">Ubah Data Orang Tua</h1>
          </div>

          <!-- Content Row -->

          <div class="row">

            <div class="col-sm-6">
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold text-primary">Form Orang Tua</h6>
                </div>
                <div class="card-body">
                  <form method="POST" action="proses/proses_edit.php">
                    <input name="id" value="<?= $data['id']; ?>" hidden>
                    <div class="form-group">
                      <label for="nama">Nama Orang Tua</label>
                      <input name="nama" value="<?= $data['nama']; ?>" id="nama" class="form-control" placeholder="Nama Orang Tua" required autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label for="nik">NIK</label>
                      <input name="nik" value="<?= $data['nik']; ?>" id="nik" class="form-control" placeholder="NIK" required autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label for="pw">Password</label>
                      <input name="pw" value="<?= $data['pw']; ?>" id="pw" class="form-control" placeholder="Password" required autocomplete="off">
                    </div>
                    <div class="form-group">
                      <label for="id_siswa">Data Siswa</label>
                      <select name="id_siswa" id="id_siswa" class="form-control js-siswa-select" data-placeholder="Cari siswa berdasarkan NIS / Nama" required>
                        <option value="">Pilih Siswa</option>
                        <?php foreach ($siswaList as $siswa) : ?>
                          <option value="<?= $siswa['nis']; ?>" <?= $siswa['nis'] == $data['nis'] ? 'selected' : ''; ?>><?= $siswa['nis']; ?> - <?= $siswa['nama']; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                      <a href="index.php" class="btn btn-sm btn-secondary"><i class="fa fa-reply"></i> Kembali</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>

        </div>
        <!-- /.container-fluid -->

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
  <script src="../../assets/vendor/jquery/jquery.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="../../assets/js/sb-admin-2.min.js"></script>
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

</body>

</html>
