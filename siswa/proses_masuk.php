<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['nis'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Sesi berakhir. Silakan login kembali.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Metode permintaan tidak valid.'
    ]);
    exit;
}

require_once '../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

$nisSession = $_SESSION['nis'];
$nis = $_POST['nis'] ?? '';

if ($nis !== $nisSession) {
    echo json_encode([
        'success' => false,
        'message' => 'Barcode tidak sesuai dengan akun yang sedang login.'
    ]);
    exit;
}

$tanggalSekarang = date('Y-m-d');
$waktuSekarang = date('H:i:s');
$namaHari = date('l', strtotime($tanggalSekarang));

$batasAbsen = '07:00:00';
if (in_array($namaHari, ['Tuesday', 'Wednesday', 'Thursday'], true)) {
    $batasAbsen = '07:10:00';
}

$statusAbsen = '';
if ($waktuSekarang > $batasAbsen) {
    $statusAbsen = 'Telat';
}

$cekQuery = $koneksi->prepare("SELECT jam_masuk FROM absensi WHERE nis = ? AND tanggal = ?");
if (!$cekQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.'
    ]);
    exit;
}

$cekQuery->bind_param('ss', $nis, $tanggalSekarang);
$cekQuery->execute();
$cekQuery->bind_result($jamMasukTersimpan);

if ($cekQuery->fetch() && $jamMasukTersimpan !== null) {
    $cekQuery->close();
    echo json_encode([
        'success' => false,
        'message' => 'Anda sudah melakukan absen masuk hari ini.'
    ]);
    exit;
}

$cekQuery->close();

$insertQuery = $koneksi->prepare("INSERT INTO absensi (nis, tanggal, jam_masuk, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE jam_masuk = VALUES(jam_masuk), status = VALUES(status)");
if (!$insertQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyiapkan penyimpanan data.'
    ]);
    exit;
}

$insertQuery->bind_param('ssss', $nis, $tanggalSekarang, $waktuSekarang, $statusAbsen);

if ($insertQuery->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Absen masuk berhasil disimpan.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyimpan data absensi.'
    ]);
}

$insertQuery->close();
$koneksi->close();
