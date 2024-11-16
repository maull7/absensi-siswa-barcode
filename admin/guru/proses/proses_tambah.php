<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

	// membuat variabel untuk menampung data dari form

  $nama = $_POST['nama'];
  $kelas = $_POST['kelas'];
  $no_tlp = $_POST['no_tlp'];
  

//cek dulu jika ada foto produk jalankan coding ini

   $query = "INSERT INTO guru ( nama, kelas, no_tlp) VALUES ( '$nama','$kelas','$no_tlp')";
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


 
