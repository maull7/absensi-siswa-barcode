<?php
include '../../../koneksi.php';
$nis = $_GET['nis'];

// Properly escape the value to prevent SQL injection
$nis = mysqli_real_escape_string($koneksi, $nis);

$result = mysqli_query($koneksi, "DELETE FROM data_siswa WHERE nis = '$nis'");
$cek = mysqli_affected_rows($koneksi);

if ($cek > 0) {
  echo "<script> 
          alert('BERHASIL DI MENGHAPUS');
        </script>";
  header("Location: ../index.php");
} else {
  echo "<script> 
          alert('GAGAL DI MENGHAPUS');
        </script>";
  header("Location: ../index.php");
}
?>
