<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

	// membuat variabel untuk menampung data dari form
  $nis = $_POST['nis'];
  $nama = $_POST['nama'];
  $kelas = $_POST['kelas'];
  $jurusan = $_POST['jurusan'];
  $tempat_l = $_POST['tempat_l'];
  $tanggal_l = $_POST['tanggal_l'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $alamat = $_POST['alamat'];
  $img = $_FILES['img']['name'] ?? null;
  $pw = $_POST['password'];
  //cek dulu jika merubah foto produk jalankan coding ini

 if($img != "") {
  $ekstensi_diperbolehkan	= array('jpeg', 'png', 'jpg');
  $x = explode('.', $img);
  $ekstensi = strtolower(end($x));
  $file_tmp = $_FILES['img']['tmp_name'];
  $angka_acak = rand(1,999);
  $nama_gambar_baru = $angka_acak.'-'.$img ?? '-';
  if(in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
    move_uploaded_file($file_tmp, '../../../assets/images/'.$nama_gambar_baru);
      // jalankan query UPDATE berdasarkan ID yang produknya kita edit
      $query  = "UPDATE data_siswa SET nis = '$nis', nama = '$nama',kelas = '$kelas',jurusan = '$jurusan',tempat_l = '$tempat_l',tanggal_l = '$tanggal_l', jenis_kelamin = '$jenis_kelamin',alamat = '$alamat',img = '$nama_gambar_baru', password='$pw'";
      $query .= "WHERE nis = '$nis'";
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
  } else {
    echo "<script>alert('ekstensi gambar yang boleh hanya jpeg jpg atau png.');window.location='../index.php';</script>";
  }

} else {
  $query  = "UPDATE data_siswa SET nis = '$nis', nama = '$nama',kelas = '$kelas',jurusan = '$jurusan',tempat_l = '$tempat_l',tanggal_l = '$tanggal_l', jenis_kelamin = '$jenis_kelamin',alamat = '$alamat',img = '$nama_gambar_baru', password='$pw'";
  $query .= "WHERE nis = '$nis'";
  $result = mysqli_query($koneksi, $query);
  // periska query apakah ada error
  if(!$result){
    die ("Query gagal dijalankan: ".mysqli_errno($koneksi).
                     " - ".mysqli_error($koneksi));

} else {
  //tampil alert dan akan redirect ke halaman index.php
  //silahkan ganti index.php sesuai halaman yang akan dituju
    echo "<script>alert('Data berhasil diubah.');window.location='../index.php';</script>";
    die;
}
}

