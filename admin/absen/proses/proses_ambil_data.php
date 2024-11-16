<?php
if (isset($_POST['class']) && isset($_POST['tgl2'])) {
    $class = $_POST['class'];
    $tanggal = $_POST['tgl2'];

    // Include database connection
    include '../../../koneksi.php';

    // Query untuk mengambil data siswa yang tidak memiliki entri di tabel 'masuk' pada tanggal tertentu
    $sql = "
    SELECT s.nis, s.nama, s.kelas, a.tanggal, a.jam_masuk, a.status
    FROM data_siswa s
    LEFT JOIN masuk a ON s.nis = a.nis AND a.tanggal = '$tanggal'
    WHERE s.kelas = '$class' AND a.tanggal IS NULL"; // Filter untuk data yang belum ada di tabel 'masuk'

    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        echo '<form method="post" action="proses/proses_status.php">'; // Tambahkan form
        echo '<table class="table table-bordered">';
        echo '<tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Tanggal</th><th>Jam Masuk</th><th>Status</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['nis'] . '</td>';
            echo '<td>' . $row['nama'] . '</td>';
            echo '<td>' . $row['kelas'] . '</td>';
            echo '<td>' . $tanggal . '</td>';
            echo '<td>' . ($row['jam_masuk'] ?? '-') . '</td>'; // Jam masuk kosong karena belum ada di tabel 'masuk'
            
            // Dropdown pilihan status
            echo '<td>';
            echo '<select class="form-control" name="status[' . $row['nis'] . ']">';
            echo '<option value="Belum Masuk">Belum Masuk</option>';
            echo '<option value="Alpha">Alpha</option>';
            echo '<option value="Izin">Izin</option>';
            echo '<option value="Sakit">Sakit</option>';
            echo '</select>';
            echo '</td>';
            
            // Input tanggal tersembunyi
            echo '<input type="hidden" name="tgl2" value="' . htmlspecialchars($tanggal) . '">';
            
            echo '</tr>';
        }
        echo '</table>';
        echo '<button type="submit">Submit Status</button>';
        echo '</form>'; // Tutup form
    } else {
        echo 'No results found.';
    }

    $koneksi->close();
}
?>
