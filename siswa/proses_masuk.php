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

date_default_timezone_set('Asia/Jakarta');

$feedback = [
    'mode' => 'masuk',
    'success' => false,
    'message' => 'Terjadi kesalahan. Silakan coba lagi.',
];

$nisSession = $_SESSION['nis'];
$nis = $_POST['nis'] ?? '';
$mode = $_POST['mode'] ?? '';
$capturedTime = $_POST['jam_masuk'] ?? '';
$latitudeInput = $_POST['latitude'] ?? null;
$longitudeInput = $_POST['longitude'] ?? null;

$cekQuery = null;
$insertQuery = null;

do {
    if ($mode !== 'masuk') {
        $feedback['message'] = 'Permintaan absensi tidak valid.';
        break;
    }

    if ($nis !== $nisSession) {
        $feedback['message'] = 'Barcode tidak sesuai dengan akun yang sedang login.';
        break;
    }

    $latitudeValue = filter_var($latitudeInput, FILTER_VALIDATE_FLOAT);
    $longitudeValue = filter_var($longitudeInput, FILTER_VALIDATE_FLOAT);

    if ($latitudeValue === false || $longitudeValue === false) {
        $feedback['message'] = 'Lokasi tidak terdeteksi. Pastikan GPS aktif dan coba lagi.';
        break;
    }

    $latitudeFormatted = number_format($latitudeValue, 8, '.', '');
    $longitudeFormatted = number_format($longitudeValue, 8, '.', '');

    $parsedTime = $capturedTime !== '' ? strtotime($capturedTime) : false;
    $jamMasuk = $parsedTime !== false ? date('H:i:s', $parsedTime) : date('H:i:s');

    $tanggalSekarang = date('Y-m-d');
    $namaHari = date('l', strtotime($tanggalSekarang));
    $batasAbsen = in_array($namaHari, ['Tuesday', 'Wednesday', 'Thursday'], true) ? '07:10:00' : '07:00:00';
    $statusAbsen = $jamMasuk > $batasAbsen ? 'Telat' : '';

    $cekQuery = $koneksi->prepare('SELECT 1 FROM masuk WHERE nis = ? AND DATE(tanggal) = ?');
    if (!$cekQuery) {
        $feedback['message'] = 'Terjadi kesalahan pada server.';
        break;
    }

    $cekQuery->bind_param('ss', $nis, $tanggalSekarang);
    $cekQuery->execute();
    $cekQuery->store_result();

    if ($cekQuery->num_rows > 0) {
        $feedback['message'] = 'Anda sudah melakukan absen masuk hari ini.';
        break;
    }

    $insertQuery = $koneksi->prepare('INSERT INTO masuk (nis, jam_masuk, tanggal, status, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$insertQuery) {
        $feedback['message'] = 'Gagal menyiapkan penyimpanan data.';
        break;
    }

    $insertQuery->bind_param('ssssss', $nis, $jamMasuk, $tanggalSekarang, $statusAbsen, $latitudeFormatted, $longitudeFormatted);

    if (!$insertQuery->execute()) {
        $feedback['message'] = 'Gagal menyimpan data absensi.';
        break;
    }

    $feedback['success'] = true;
    $feedback['message'] = 'Absen masuk berhasil disimpan.';
} while (false);

if ($cekQuery instanceof mysqli_stmt) {
    $cekQuery->close();
}

if ($insertQuery instanceof mysqli_stmt) {
    $insertQuery->close();
}

$koneksi->close();

$_SESSION['absen_feedback'] = $feedback;
header('Location: index.php');
exit();
