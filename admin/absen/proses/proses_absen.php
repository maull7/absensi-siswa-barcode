<!DOCTYPE html>
<html lang="en">
<head>
  <title>Absensi | Proses</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">

<?php
session_start();
include_once '../../../koneksi.php';

$nis = $_POST['nis'];
$tanggal = $_POST['tanggal'];
$status = $_POST['status'];
$keterangan = $_POST['keterangan'];

$update = "INSERT INTO absen (nis, tanggal, status, keterangan) VALUES ('$nis','$tanggal', '$status' ,'$keterangan')";
$hasil = mysqli_query($koneksi,$update);


if ($hasil){
//header ('location:view.php');
echo " <div class='alert alert-success'>
    <strong>Success!</strong> Redirecting you back in 1 seconds.
  </div>
<meta http-equiv='refresh' content='1; url= ../data_absen.php'/>  ";
} else { echo "<div class='alert alert-warning'>
    <strong>Failed!</strong> Redirecting you back in 1 seconds.
  </div>
 <meta http-equiv='refresh' content='1; url= ../data_absen.php'/> ";

}
?>

</body>
</html>