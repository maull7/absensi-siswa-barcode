<?php
include('../../koneksi.php');
$result = mysqli_query($koneksi, "SELECT * FROM data_siswa");
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
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

    <title>Kelola Data Siswa</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../../assets/img/smkmadya.png">
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        main {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
        }

        #reader {
            width: 600px;
            border-radius: 30px;
        }

        #result {
            text-align: center;
            font-size: 1.5rem;
        }

        .hidden {
            display: none;
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
                <li class="nav-item active">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data" aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-receipt"></i>
                        <span>Absensi</span>
                    </a>
                    <div id="data" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item " href="input.php">Masuk</a>
                            <a class="collapse-item active" href="input_plg.php">Pulang</a>
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
                            <li>
                                <h4>
                                    <div class="date">
                                        <script type='text/javascript'>
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
                                        </script>
                                    </div>
                                </h4>
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    </div>

                    <main>
                        <div id="reader" class="rounded"></div>
                        <div id="result"></div>
                    </main>

                    <!-- Tombol dan Form Input NIS -->
                    <div class="container mt-4">
                        <center> <button class="btn btn-primary" id="nisButton">Input NIS</button></center>
                        <center>
                            <div id="nisForm" class="mt-3 hidden">
                                <form id="nisFormSubmit" action="validator.php" method="post">
                                    <div class="mb-3">
                                        <label for="nisInput" class="form-label">Masukkan NIS</label>
                                        <input type="text" class="form-control" id="nisInput" name="nis" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="submitNIS">Submit</button>
                                </form>
                            </div>
                        </center>
                    </div>

                    <!-- JavaScript untuk menampilkan/menyembunyikan form NIS -->
                    <script>
                        document.getElementById('nisButton').addEventListener('click', function() {
                            const form = document.getElementById('nisForm');
                            if (form.classList.contains('hidden')) {
                                form.classList.remove('hidden');
                            } else {
                                form.classList.add('hidden');
                            }
                        });

                        const scanner = new Html5QrcodeScanner('reader', {
                    // Scanner will be initialized in DOM inside element with id of 'reader'
                    qrbox: {
                        width: 250,
                        height: 250,
                    }, // Sets dimensions of scanning box (set relative to reader element width)
                    fps: 20, // Frames per second to attempt a scan
                });


                scanner.render(success, error);
                // Starts scanner

                function success(result) {

                    let today = new Date().toISOString().slice(0, 10)

                    const date_obj = new Date();

                    time = new Date().toLocaleTimeString();

                    document.getElementById('result').innerHTML = `

        <div class="card" style="width: 18rem; onload="console_log(${result});">

        <img src="../../assets/images/barcode-scan.gif" class="card-img-top" alt="...">

        <div class="card-body">

        <form id="input" action="validator_plg.php" method="post">

        <p style="font-size: 14px;" class="card-text">Bar Code Read Successfully : <span class="badge bg-primary">${result}</span></p>

        <p style="font-size: 14px;" class="card-text">Date : <span class="badge bg-primary">${today}</span></p>

        <p style="font-size: 14px;" class="card-text">Capture Time : <span class="badge bg-primary">${time}</span></p>

        <input type="hidden" name="nis" value="${result}" id="result">

        <input type="hidden" name="time_val" value="${time}" id="capture_time">

        <input type="hidden" name="date_val" value="${today}" id="capture_date">

        <input type="submit" value="Submit" style="display: none;">

        </form>

        </div>

        </div>
        `;
                    // Simpan skrip berikut di bagian bawah body atau gunakan window.onload untuk memastikan formulir telah dimuat
                    document.getElementById('input').submit();

                    // Prints result as a link inside result element

                    scanner.clear();
                    // Clears scanning instance

                    document.getElementById('reader').remove();
                    // Removes reader element from DOM since no longer needed

                }

                function error(err) {
                    console.error(err);
                    // Prints any errors to the console
                }
                document.getElementById('submitNIS').addEventListener('click', function(event) {
                            event.preventDefault(); // Menghentikan pengiriman formulir secara otomatis

                            // Mendapatkan nilai NIS yang dimasukkan oleh pengguna
                            const nisValue = document.getElementById('nisInput').value;

                            // Memanggil fungsi success dengan nilai NIS
                            success(nisValue);
                        });

                function console_log(result) {
                    console.log(result);
                }
            </script>


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

                    <!-- Page level custom scripts -->
                    <script src="../../assets/js/demo/datatables-demo.js"></script>

</body>

</html>