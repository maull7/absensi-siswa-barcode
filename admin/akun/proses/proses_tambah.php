<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

	// membuat variabel untuk menampung data dari form

  $username = $_POST['username'];
  $password = $_POST['password'];
  $nama = $_POST['nama'];
  $sebagai = $_POST['sebagai'];


//cek dulu jika ada foto produk jalankan coding ini
if($username!= "") {
   $query = "INSERT INTO login ( username, password, nama, sebagai) VALUES ( '$username','$password','$nama' ,'$sebagai')";
                  $result = mysqli_query($koneksi, $query);
                  // periska query apakah ada error
                  if(!$result){
                      die ("Query gagal dijalankan: ".mysqli_errno($koneksi).
                           " - ".mysqli_error($koneksi));
                  } else {
                    //tampil alert dan akan redirect ke halaman index.php
                    //silahkan ganti index.php sesuai halaman yang akan dituju
                    echo "<script>alert('Data berhasil ditambah.');window.location='../index.php';</script>";
                  }
}

 
