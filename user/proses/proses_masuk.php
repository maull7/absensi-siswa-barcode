<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

// membuat variabel untuk menampung data dari form
// Mendapatkan tanggal dan waktu sekarang
$tanggal_sekarang = date('Y-m-d');
$waktu_sekarang = date('H:i:s');

// Mendapatkan nama hari dari tanggal sekarang
$nama_hari = date('l', strtotime($tanggal_sekarang));

// Menentukan batas waktu absen untuk setiap hari
$batas_absen = '07:00:00'; // Default untuk Senin dan Jumat

if ($nama_hari == 'Tuesday' || $nama_hari == 'Wednesday' || $nama_hari == 'Thursday') {
    $batas_absen = '07:10:00';
}

// Menentukan status absen
$status_absen = '';

// Cek apakah waktu sekarang melebihi batas waktu absen
if ($waktu_sekarang > $batas_absen) {
    $status_absen = 'Telat';
}

// Misalkan variabel $nis dan $jam_masuk sudah diambil dari input form sebelumnya
$nis = $_POST['nis'];
$jam_masuk = $_POST['jam_masuk'];

// Mengecek apakah sudah ada data untuk NIS tersebut pada tanggal sekarang
$cek_stmt = $koneksi->prepare("SELECT jam_masuk FROM absensi WHERE nis = ? AND tanggal = ?");

if ($cek_stmt) {
    $cek_stmt->bind_param('ss', $nis, $tanggal_sekarang);
    $cek_stmt->execute();
    $cek_stmt->bind_result($jamMasukTersimpan);

    if ($cek_stmt->fetch() && $jamMasukTersimpan !== null) {
        $cek_stmt->close();
        echo "<script>alert('Anda sudah melakukan absen hari ini.');window.location='../input.php';</script>";
        exit;
    }

    $cek_stmt->close();
}

$query = "INSERT INTO absensi (nis, tanggal, jam_masuk, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE jam_masuk = VALUES(jam_masuk), status = VALUES(status)";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
}

$stmt->bind_param('ssss', $nis, $tanggal_sekarang, $jam_masuk, $status_absen);

if (!$stmt->execute()) {
    $stmt->close();
    die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
}

$stmt->close();
header("location:../index.php");
exit;

?>
