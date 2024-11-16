<?php
include "../../koneksi.php";
$koneksi = mysqli_connect("localhost","root", "", "absensiqr"); //pastikan urutan nya seperti ini, jangan tertukar

$nis = $_GET['nis'];

$query = mysqli_query($koneksi, "SELECT * FROM data_siswa WHERE nis = '$nis'");
$sw = mysqli_fetch_array($query);
$data = array(
    'nama'              =>  $sw['nama'],
    'kelas'              =>  $sw['kelas'],);

    echo json_encode($data);

?>