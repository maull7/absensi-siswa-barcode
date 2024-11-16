<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

	// membuat variabel untuk menampung data dari form

  $nis = $_POST['nis'];
  $nama = $_POST['nama'];
  $kelas= $_POST['kelas'];
  $jurusan= $_POST['jurusan'];
  $tempat_l = $_POST['tempat_l'];
  $tanggal_l = $_POST['tanggal_l'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];


//cek dulu jika ada foto produk jalankan coding ini
if($nis!= "") {
   $query = "INSERT INTO data_siswa ( nis, nama, kelas, jurusan, tempat_l, tanggal_l, jenis_kelamin, alamat) VALUES ( '$nis','$nama','$kelas','$jurusan','$tempat_l','$tanggal_l','$jenis_kelamin','$alamat' )";
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

 
