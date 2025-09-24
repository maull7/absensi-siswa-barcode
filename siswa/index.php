<?php
// Start the session
session_start();

if (!isset($_SESSION['nis'])) {
    header("Location: ../index.php");
    exit();
}

// Access the NIS from the session
$nis = $_SESSION['nis'];

// Now you can use $nis wherever you need it
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Homepage</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../assets/img/smkmadya.png">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        font-family: 'Prompt', sans-serif;
    }

    .nav-pills .nav-link {
        border-radius: 10px;
        font-weight: 600;
    }

    .scanner-wrapper {
        max-width: 600px;
        margin: 0 auto;
    }

    .qr-reader {
        border-radius: 12px;
        overflow: hidden;
    }

    .result-card {
        margin-top: 1rem;
    }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div>
                    <img src="../assets/img/madep.png" alt="logo" width="45px">
                </div>

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link" href="../logout.php">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray"></i>
                    <span>Logout</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
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
                                <img class="img-profile rounded-circle" src="../assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a href="../logout.php" class="dropdown-item">
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
                    <br>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">

                    </div>
                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Absensi Mandiri</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-4">Silakan pilih jenis absensi kemudian arahkan barcode identitas Anda ke kamera.</p>
                                <ul class="nav nav-pills mb-3" id="absen-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="masuk-tab" data-toggle="pill" href="#absen-masuk" role="tab" aria-controls="absen-masuk" aria-selected="true">Absen Masuk</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pulang-tab" data-toggle="pill" href="#absen-pulang" role="tab" aria-controls="absen-pulang" aria-selected="false">Absen Pulang</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="absen-tabContent">
                                    <div class="tab-pane fade show active" id="absen-masuk" role="tabpanel" aria-labelledby="masuk-tab">
                                        <div class="scanner-wrapper">
                                            <div id="reader-masuk" class="qr-reader"></div>
                                            <div id="result-masuk" class="result-card"></div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="absen-pulang" role="tabpanel" aria-labelledby="pulang-tab">
                                        <div class="scanner-wrapper">
                                            <div id="reader-pulang" class="qr-reader"></div>
                                            <div id="result-pulang" class="result-card"></div>
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
        <script>
            const sessionNis = "<?php echo $nis; ?>";
            let activeScanner = null;
            let isProcessing = false;

            const scannerConfig = {
                qrbox: {
                    width: 250,
                    height: 250,
                },
                fps: 15,
            };

            const resultContainers = {
                masuk: document.getElementById('result-masuk'),
                pulang: document.getElementById('result-pulang')
            };

            function clearScanner() {
                if (activeScanner) {
                    activeScanner.clear().catch(() => {
                        // ignore errors when clearing
                    });
                    activeScanner = null;
                }
                document.querySelectorAll('.qr-reader').forEach((element) => {
                    element.innerHTML = '';
                });
            }

            function showMessage(mode, type, message) {
                const container = resultContainers[mode];
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                container.innerHTML = `
                    <div class="alert ${alertClass}" role="alert">
                        ${message}
                    </div>
                `;
            }

            function handleScanSuccess(decodedText, decodedResult, mode) {
                if (isProcessing) {
                    return;
                }

                if (decodedText !== sessionNis) {
                    showMessage(mode, 'error', 'Barcode tidak sesuai dengan akun yang sedang login. Silakan coba lagi.');
                    return;
                }

                const endpoint = mode === 'masuk' ? 'proses_masuk.php' : 'proses_pulang.php';
                const formData = new FormData();
                formData.append('nis', decodedText);

                isProcessing = true;

                fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then((response) => response.json())
                    .then((data) => {
                        const type = data.success ? 'success' : 'error';
                        showMessage(mode, type, data.message || 'Terjadi kesalahan.');

                        if (data.success) {
                            clearScanner();
                        }
                    })
                    .catch(() => {
                        showMessage(mode, 'error', 'Gagal mengirim data absensi. Silakan coba lagi.');
                    })
                    .finally(() => {
                        isProcessing = false;
                    });
            }

            function handleScanError(errorMessage) {
                console.warn(errorMessage);
            }

            function startScanner(mode) {
                clearScanner();
                const elementId = mode === 'masuk' ? 'reader-masuk' : 'reader-pulang';
                const scanner = new Html5QrcodeScanner(elementId, scannerConfig, false);
                scanner.render((decodedText, decodedResult) => handleScanSuccess(decodedText, decodedResult, mode), handleScanError);
                activeScanner = scanner;
                resultContainers[mode].innerHTML = '';
                isProcessing = false;
            }

            $('#masuk-tab').on('shown.bs.tab', () => {
                startScanner('masuk');
            });

            $('#pulang-tab').on('shown.bs.tab', () => {
                startScanner('pulang');
            });

            // Start default scanner
            startScanner('masuk');
        </script>
</body>

</html>