<?php
include('../../koneksi.php');
session_start();
if (!isset($_SESSION['sebagai'])) {
    header('Location: ../../index.php');
    exit();
}

if ($_SESSION['sebagai'] === 'user') {
    header('Location: ../../index.php');
    exit();
}

$barcodeConfig = require __DIR__ . '/../../barcode_config.php';
$generalBarcodes = [
    'masuk' => is_array($barcodeConfig) && isset($barcodeConfig['masuk']) ? (string) $barcodeConfig['masuk'] : 'ABSENSI-MASUK',
    'pulang' => is_array($barcodeConfig) && isset($barcodeConfig['pulang']) ? (string) $barcodeConfig['pulang'] : 'ABSENSI-PULANG',
];
$barcodeLabels = [
    'masuk' => 'Absen Masuk',
    'pulang' => 'Absen Pulang',
];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Generate Barcode Umum</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="../../assets/img/smkmadya.png">
    <link href="../../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .barcode-wrapper {
            min-height: 240px;
            display: flex;
            align-items: center;
            justify-content: center;
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
                            <a class="collapse-item" href="input.php">Masuk</a>
                            <a class="collapse-item" href="input_plg.php">Pulang</a>
                            <a class="collapse-item active" href="barcode_umum.php">Barcode Masuk/Pulang</a>
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
                            <a class="collapse-item" href="data_absen.php">Data Tidak Hadir</a>
                            <a class="collapse-item" href="data_masuk.php">Data Absen Masuk</a>
                            <a class="collapse-item" href="data_pulang.php">Data Absen Pulang</a>
                        </div>
                    </div>
                </li>


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
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <div class="text-center d-none d-md-inline">
                        <a class="btn" id="sidebarToggle"><i class="fas fa-bars"></i></a>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') : 'Admin'; ?></span>
                                <img class="img-profile rounded-circle" src="../../assets/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="../../logout.php">
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

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Barcode Umum Absensi</h1>
                    </div>

                    <p class="mb-4">Gunakan barcode berikut untuk proses absensi umum. Siswa dapat memindai barcode berdasarkan jenis absensi Masuk atau Pulang tanpa perlu barcode khusus masing-masing.</p>

                    <div class="row">
                        <?php foreach ($generalBarcodes as $mode => $code): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow h-100">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">Barcode <?= htmlspecialchars($barcodeLabels[$mode], ENT_QUOTES, 'UTF-8'); ?></h6>
                                        <span class="badge bg-light text-dark">Mode: <?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="mb-2">Data yang dikodekan:</p>
                                        <p><code><?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8'); ?></code></p>
                                        <div id="barcode-<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>" class="barcode-wrapper mb-3"></div>
                                        <div class="btn-group" role="group" aria-label="Aksi barcode">
                                            <button type="button" class="btn btn-primary download-btn" data-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">Unduh PNG</button>
                                            <button type="button" class="btn btn-outline-secondary copy-btn" data-mode="<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">Salin Teks</button>
                                        </div>
                                        <p class="text-muted small mt-3">Barcode umum <?= htmlspecialchars(strtolower($barcodeLabels[$mode]), ENT_QUOTES, 'UTF-8'); ?> ini dapat ditempatkan di area strategis untuk dipindai siswa.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../assets/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const barcodeValues = <?php echo json_encode($generalBarcodes, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        function renderBarcode(mode, value) {
            const container = document.getElementById(`barcode-${mode}`);
            if (!container) {
                return;
            }
            container.innerHTML = '';
            new QRCode(container, {
                text: value,
                width: 220,
                height: 220,
                correctLevel: QRCode.CorrectLevel.H,
            });
        }

        function getBarcodeDataUrl(container) {
            const canvas = container.querySelector('canvas');
            if (canvas) {
                return canvas.toDataURL('image/png');
            }
            const img = container.querySelector('img');
            if (img) {
                return img.src;
            }
            return null;
        }

        Object.keys(barcodeValues).forEach((mode) => {
            renderBarcode(mode, barcodeValues[mode]);
        });

        document.querySelectorAll('.download-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const mode = button.getAttribute('data-mode');
                const container = document.getElementById(`barcode-${mode}`);
                if (!container) {
                    return;
                }
                const dataUrl = getBarcodeDataUrl(container);
                if (!dataUrl) {
                    alert('Gagal menyiapkan gambar barcode.');
                    return;
                }
                const link = document.createElement('a');
                link.href = dataUrl;
                link.download = `barcode-${mode}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });

        document.querySelectorAll('.copy-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const mode = button.getAttribute('data-mode');
                const value = barcodeValues[mode];
                if (!navigator.clipboard) {
                    const textarea = document.createElement('textarea');
                    textarea.value = value;
                    document.body.appendChild(textarea);
                    textarea.select();
                    try {
                        document.execCommand('copy');
                        alert('Teks barcode tersalin.');
                    } catch (err) {
                        alert('Gagal menyalin teks barcode.');
                    }
                    document.body.removeChild(textarea);
                    return;
                }
                navigator.clipboard.writeText(value)
                    .then(() => {
                        alert('Teks barcode tersalin.');
                    })
                    .catch(() => {
                        alert('Gagal menyalin teks barcode.');
                    });
            });
        });
    </script>
</body>

</html>
