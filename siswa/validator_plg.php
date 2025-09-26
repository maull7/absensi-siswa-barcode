<?php
session_start();

if (!isset($_SESSION['nis'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

require_once '../koneksi.php';
$barcodeConfig = require __DIR__ . '/../barcode_config.php';
$generalBarcodes = [
    'masuk' => is_array($barcodeConfig) && isset($barcodeConfig['masuk']) ? (string) $barcodeConfig['masuk'] : 'ABSENSI-MASUK',
    'pulang' => is_array($barcodeConfig) && isset($barcodeConfig['pulang']) ? (string) $barcodeConfig['pulang'] : 'ABSENSI-PULANG',
];

date_default_timezone_set('Asia/Jakarta');

$sessionNis = $_SESSION['nis'];
$scannedNis = $_POST['nis'] ?? '';
$captureTime = $_POST['time_val'] ?? '';
$captureDate = $_POST['date_val'] ?? '';
$mode = $_POST['mode'] ?? '';
$rawCode = trim($_POST['raw_code'] ?? $scannedNis);
$displayBarcode = $rawCode !== '' ? $rawCode : $scannedNis;
$latitudeInput = $_POST['latitude'] ?? '';
$longitudeInput = $_POST['longitude'] ?? '';

$latitudeValue = filter_var($latitudeInput, FILTER_VALIDATE_FLOAT);
$longitudeValue = filter_var($longitudeInput, FILTER_VALIDATE_FLOAT);
$latitudeFormatted = $latitudeValue !== false ? number_format($latitudeValue, 8, '.', '') : '';
$longitudeFormatted = $longitudeValue !== false ? number_format($longitudeValue, 8, '.', '') : '';

$validationMessage = 'Validasi Gagal';
$isValid = false;
$nama = '';
$kelas = '';

if ($scannedNis !== $sessionNis) {
    $validationMessage = 'Barcode tidak sesuai dengan akun yang sedang login.';
} elseif ($rawCode !== $sessionNis && (!isset($generalBarcodes[$mode]) || $rawCode !== $generalBarcodes[$mode])) {
    $validationMessage = 'Barcode tidak dikenali untuk mode absensi ini.';
} else {
    $stmt = $koneksi->prepare('SELECT nama, kelas FROM data_siswa WHERE nis = ?');

    if ($stmt) {
        $stmt->bind_param('s', $scannedNis);
        $stmt->execute();
        $stmt->bind_result($namaResult, $kelasResult);

        if ($stmt->fetch()) {
            $nama = $namaResult;
            $kelas = $kelasResult;
            $isValid = true;
            $validationMessage = 'Barcode berhasil divalidasi. Menyimpan absensi pulang...';

            $tanggalSekarang = date('Y-m-d');
            $cekAbsen = $koneksi->prepare('SELECT jam_pulang FROM pulang WHERE nis = ? AND DATE(tanggal) = ? ORDER BY jam_pulang DESC LIMIT 1');

            if ($cekAbsen) {
                $cekAbsen->bind_param('ss', $scannedNis, $tanggalSekarang);
                $cekAbsen->execute();
                $cekAbsen->bind_result($jamPulangTersimpan);

                if ($cekAbsen->fetch()) {
                    $isValid = false;
                    $jamTersimpan = '';
                    if (!empty($jamPulangTersimpan)) {
                        $parsedStoredTime = strtotime($jamPulangTersimpan);
                        if ($parsedStoredTime !== false) {
                            $jamTersimpan = date('H:i', $parsedStoredTime) . ' WIB';
                        }
                    }

                    $validationMessage = 'Anda sudah melakukan absen pulang hari ini.';
                    if ($jamTersimpan !== '') {
                        $validationMessage .= ' Catatan terakhir pada ' . $jamTersimpan . '.';
                    }
                }

                $cekAbsen->close();
            } else {
                $isValid = false;
                $validationMessage = 'Tidak dapat memeriksa status absensi saat ini. Silakan coba lagi.';
            }
        } else {
            $validationMessage = 'Data siswa tidak ditemukan.';
        }

        $stmt->close();
    } else {
        $validationMessage = 'Gagal menyiapkan validasi data.';
    }
}

$timeFormatted = '';
if (!empty($captureTime)) {
    $parsedTime = strtotime($captureTime);
    if ($parsedTime !== false) {
        $timeFormatted = date('H:i:s', $parsedTime);
    }
}

if ($timeFormatted === '') {
    $timeFormatted = date('H:i:s');
}

$dateFormatted = '';
if (!empty($captureDate)) {
    $parsedDate = strtotime($captureDate);
    if ($parsedDate !== false) {
        $dateFormatted = date('Y-m-d', $parsedDate);
    }
}

if ($dateFormatted === '') {
    $dateFormatted = date('Y-m-d');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Validasi Absen Pulang</title>
    <link rel="icon" href="../assets/img/smkmadya.png">
    <link href="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js" rel="preload" as="script">
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h5 class="m-0 font-weight-bold text-primary">Validasi Absen Pulang</h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="lead mb-4"><?= htmlspecialchars($validationMessage, ENT_QUOTES, 'UTF-8'); ?></p>
                        <?php if ($isValid): ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="w-50">Barcode</th>
                                            <td><?= htmlspecialchars($displayBarcode, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">NIS</th>
                                            <td><?= htmlspecialchars($scannedNis, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nama</th>
                                            <td><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Kelas</th>
                                            <td><?= htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tanggal</th>
                                            <td><?= htmlspecialchars($dateFormatted, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Jam</th>
                                            <td><?= htmlspecialchars($timeFormatted, ENT_QUOTES, 'UTF-8'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <form id="validator" method="post" action="proses_pulang.php">
                                <input type="hidden" name="nis" value="<?= htmlspecialchars($scannedNis, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="nama" value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="kelas" value="<?= htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="jam_pulang" value="<?= htmlspecialchars($timeFormatted, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="tanggal" value="<?= htmlspecialchars($dateFormatted, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="latitude" value="<?= htmlspecialchars($latitudeFormatted, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="longitude" value="<?= htmlspecialchars($longitudeFormatted, ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="mode" value="pulang">
                                <input type="submit" value="Kirim" style="display: none;">
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-center">
                        <?php if ($isValid): ?>
                            <small class="text-muted">Mengalihkan ke proses absensi...</small>
                        <?php else: ?>
                            <a href="index.php" class="btn btn-primary">Kembali ke halaman absensi</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            <?php if ($isValid): ?>
            Swal.fire({
                icon: 'success',
                title: 'Validasi Berhasil',
                text: <?php echo json_encode($validationMessage, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                willClose: () => {
                    const form = document.getElementById('validator');
                    if (form) {
                        form.submit();
                    }
                }
            });
            <?php else: ?>
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: <?php echo json_encode($validationMessage, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>,
                confirmButtonText: 'Kembali'
            }).then(() => {
                window.location.href = 'index.php';
            });
            <?php endif; ?>
        });
    </script>
</body>

</html>
