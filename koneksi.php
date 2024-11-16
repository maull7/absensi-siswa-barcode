<?php
$host = "localhost"; 
$user = "root";
$pass = "";
$nama_db = "absensi_1"; //nama database
$koneksi = mysqli_connect($host, $user, $pass, $nama_db); //pastikan urutan nya seperti ini, jangan tertukar

if (!$koneksi) { //jika tidak terkoneksi maka akan tampil error
    die("Koneksi dengan database gagal: " . mysqli_connect_error());
}
?>