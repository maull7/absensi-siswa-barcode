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

$cekQuery = $koneksi->prepare("SELECT 1 FROM masuk WHERE nis = ? AND DATE(tanggal) = ?");
if (!$cekQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.'
    ]);
    exit;
}

$cekQuery->bind_param('ss', $nis, $tanggalSekarang);
$cekQuery->execute();
$cekQuery->store_result();

if ($cekQuery->num_rows > 0) {
    $cekQuery->close();
    echo json_encode([
        'success' => false,
        'message' => 'Anda sudah melakukan absen masuk hari ini.'
    ]);
    exit;
}

$cekQuery->close();

$insertQuery = $koneksi->prepare("INSERT INTO masuk (nis, jam_masuk, tanggal, status) VALUES (?, ?, ?, ?)");
if (!$insertQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyiapkan penyimpanan data.'
    ]);
    exit;
}

$insertQuery->bind_param('ssss', $nis, $waktuSekarang, $tanggalSekarang, $statusAbsen);

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
