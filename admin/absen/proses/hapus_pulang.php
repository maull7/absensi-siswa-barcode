<?php
include '../../../koneksi.php';

if (empty($_GET['laporan'])) {
    $resetPulang = mysqli_query($koneksi, "UPDATE absensi SET jam_pulang = NULL WHERE jam_pulang IS NOT NULL");
    $cleanup = mysqli_query($koneksi, "DELETE FROM absensi WHERE jam_masuk IS NULL AND jam_pulang IS NULL");

    if ($resetPulang && $cleanup) {
        echo "<script>alert('BERHASIL MENGHAPUS DATA ABSEN PULANG');window.location='../data_pulang.php';</script>";
        exit;
    }

    echo "<script>alert('GAGAL MENGHAPUS DATA ABSEN PULANG');window.location='../data_pulang.php';</script>";
    exit;
}
?>
