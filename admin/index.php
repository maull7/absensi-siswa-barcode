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
        <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
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

                    // Ambil tanggal dari input form, default ke hari ini jika tidak ada input
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');

// Escape input untuk mencegah SQL injection
$tanggal = mysqli_real_escape_string($koneksi, $tanggal);

// Inisialisasi variabel
$c = 0;

// Query untuk menghitung jumlah absen berdasarkan tanggal
$query = "SELECT COUNT(nis) AS am FROM masuk WHERE tanggal = '$tanggal' AND status =''";
$sql = mysqli_query($koneksi, $query);

// Cek apakah query mengembalikan hasil
if ($sql && mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);
    $c = $data['am'];  // Ambil jumlah absen
}

                


                    $d = 0;
                    $date = date('Y-m-d');
                    $query  = "SELECT count(id) AS ap FROM pulang WHERE tanggal ='$date' ";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $d  = $data['ap'];
                    }

                   $e = 0;
                    $query  = "SELECT count(nis) AS si FROM masuk WHERE status='Izin' AND tanggal = '$tanggal'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $e  = $data['si'];
                    }
                
                    $f = 0;
                    $query  = "SELECT count(id) AS ss FROM masuk WHERE status='Sakit' AND tanggal = '$tanggal'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $f  = $data['ss'];
                    }

                    $g = 0;
                    $query  = "SELECT count(nis) AS sa FROM masuk WHERE status='Alpha' AND tanggal = '$tanggal'";
                    $sql    = mysqli_query($koneksi, $query);
                    if(mysqli_num_rows($sql)>0){
                    $data = mysqli_fetch_assoc($sql);
                    $g  = $data['sa'];
                    }

                    $statusCounts = [
                        'Hadir' => 0,
                        'Izin' => 0,
                        'Sakit' => 0,
                        'Alpha' => 0,
                    ];
                    $statusTambahan = [];

                    $statusQuery = mysqli_query($koneksi, "SELECT IF(status IS NULL OR status = '', 'Hadir', status) AS status_label, COUNT(*) AS total FROM masuk WHERE tanggal = '$tanggal' GROUP BY status_label");
                    if ($statusQuery) {
                        while ($rowStatus = mysqli_fetch_assoc($statusQuery)) {
                            $labelStatus = $rowStatus['status_label'] ?? '';
                            $jumlahStatus = (int) ($rowStatus['total'] ?? 0);
                            if (array_key_exists($labelStatus, $statusCounts)) {
                                $statusCounts[$labelStatus] = $jumlahStatus;
                            } else {
                                $statusTambahan[$labelStatus] = $jumlahStatus;
                            }
                        }
                    }

                    $statusDistributionSeries = [];
                    foreach ($statusCounts as $labelStatus => $jumlahStatus) {
                        $statusDistributionSeries[] = [
                            'name' => $labelStatus,
                            'y' => $jumlahStatus
                        ];
                    }
                    foreach ($statusTambahan as $labelStatus => $jumlahStatus) {
                        $statusDistributionSeries[] = [
                            'name' => $labelStatus,
                            'y' => $jumlahStatus
                        ];
                    }

                    $totalMasukHarian = 0;
                    $totalMasukQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM masuk WHERE tanggal = '$tanggal'");
                    if ($totalMasukQuery && mysqli_num_rows($totalMasukQuery) > 0) {
                        $rowMasuk = mysqli_fetch_assoc($totalMasukQuery);
                        $totalMasukHarian = (int) ($rowMasuk['total'] ?? 0);
                    }

                    $totalPulangHarian = 0;
                    $totalPulangQuery = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pulang WHERE tanggal = '$tanggal'");
                    if ($totalPulangQuery && mysqli_num_rows($totalPulangQuery) > 0) {
                        $rowPulang = mysqli_fetch_assoc($totalPulangQuery);
                        $totalPulangHarian = (int) ($rowPulang['total'] ?? 0);
                    }

                    $belumPulangHarian = max($totalMasukHarian - $totalPulangHarian, 0);

                    $today = new DateTime();

                    // Mengatur tanggal akhir ke hari Jumat
                    $daysUntilFriday = (5 - $today->format('N') + 7) % 7; // Menghitung selisih hari hingga Jumat
                    $fridayDate = clone $today; // Membuat salinan dari tanggal hari ini
                    $fridayDate->modify("+$daysUntilFriday days"); // Menambahkan selisih hari hingga Jumat
    
                    $startDate = $today->format('Y-m-d'); // Tanggal awal adalah hari ini
                    $endDate = $fridayDate->format('Y-m-d'); // Tanggal akhir adalah hari Jumat
    
                    // Cek apakah form sudah disubmit dan apakah ada nilai input
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $star = $_POST['start_date']; // Tanggal awal dari input pengguna
                        $end = $_POST['end_date']; // Tanggal akhir dari input pengguna
    
                        // Gunakan tanggal dari input pengguna jika tersedia
                        $startDate = $star;
                        $endDate = $end;
                    }
    
                    // Query untuk menghitung jumlah ketidakhadiran per hari dalam minggu yang dipilih
                    $queryAll = "SELECT 
                        COUNT(CASE WHEN DAYOFWEEK(tanggal) = 2 THEN status END) AS Senin,
                        COUNT(CASE WHEN DAYOFWEEK(tanggal) = 3 THEN status END) AS Selasa,
                        COUNT(CASE WHEN DAYOFWEEK(tanggal) = 4 THEN status END) AS Rabu,
                        COUNT(CASE WHEN DAYOFWEEK(tanggal) = 5 THEN status END) AS Kamis,
                        COUNT(CASE WHEN DAYOFWEEK(tanggal) = 6 THEN status END) AS Jumat
                    FROM masuk
                    WHERE status IN ('Alpha', 'Izin', 'Sakit') 
                    AND tanggal BETWEEN '$startDate' AND '$endDate';";
    
                    $resultAll = mysqli_query($koneksi, $queryAll);
    
                    if ($resultAll) {
                        $data = mysqli_fetch_assoc($resultAll);
                        $senin = $data['Senin'] ?? 0; // Gunakan 0 jika tidak ada data
                        $selasa = $data['Selasa'] ?? 0; // Gunakan 0 jika tidak ada data
                        $rabu = $data['Rabu'] ?? 0; // Gunakan 0 jika tidak ada data
                        $kamis = $data['Kamis'] ?? 0; // Gunakan 0 jika tidak ada data
                        $jumat = $data['Jumat'] ?? 0; // Gunakan 0 jika tidak ada data
                    } else {
                        echo "Query gagal: " . mysqli_error($koneksi);
                    }
    
                    // Mengatur tanggal awal dan akhir
                    $today = new DateTime();
                    $daysUntilFriday = (5 - $today->format('N') + 7) % 7;
                    $fridayDate = clone $today;
                    $fridayDate->modify("+$daysUntilFriday days");
    
                    $startDate = $today->format('Y-m-d');
                    $endDate = $fridayDate->format('Y-m-d');
    
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $star = $_POST['start_date'];
                        $end = $_POST['end_date'];
    
                        // Menggunakan tanggal dari input pengguna jika tersedia
                        $startDate = $star;
                        $endDate = $end;
                    }
    
                    $alasan = "WITH DailyStatusCount AS (
        SELECT 
            tanggal, 
            status, 
            COUNT(*) AS jumlah,
            ROW_NUMBER() OVER (PARTITION BY tanggal ORDER BY COUNT(*) DESC) AS rn
        FROM masuk
        WHERE DAYOFWEEK(tanggal) BETWEEN 2 AND 6
        AND tanggal BETWEEN '$startDate' AND '$endDate'  -- Tambahkan filter berdasarkan tanggal
        GROUP BY tanggal, status
    )
    SELECT 
        CASE DAYOFWEEK(tanggal)
            WHEN 2 THEN 'Senin'
            WHEN 3 THEN 'Selasa'
            WHEN 4 THEN 'Rabu'
            WHEN 5 THEN 'Kamis'
            WHEN 6 THEN 'Jumat'
        END AS nama_hari, 
        status,
        jumlah
    FROM DailyStatusCount
    WHERE rn = 1
    ORDER BY tanggal;";
    
                    $hsn = mysqli_query($koneksi, $alasan);
                    if ($hsn) {
                        // Inisialisasi semua variabel status
                        $statusSenin = $statusSelasa = $statusRabu = $statusKamis = $statusJumat = null;
                        $js = $jss = $jr = $jk = $jj = null;
    
                        while ($as = mysqli_fetch_assoc($hsn)) {
                            $nama_hari = $as['nama_hari'];
                            $status = $as['status'];
                            $jumlah = $as['jumlah'];
    
                            switch ($nama_hari) {
                                case "Senin":
                                    $statusSenin = $status;
                                    $js = $jumlah;
                                    break;
                                case "Selasa":
                                    $statusSelasa = $status;
                                    $jss = $jumlah;
                                    break;
                                case "Rabu":
                                    $statusRabu = $status;
                                    $jr = $jumlah;
                                    break;
                                case "Kamis":
                                    $statusKamis = $status;
                                    $jk = $jumlah;
                                    break;
                                case "Jumat":
                                    $statusJumat = $status;
                                    $jj = $jumlah;
                                    break;
                            }
                        }
                    } else {
                        echo "Query gagal: " . mysqli_error($koneksi);
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
    <div class="card border-left-success shadow h-100">
        <div class="card-body">
            <!-- Form Pilih Tanggal -->
            <form method="GET" class="mb-3" action="">
                <div class="form-group d-flex align-items-center">
                    <label for="tanggal" class="mr-2">Pilih Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" 
                           class="form-control mr-2" 
                           value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d'); ?>">
                    <button type="submit" class="btn btn-success">Cari</button>
                </div>
            </form>

            <!-- Absen Masuk Info -->
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xl font-weight-bold text-success text-uppercase mb-1">
                        Absen Masuk
                    </div>
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

                            <div class="col-12">
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 mb-4">
                                        <div class="card shadow h-100">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Distribusi Status Absen Masuk (<?= htmlspecialchars(date('d F Y', strtotime($tanggal)), ENT_QUOTES, 'UTF-8'); ?>)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="statusMasukChart" style="height: 320px;"></div>
                                                <p class="mt-3 text-xs text-muted mb-0">Absen masuk menandakan siswa hadir. Status izin, sakit, atau alpha tercatat ketika siswa tidak hadir di sekolah.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 mb-4">
                                        <div class="card shadow h-100">
                                            <div class="card-header py-3">
                                                <h6 class="m-0 font-weight-bold text-primary">Perbandingan Absen Masuk &amp; Pulang (<?= htmlspecialchars(date('d F Y', strtotime($tanggal)), ENT_QUOTES, 'UTF-8'); ?>)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div id="perbandinganMasukPulangChart" style="height: 320px;"></div>
                                                <p class="mt-3 text-xs text-muted mb-0">Absen pulang bersifat opsional sehingga jumlahnya sering kali lebih sedikit daripada absen masuk.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <figure class="highcharts-figure">
                                <div id="container"></div>
                                <p class="highcharts-description text-center">
                                    Data data siswa tidak masuk dalam mingguan
                                </p>
                            </figure>
                            <div class="d-flex justify-content-center">
                                <form method="POST" action="">
                                    <label for="start_date">Tanggal Awal:</label>
                                    <input type="date" id="start_date" name="start_date" required>

                                    <label for="end_date">Tanggal Akhir:</label>
                                    <input type="date" id="end_date" name="end_date" required>

                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </form>

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
<style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 310px;
        max-width: 1000px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }
