<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

	// membuat variabel untuk menampung data dari form
  $id = $_POST['id'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $nama = $_POST['nama'];
  $sebagai = $_POST['sebagai'];
  //cek dulu jika merubah foto produk jalankan coding ini
 {
      // jalankan query UPDATE berdasarkan ID yang produknya kita edit
      $query  = "UPDATE login SET username = '$username', password = '$password', nama = '$nama',sebagai = '$sebagai'";
      $query .= "WHERE id = '$id'";
      $result = mysqli_query($koneksi, $query);
      // periska query apakah ada error
      if(!$result){
        die ("Query gagal dijalankan: ".mysqli_errno($koneksi).
                         " - ".mysqli_error($koneksi));
  } else {
    //tampil alert dan akan redirect ke halaman index.php
    //silahkan ganti index.php sesuai halaman yang akan dituju
      echo "<script>alert('Data berhasil diubah.');window.location='../index.php';</script>";
  }
    }

 