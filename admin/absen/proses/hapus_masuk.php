<?php
include '../../../koneksi.php';

if (empty($_GET['laporan'])) {
    $resetMasuk = mysqli_query($koneksi, "UPDATE absensi SET jam_masuk = NULL, status = NULL");
    $cleanup = mysqli_query($koneksi, "DELETE FROM absensi WHERE jam_masuk IS NULL AND jam_pulang IS NULL");

    if ($resetMasuk && $cleanup) {
        echo "<script>alert('BERHASIL MENGHAPUS DATA ABSEN MASUK');window.location='../data_masuk.php';</script>";
        exit;
    }

    echo "<script>alert('GAGAL MENGHAPUS DATA ABSEN MASUK');window.location='../data_masuk.php';</script>";
    exit;
}
?>
