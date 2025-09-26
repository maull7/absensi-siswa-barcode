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
    'mode' => 'pulang',
    'success' => false,
    'message' => 'Terjadi kesalahan. Silakan coba lagi.',
];

$nisSession = $_SESSION['nis'];
$nis = $_POST['nis'] ?? '';
$mode = $_POST['mode'] ?? '';
$capturedTime = $_POST['jam_pulang'] ?? '';
$status = '';
$latitudeInput = $_POST['latitude'] ?? null;
$longitudeInput = $_POST['longitude'] ?? null;

$cekQuery = null;
$insertQuery = null;

do {
    if ($mode !== 'pulang') {
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
    $jamPulang = $parsedTime !== false ? date('H:i:s', $parsedTime) : date('H:i:s');

    $tanggalSekarang = date('Y-m-d');

    $cekQuery = $koneksi->prepare('SELECT jam_pulang FROM pulang WHERE nis = ? AND DATE(tanggal) = ? ORDER BY jam_pulang DESC LIMIT 1');
    if (!$cekQuery) {
        $feedback['message'] = 'Terjadi kesalahan pada server.';
        break;
    }

    $cekQuery->bind_param('ss', $nis, $tanggalSekarang);
    $cekQuery->execute();
    $cekQuery->bind_result($jamPulangTersimpan);

    if ($cekQuery->fetch()) {
        $feedback['message'] = 'Anda sudah melakukan absen pulang hari ini.';
        if (!empty($jamPulangTersimpan)) {
            $parsedExisting = strtotime($jamPulangTersimpan);
            if ($parsedExisting !== false) {
                $feedback['message'] .= ' Catatan terakhir pada ' . date('H:i', $parsedExisting) . ' WIB.';
            }
        }
        break;
    }

    $cekQuery->close();
    $cekQuery = null;

    $insertQuery = $koneksi->prepare('INSERT INTO pulang (nis, jam_pulang, tanggal, status, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?)');
    if (!$insertQuery) {
        $feedback['message'] = 'Gagal menyiapkan penyimpanan data.';
        break;
    }

    $insertQuery->bind_param('ssssss', $nis, $jamPulang, $tanggalSekarang, $status, $latitudeFormatted, $longitudeFormatted);

    if (!$insertQuery->execute()) {
        $feedback['message'] = 'Gagal menyimpan data absensi.';
        break;
    }

    $feedback['success'] = true;
    $feedback['message'] = 'Absen pulang berhasil dicatat pada ' . date('H:i', strtotime($jamPulang)) . ' WIB.';
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
