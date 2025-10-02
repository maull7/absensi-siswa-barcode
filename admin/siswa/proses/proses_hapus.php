<?php
include '../../../koneksi.php';

$nis = mysqli_real_escape_string($koneksi, $_GET['nis'] ?? '');

if ($nis === '') {
    echo "<script>alert('Data siswa tidak ditemukan.');window.location='../index.php';</script>";
    exit();
}

mysqli_begin_transaction($koneksi);

try {
    $deleteSiswa = mysqli_query($koneksi, "DELETE FROM data_siswa WHERE nis = '$nis'");
    if (!$deleteSiswa) {
        throw new Exception('Gagal menghapus data siswa: ' . mysqli_error($koneksi));
    }

    mysqli_query($koneksi, "DELETE FROM orang_tua WHERE nis = '$nis'");

    mysqli_commit($koneksi);
    echo "<script>alert('Data siswa dan akun orang tua berhasil dihapus.');window.location='../index.php';</script>";
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $message = addslashes($e->getMessage());
    echo "<script>alert('" . $message . "');window.location='../index.php';</script>";
}
?>
