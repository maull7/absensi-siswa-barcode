<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../koneksi.php';

// membuat variabel untuk menampung data dari form
$nis = $_POST['nis'];
$jam_pulang = $_POST['jam_pulang'];

// Mendapatkan tanggal saat ini
$tanggal_sekarang = date('Y-m-d');

$cek_stmt = $koneksi->prepare("SELECT jam_pulang FROM absensi WHERE nis = ? AND tanggal = ?");

if ($cek_stmt) {
    $cek_stmt->bind_param('ss', $nis, $tanggal_sekarang);
    $cek_stmt->execute();
    $cek_stmt->bind_result($jamPulangTersimpan);

    if ($cek_stmt->fetch() && $jamPulangTersimpan !== null) {
        $cek_stmt->close();
        echo "<script>alert('Anda sudah melakukan absen pulang hari ini.');window.location='../input_plg.php';</script>";
        exit;
    }

    $cek_stmt->close();
}

$update_stmt = $koneksi->prepare("UPDATE absensi SET jam_pulang = ? WHERE nis = ? AND tanggal = ?");

if (!$update_stmt) {
    die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
}

$update_stmt->bind_param('sss', $jam_pulang, $nis, $tanggal_sekarang);
$update_stmt->execute();

if ($update_stmt->affected_rows === 0) {
    $update_stmt->close();

    $insert_stmt = $koneksi->prepare("INSERT INTO absensi (nis, tanggal, jam_pulang) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE jam_pulang = VALUES(jam_pulang)");
    if (!$insert_stmt) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    }

    $insert_stmt->bind_param('sss', $nis, $tanggal_sekarang, $jam_pulang);

    if (!$insert_stmt->execute()) {
        $insert_stmt->close();
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    }

    $insert_stmt->close();
} else {
    $update_stmt->close();
}

header("location:../input_plg.php");
exit;
?>
