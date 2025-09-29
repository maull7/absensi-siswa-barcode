<?php
include '../../../koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$nik = $_POST['nik'];
$pw = $_POST['pw'];
$id_siswa = $_POST['id_siswa'];

$query = "UPDATE orang_tua SET nama = '$nama', nik = '$nik', pw = '$pw', nis = '$id_siswa' WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
} else {
    echo "<script>alert('Data berhasil diubah.');window.location='../index.php';</script>";
}
