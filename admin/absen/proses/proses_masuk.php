<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';
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

// Menampilkan hasil untuk debugging (bisa dihapus dalam implementasi produksi)
echo "Hari ini: $nama_hari\n";
echo "Waktu sekarang: $waktu_sekarang\n";
echo "Batas absen: $batas_absen\n";
echo "Status absen: $status_absen\n";

// Misalkan variabel $nis dan $jam_masuk sudah diambil dari input form sebelumnya
$nis = $_POST['nis'];
$jam_masuk = $_POST['jam_masuk'];

// Mengecek apakah sudah ada data untuk NIS tersebut pada tanggal sekarang
$cek_query = "SELECT * FROM masuk WHERE nis = '$nis' AND DATE_FORMAT(tanggal, '%Y-%m-%d') = '$tanggal_sekarang'";
$cek_result = mysqli_query($koneksi, $cek_query);

if (mysqli_num_rows($cek_result) > 0) {
    // Jika sudah ada data, tampilkan pesan kesalahan
    echo "<script>alert('Anda sudah melakukan absen hari ini.');window.location='../input.php';</script>";
} else {
    // Jika belum ada data, jalankan query INSERT
    $query = "INSERT INTO masuk (nis, jam_masuk, tanggal, status) VALUES ('$nis','$jam_masuk', '$tanggal_sekarang', '$status_absen')";
    $result = mysqli_query($koneksi, $query);

    // periksa query apakah ada error
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        // tampilkan alert dan redirect ke halaman input_plg.php
        header("location:../input.php");
    }
}

?>
