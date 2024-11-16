<?php
include('../../../koneksi.php');

// Mendapatkan data dari formulir
$status = $_POST['status']; // Ini adalah array status
$tanggal = $_POST['tgl2'];
$jam = '-';

// Iterasi status dan sisipkan ke database
foreach ($status as $nis => $statusValue) {
if(in_array($statusValue, ['Alpha','Izin', 'Sakit'])){
    // Buat perintah SQL untuk menyimpan data ke dalam tabel
    $sql = "INSERT INTO masuk (nis, tanggal, status, jam_masuk) VALUES ('$nis', '$tanggal', '$statusValue', '$jam')";

    if (!mysqli_query($koneksi, $sql)) {
        echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
    }

}
    
}

// Redirect setelah berhasil
header("location:../data_absen.php");

// Menutup koneksi
mysqli_close($koneksi);
?>
