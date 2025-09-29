<?php
include '../../../koneksi.php';

$nama = $_POST['nama'];
$nik = $_POST['nik'];
$pw = $_POST['pw'];
$id_siswa = $_POST['id_siswa'];

if ($nama != "" && $nik != "" && $pw != "" && $id_siswa != "") {
    $query = "INSERT INTO orang_tua (nama, nik, pw, nis) VALUES ('$nama', '$nik', '$pw', '$id_siswa')";
    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        die("Query gagal dijalankan: " . mysqli_errno($koneksi) . " - " . mysqli_error($koneksi));
    } else {
        echo "<script>alert('Data berhasil ditambah.');window.location='../index.php';</script>";
    }
} else {
    echo "<script>alert('Mohon lengkapi seluruh data.');window.location='../index.php';</script>";
}
