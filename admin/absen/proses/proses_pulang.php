<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

// membuat variabel untuk menampung data dari form
$nis = $_POST['nis'];
$jam_pulang = $_POST['jam_pulang'];

// Mendapatkan tanggal saat ini
$tanggal_sekarang = date('Y-m-d');

// Mengecek apakah sudah ada data untuk NIS tersebut pada tanggal sekarang
$cek_query = "SELECT * FROM pulang WHERE nis = '$nis' AND DATE_FORMAT(tanggal, '%Y-%m-%d') = '$tanggal_sekarang'";
$cek_result = mysqli_query($koneksi, $cek_query);

if (mysqli_num_rows($cek_result) > 0) {
    // Jika sudah ada data, tampilkan pesan kesalahan
    echo "<script>alert('Anda sudah melakukan absen pulang hari ini.');window.location='../input_plg.php';</script>";
} else {
    // Jika belum ada data, jalankan query INSERT
    $query = "INSERT INTO pulang (nis, jam_pulang, tanggal) VALUES ('$nis','$jam_pulang', '$tanggal_sekarang')";
    $result = mysqli_query($koneksi, $query);

    // periksa query apakah ada error
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        // tampilkan alert dan redirect ke halaman index.php
       // echo "<script>alert('Anda Berhasil Absen Pulang.');window.location='../input_plg.php';</script>";
       header ("location:../input_plg.php");
    }
}
?>