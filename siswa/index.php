<?php
// Start the session
session_start();

if (!isset($_SESSION['nis'])) {
    header("Location: ../index.php");
    exit();
}

// Access the NIS from the session
$nis = $_SESSION['nis'];

$barcodeConfig = require __DIR__ . '/../barcode_config.php';
$generalBarcodes = [
    'masuk' => is_array($barcodeConfig) && isset($barcodeConfig['masuk']) ? (string) $barcodeConfig['masuk'] : 'ABSENSI-MASUK',
    'pulang' => is_array($barcodeConfig) && isset($barcodeConfig['pulang']) ? (string) $barcodeConfig['pulang'] : 'ABSENSI-PULANG',
];

$feedback = $_SESSION['absen_feedback'] ?? null;
$activeTab = 'masuk';
$feedbackMode = null;
$feedbackMessage = '';
$feedbackSuccess = null;

if ($feedback) {
    $feedbackMode = $feedback['mode'] ?? null;
    $feedbackMessage = $feedback['message'] ?? '';
    $feedbackSuccess = $feedback['success'] ?? false;
    if (!empty($feedbackMode)) {
        $activeTab = $feedbackMode;
    }
    unset($_SESSION['absen_feedback']);
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
                                <p class="mb-4">Silakan pilih jenis absensi kemudian arahkan barcode umum (Masuk/Pulang) atau barcode identitas Anda ke kamera.</p>
                                <ul class="nav nav-pills mb-3" id="absen-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link <?= $activeTab === 'masuk' ? 'active' : '' ?>" id="masuk-tab" data-toggle="pill" href="#absen-masuk" role="tab" aria-controls="absen-masuk" aria-selected="<?= $activeTab === 'masuk' ? 'true' : 'false' ?>">Absen Masuk</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link <?= $activeTab === 'pulang' ? 'active' : '' ?>" id="pulang-tab" data-toggle="pill" href="#absen-pulang" role="tab" aria-controls="absen-pulang" aria-selected="<?= $activeTab === 'pulang' ? 'true' : 'false' ?>">Absen Pulang</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="absen-tabContent">
                                    <div class="tab-pane fade <?= $activeTab === 'masuk' ? 'show active' : '' ?>" id="absen-masuk" role="tabpanel" aria-labelledby="masuk-tab">
                                        <div class="scanner-wrapper">
                                            <div id="reader-masuk" class="qr-reader"></div>
                                            <div id="result-masuk" class="result-card">
                                                <?php if ($feedbackMode === 'masuk' && $feedbackMessage !== ''): ?>
                                                    <div class="alert alert-<?= $feedbackSuccess ? 'success' : 'danger' ?>" role="alert">
                                                        <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade <?= $activeTab === 'pulang' ? 'show active' : '' ?>" id="absen-pulang" role="tabpanel" aria-labelledby="pulang-tab">
                                        <div class="scanner-wrapper">
                                            <div id="reader-pulang" class="qr-reader"></div>
                                            <div id="result-pulang" class="result-card">
                                                <?php if ($feedbackMode === 'pulang' && $feedbackMessage !== ''): ?>
                                                    <div class="alert alert-<?= $feedbackSuccess ? 'success' : 'danger' ?>" role="alert">
                                                        <?= htmlspecialchars($feedbackMessage, ENT_QUOTES, 'UTF-8'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
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
            const generalBarcodes = <?php echo json_encode($generalBarcodes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
            const defaultMode = "<?= $activeTab; ?>";
            const readerIds = { masuk: 'reader-masuk', pulang: 'reader-pulang' };
            const resultContainers = {};
            let activeScanner = null;
            let activeMode = null;

            const scannerConfig = {
                qrbox: {
                    width: 250,
                    height: 250,
                },
                fps: 15,
            };

            function stopScanner() {
                if (activeScanner) {
                    activeScanner.clear().catch(() => {
                        // abaikan error ketika berhenti memindai
                    });
                    activeScanner = null;
                }
                Object.values(readerIds).forEach((elementId) => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.innerHTML = '';
                    }
                });
            }

            function showMessage(mode, type, message) {
                const container = resultContainers[mode];
                if (!container) {
                    return;
                }
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                container.innerHTML = `
                    <div class="alert ${alertClass}" role="alert">
                        ${message}
                    </div>
                `;
            }

            function escapeHtml(value) {
                return String(value).replace(/[&<>"']/g, (match) => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                })[match]);
            }

            function buildCard(mode, nisValue, scannedText) {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');

                const today = `${year}-${month}-${day}`;
                const time = `${hours}:${minutes}:${seconds}`;
                const validatorEndpoint = mode === 'masuk' ? 'validator.php' : 'validator_plg.php';
                const sanitizedNis = escapeHtml(nisValue);
                const sanitizedScan = escapeHtml(scannedText);
                const usingGeneralBarcode = nisValue !== scannedText;

                return `
                    <div class="card" style="width: 18rem;">
                        <img src="../assets/images/barcode-scan.gif" class="card-img-top" alt="Animasi pemindaian barcode">
                        <div class="card-body">
                            <form id="form-${mode}" action="${validatorEndpoint}" method="post">
                                <p style="font-size: 14px;" class="card-text">Data Barcode : <span class="badge bg-primary">${sanitizedScan}</span></p>
                                <p style="font-size: 14px;" class="card-text">NIS Terkirim : <span class="badge bg-primary">${sanitizedNis}</span></p>
                                <p style="font-size: 14px;" class="card-text">Date : <span class="badge bg-primary">${today}</span></p>
                                <p style="font-size: 14px;" class="card-text">Capture Time : <span class="badge bg-primary">${time}</span></p>
                                <input type="hidden" name="nis" value="${sanitizedNis}">
                                <input type="hidden" name="raw_code" value="${sanitizedScan}">
                                <input type="hidden" name="time_val" value="${time}">
                                <input type="hidden" name="date_val" value="${today}">
                                <input type="hidden" name="mode" value="${mode}">
                                <input type="submit" value="Submit" style="display: none;">
                                ${usingGeneralBarcode ? '<p class="text-muted small mt-2 mb-0">Barcode umum terdeteksi. Sistem menggunakan NIS akun Anda.</p>' : ''}
                            </form>
                        </div>
                    </div>
                `;
            }

            function handleScanSuccess(decodedText, decodedResult, mode) {
                const container = resultContainers[mode];
                if (!container) {
                    return;
                }

                const trimmedText = String(decodedText).trim();
                const personalBarcode = trimmedText === sessionNis;
                const modeBarcode = typeof generalBarcodes[mode] === 'string' ? generalBarcodes[mode] : null;
                const otherMode = mode === 'masuk' ? 'pulang' : 'masuk';
                const otherModeBarcode = typeof generalBarcodes[otherMode] === 'string' ? generalBarcodes[otherMode] : null;
                const generalBarcode = modeBarcode !== null && trimmedText === modeBarcode;
                const otherGeneralBarcode = otherModeBarcode !== null && trimmedText === otherModeBarcode;

                if (!personalBarcode && !generalBarcode) {
                    if (otherGeneralBarcode) {
                        showMessage(mode, 'error', `Barcode ini digunakan untuk absen ${otherMode}. Silakan pindai pada tab ${otherMode}.`);
                    } else {
                        showMessage(mode, 'error', 'Barcode tidak dikenali. Gunakan barcode akun Anda atau barcode umum sesuai jenis absensi.');
                    }
                    return;
                }

                container.innerHTML = buildCard(mode, sessionNis, trimmedText);
                stopScanner();

                const form = document.getElementById(`form-${mode}`);
                if (form) {
                    form.submit();
                }
            }

            function handleScanError(errorMessage) {
                console.warn(errorMessage);
            }

            function startScanner(mode) {
                stopScanner();
                activeMode = mode;
                const elementId = readerIds[mode];
                const scanner = new Html5QrcodeScanner(elementId, scannerConfig, false);
                scanner.render((decodedText, decodedResult) => handleScanSuccess(decodedText, decodedResult, mode), handleScanError);
                activeScanner = scanner;
            }

            document.addEventListener('DOMContentLoaded', () => {
                resultContainers.masuk = document.getElementById('result-masuk');
                resultContainers.pulang = document.getElementById('result-pulang');

                startScanner(defaultMode === 'pulang' ? 'pulang' : 'masuk');

                $('#absen-tab a[data-toggle="pill"]').on('shown.bs.tab', (event) => {
                    const targetId = event.target.getAttribute('href');
                    const mode = targetId === '#absen-pulang' ? 'pulang' : 'masuk';
                    startScanner(mode);
                });

                $('#absen-tab a[data-toggle="pill"]').on('hide.bs.tab', () => {
                    stopScanner();
                });
            });
        </script>
</body>

</html>