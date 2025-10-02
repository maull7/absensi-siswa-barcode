<?php
// memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

// Membuat variabel untuk menampung data dari form
$nis            = mysqli_real_escape_string($koneksi, trim($_POST['nis'] ?? ''));
$nama           = mysqli_real_escape_string($koneksi, trim($_POST['nama'] ?? ''));
$kelas          = mysqli_real_escape_string($koneksi, trim($_POST['kelas'] ?? ''));
$jurusan        = mysqli_real_escape_string($koneksi, trim($_POST['jurusan'] ?? ''));
$tempat_l       = mysqli_real_escape_string($koneksi, trim($_POST['tempat_l'] ?? ''));
$tanggal_l      = mysqli_real_escape_string($koneksi, trim($_POST['tanggal_l'] ?? ''));
$jenis_kelamin  = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin'] ?? ''));
$alamat         = mysqli_real_escape_string($koneksi, trim($_POST['alamat'] ?? ''));
$pw             = mysqli_real_escape_string($koneksi, trim($_POST['password'] ?? ''));
$namaOrangTua   = mysqli_real_escape_string($koneksi, trim($_POST['nama_orang_tua'] ?? ''));
$nikOrangTua    = mysqli_real_escape_string($koneksi, trim($_POST['nik_orang_tua'] ?? ''));

if ($nis === '' || $nama === '' || $kelas === '' || $jurusan === '' || $tempat_l === '' || $tanggal_l === '' ||
    $jenis_kelamin === '' || $alamat === '' || $pw === '' || $namaOrangTua === '' || $nikOrangTua === '') {
    echo "<script>alert('Mohon lengkapi seluruh data siswa dan orang tua.');window.history.back();</script>";
    exit();
}

// Cek apakah NIS sudah digunakan
$cekSiswa = mysqli_query($koneksi, "SELECT 1 FROM data_siswa WHERE nis = '$nis' LIMIT 1");
if ($cekSiswa && mysqli_num_rows($cekSiswa) > 0) {
    echo "<script>alert('NIS sudah terdaftar. Gunakan NIS lainnya.');window.history.back();</script>";
    exit();
}

// Cek apakah sudah ada akun orang tua untuk siswa atau nik yang sama
$cekOrtuByNis = mysqli_query($koneksi, "SELECT 1 FROM orang_tua WHERE nis = '$nis' LIMIT 1");
if ($cekOrtuByNis && mysqli_num_rows($cekOrtuByNis) > 0) {
    echo "<script>alert('Akun orang tua untuk NIS tersebut sudah ada.');window.history.back();</script>";
    exit();
}

$cekOrtuByNik = mysqli_query($koneksi, "SELECT 1 FROM orang_tua WHERE nik = '$nikOrangTua' LIMIT 1");
if ($cekOrtuByNik && mysqli_num_rows($cekOrtuByNik) > 0) {
    echo "<script>alert('NIK orang tua sudah terdaftar. Gunakan NIK lainnya.');window.history.back();</script>";
    exit();
}

mysqli_begin_transaction($koneksi);

try {
    $querySiswa = "INSERT INTO data_siswa (nis, nama, kelas, jurusan, tempat_l, tanggal_l, jenis_kelamin, alamat, password) VALUES (
        '$nis', '$nama', '$kelas', '$jurusan', '$tempat_l', '$tanggal_l', '$jenis_kelamin', '$alamat', '$pw')";

    if (!mysqli_query($koneksi, $querySiswa)) {
        throw new Exception('Gagal menyimpan data siswa: ' . mysqli_error($koneksi));
    }

    $queryOrtu = "INSERT INTO orang_tua (nama, nik, pw, nis) VALUES ('$namaOrangTua', '$nikOrangTua', '$pw', '$nis')";
    if (!mysqli_query($koneksi, $queryOrtu)) {
        throw new Exception('Gagal membuat akun orang tua: ' . mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    echo "<script>alert('Data siswa dan akun orang tua berhasil ditambah.');window.location='../index.php';</script>";
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $message = addslashes($e->getMessage());
    echo "<script>alert('" . $message . "');window.history.back();</script>";
}


