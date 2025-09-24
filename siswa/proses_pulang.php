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

    $parsedTime = $capturedTime !== '' ? strtotime($capturedTime) : false;
    $jamPulang = $parsedTime !== false ? date('H:i:s', $parsedTime) : date('H:i:s');

    $tanggalSekarang = date('Y-m-d');

    $cekQuery = $koneksi->prepare('SELECT 1 FROM pulang WHERE nis = ? AND DATE(tanggal) = ?');
    if (!$cekQuery) {
        $feedback['message'] = 'Terjadi kesalahan pada server.';
        break;
    }

    $cekQuery->bind_param('ss', $nis, $tanggalSekarang);
    $cekQuery->execute();
    $cekQuery->store_result();

    if ($cekQuery->num_rows > 0) {
        $feedback['message'] = 'Anda sudah melakukan absen pulang hari ini.';
        break;
    }

    $insertQuery = $koneksi->prepare('INSERT INTO pulang (nis, jam_pulang, tanggal) VALUES (?, ?, ?)');
    if (!$insertQuery) {
        $feedback['message'] = 'Gagal menyiapkan penyimpanan data.';
        break;
    }

    $insertQuery->bind_param('sss', $nis, $jamPulang, $tanggalSekarang);

    if (!$insertQuery->execute()) {
        $feedback['message'] = 'Gagal menyimpan data absensi.';
        break;
    }

    $feedback['success'] = true;
    $feedback['message'] = 'Absen pulang berhasil disimpan.';
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
