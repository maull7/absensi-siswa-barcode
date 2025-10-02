<?php
// Memanggil file koneksi.php untuk melakukan koneksi database
include '../../../koneksi.php';

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

$currentDataQuery = mysqli_query($koneksi, "SELECT img FROM data_siswa WHERE nis = '$nis' LIMIT 1");
if (!$currentDataQuery || mysqli_num_rows($currentDataQuery) === 0) {
    echo "<script>alert('Data siswa tidak ditemukan.');window.location='../index.php';</script>";
    exit();
}

$currentData  = mysqli_fetch_assoc($currentDataQuery);
$currentImage = $currentData['img'] ?? '';

$uploadedFileName = $_FILES['img']['name'] ?? '';
$nama_gambar_baru = $currentImage;

if (!empty($uploadedFileName)) {
    $ekstensi_diperbolehkan = ['jpeg', 'png', 'jpg'];
    $x = explode('.', $uploadedFileName);
    $ekstensi = strtolower(end($x));
    $file_tmp = $_FILES['img']['tmp_name'];
    $angka_acak = rand(1, 999);
    $nama_file_baru = $angka_acak . '-' . $uploadedFileName;

    if (!in_array($ekstensi, $ekstensi_diperbolehkan, true)) {
        echo "<script>alert('Ekstensi gambar yang diperbolehkan hanya jpeg, jpg, atau png.');window.history.back();</script>";
        exit();
    }

    if (!move_uploaded_file($file_tmp, '../../../assets/images/' . $nama_file_baru)) {
        echo "<script>alert('Gagal mengunggah gambar.');window.history.back();</script>";
        exit();
    }

    $nama_gambar_baru = $nama_file_baru;
}

mysqli_begin_transaction($koneksi);

try {
    $queryUpdate = "UPDATE data_siswa SET nama = '$nama', kelas = '$kelas', jurusan = '$jurusan', tempat_l = '$tempat_l', tanggal_l = '$tanggal_l', jenis_kelamin = '$jenis_kelamin', alamat = '$alamat', img = '$nama_gambar_baru', password = '$pw' WHERE nis = '$nis'";

    if (!mysqli_query($koneksi, $queryUpdate)) {
        throw new Exception('Gagal memperbarui data siswa: ' . mysqli_error($koneksi));
    }

    $cekNikOrtu = mysqli_query($koneksi, "SELECT id FROM orang_tua WHERE nik = '$nikOrangTua' AND nis <> '$nis' LIMIT 1");
    if ($cekNikOrtu && mysqli_num_rows($cekNikOrtu) > 0) {
        throw new Exception('NIK orang tua sudah digunakan oleh akun lain.');
    }

    $cekOrtu = mysqli_query($koneksi, "SELECT id FROM orang_tua WHERE nis = '$nis' LIMIT 1");
    if ($cekOrtu && mysqli_num_rows($cekOrtu) > 0) {
        $ortuRow = mysqli_fetch_assoc($cekOrtu);
        $ortuId = $ortuRow['id'];
        $queryOrtu = "UPDATE orang_tua SET nama = '$namaOrangTua', nik = '$nikOrangTua', pw = '$pw' WHERE id = '$ortuId'";
    } else {
        $queryOrtu = "INSERT INTO orang_tua (nama, nik, pw, nis) VALUES ('$namaOrangTua', '$nikOrangTua', '$pw', '$nis')";
    }

    if (!mysqli_query($koneksi, $queryOrtu)) {
        throw new Exception('Gagal memperbarui akun orang tua: ' . mysqli_error($koneksi));
    }

    mysqli_commit($koneksi);
    echo "<script>alert('Data siswa dan akun orang tua berhasil diubah.');window.location='../index.php';</script>";
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $message = addslashes($e->getMessage());
    echo "<script>alert('" . $message . "');window.history.back();</script>";
}