</style>

<?php
    $statusDistributionJson = json_encode($statusDistributionSeries, JSON_UNESCAPED_UNICODE);
    $kehadiranComparisonData = [
        'masuk' => $totalMasukHarian,
        'pulang' => $totalPulangHarian,
        'belum' => $belumPulangHarian,
    ];
    $kehadiranComparisonJson = json_encode($kehadiranComparisonData, JSON_UNESCAPED_UNICODE);
?>

<script>
    // Pastikan variabel-variabel ini terdefinisi
    const statusDistributionSeries = <?php echo $statusDistributionJson; ?>;
    const kehadiranComparison = <?php echo $kehadiranComparisonJson; ?>;
    const senin = <?php echo json_encode($senin); ?>; // Contoh nilai
    const selasa = <?php echo json_encode($selasa); ?>; // Contoh nilai
    const rabu = <?php echo json_encode($rabu); ?>; // Contoh nilai
    const kamis = <?php echo json_encode($kamis); ?>; // Contoh nilai
    const jumat = <?php echo json_encode($jumat); ?>; // Contoh nilai

    let ss = <?php echo json_encode($statusSenin); ?>;
    let sl = <?php echo json_encode($statusSelasa); ?>;
    let sr = <?php echo json_encode($statusRabu); ?>;
    let sk = <?php echo json_encode($statusKamis); ?>;
    let sj = <?php echo json_encode($statusJumat); ?>;

    let js = <?php echo json_encode($js); ?>;
    let jsl = <?php echo json_encode($jss); ?>;
    let jr = <?php echo json_encode($jr); ?>;
    let jk = <?php echo json_encode($jk); ?>;
    let jj = <?php echo json_encode($jj); ?>;

    // Membuat chart saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        Highcharts.chart('statusMasukChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: 'Distribusi Status Absen Masuk'
            },
            tooltip: {
                pointFormat: '<b>{point.y} siswa</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: ' siswa'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Jumlah',
                colorByPoint: true,
                data: statusDistributionSeries
            }]
        });

        Highcharts.chart('perbandinganMasukPulangChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Perbandingan Absen Masuk & Pulang'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Jumlah Siswa'
                },
                allowDecimals: false
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: '<b>{point.y} siswa</b>'
            },
            series: [{
                name: 'Jumlah',
                colorByPoint: true,
                data: [
                    { name: 'Absen Masuk', y: parseInt(kehadiranComparison.masuk || 0, 10), color: '#1cc88a' },
                    { name: 'Absen Pulang', y: parseInt(kehadiranComparison.pulang || 0, 10), color: '#e74a3b' },
                    { name: 'Belum Pulang', y: parseInt(kehadiranComparison.belum || 0, 10), color: '#36b9cc' }
                ]
            }]
        });

        Highcharts.chart('container', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Data Absensi Siswa Harian'
            },
            subtitle: {
                text: 'Sumber: SMK MADYA DEPOK'
            },
            xAxis: {
                categories: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
                accessibility: {
                    description: 'Hari-hari dalam seminggu'
                }
            },
            yAxis: {
                title: {
                    text: 'Jumlah Siswa Tidak Hadir'
                },
                labels: {
                    format: '{value} siswa'
                }
            },
            tooltip: {
                crosshairs: true,
                shared: true,
                formatter: function() {
                    let s = '<b>' + this.x + '</b><br />';
                    this.points.forEach(point => {
                        s += point.series.name + ': ' + point.y + ' siswa<br />';
                        if (point.point.alasan) {
                            s += 'Rata-rata alasan: ' + point.point.alasan + '<br />';
                            s += 'Jumlah ' + point.point.alasan + ': ' + point.point.jumlah;
                        }
                    });
                    return s;
                }
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                name: 'Jumlah Tidak Hadir',
                marker: {
                    symbol: 'circle'
                },
                data: [{
                        y: parseInt(senin) || 0, // Menggunakan 0 jika tidak ada nilai
                        alasan: ss,
                        jumlah: parseInt(js) || 0
                    },
                    {
                        y: parseInt(selasa) || 0,
                        alasan: sl,
                        jumlah: parseInt(jsl) || 0
                    },
                    {
                        y: parseInt(rabu) || 0,
                        alasan: sr,
                        jumlah: parseInt(jr) || 0
                    },
                    {
                        y: parseInt(kamis) || 0,
                        alasan: sk,
                        jumlah: parseInt(jk) || 0
                    },
                    {
                        y: parseInt(jumat) || 0,
                        alasan: sj,
                        jumlah: parseInt(jj) || 0
                    }
                ]
            }],
        });
    });
</script>