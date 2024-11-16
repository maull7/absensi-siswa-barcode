<?php
include '../../koneksi.php';

    if (empty($_GET['laporan'])) {
        // Assuming $config is your database connection
        
       // Delete records from the 'transaksi' table
       $sql = 'DELETE FROM masuk';
       $stmtTransaksi = $koneksi->prepare($sql);
      $stmtTransaksi->execute();

                                      
$cek = mysqli_affected_rows($koneksi);

if ($cek > 0) {
  echo "<script> 
          alert('BERHASIL DI MENGHAPUS');
        </script>";
  header("Location: ../data_masuk.php");
} else {
  echo "<script> 
          alert('GAGAL DI MENGHAPUS');
        </script>";
  header("Location: ../data_masuk.php");
}
  }
                                        
  ?>