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

$cekQuery = $koneksi->prepare("SELECT jam_pulang FROM absensi WHERE nis = ? AND tanggal = ?");
if (!$cekQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.'
    ]);
    exit;
}

$cekQuery->bind_param('ss', $nis, $tanggalSekarang);
$cekQuery->execute();
$cekQuery->bind_result($jamPulangTersimpan);

if ($cekQuery->fetch() && $jamPulangTersimpan !== null) {
    $cekQuery->close();
    echo json_encode([
        'success' => false,
        'message' => 'Anda sudah melakukan absen pulang hari ini.'
    ]);
    exit;
}

$cekQuery->close();

$updateQuery = $koneksi->prepare("UPDATE absensi SET jam_pulang = ? WHERE nis = ? AND tanggal = ?");
if (!$updateQuery) {
    echo json_encode([
        'success' => false,
        'message' => 'Gagal menyiapkan penyimpanan data.'
    ]);
    exit;
}

$updateQuery->bind_param('sss', $waktuSekarang, $nis, $tanggalSekarang);
$updateQuery->execute();

if ($updateQuery->affected_rows === 0) {
    $updateQuery->close();

    $insertQuery = $koneksi->prepare("INSERT INTO absensi (nis, tanggal, jam_pulang) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE jam_pulang = VALUES(jam_pulang)");
    if (!$insertQuery) {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan data absensi.'
        ]);
        exit;
    }

    $insertQuery->bind_param('sss', $nis, $tanggalSekarang, $waktuSekarang);

    if ($insertQuery->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Absen pulang berhasil disimpan.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan data absensi.'
        ]);
    }

    $insertQuery->close();
    $koneksi->close();
    exit;
}

$updateQuery->close();
$koneksi->close();

echo json_encode([
    'success' => true,
    'message' => 'Absen pulang berhasil disimpan.'
]);
