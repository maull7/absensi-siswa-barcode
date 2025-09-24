<?php
// Include koneksi database
include '../../../koneksi.php';

// Tentukan rentang tanggal yang ingin ditampilkan
$tanggal_mulai = $_POST['tgl1'];
$tanggal_akhir = $_POST['tgl2'];

$kelas = $_POST['class'];

// Query untuk mengambil data kehadiran siswa dalam rentang tanggal
$query = "
    SELECT 
        s.nis, 
        s.nama, 
        s.kelas, 
        DAY(a.tanggal) AS tanggal_hari,
        a.status
    FROM 
        data_siswa s
    LEFT JOIN
        absensi a ON s.nis = a.nis AND a.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'
    WHERE 
        s.kelas = '$kelas'
    ORDER BY 
        s.nama, a.tanggal;
";

// Eksekusi query
$result = $koneksi->query($query);

// Cek apakah ada hasil dari query
if ($result->num_rows > 0) {
    // Menyimpan data dalam array untuk memudahkan penyusunan
    $siswa_data = [];
    $tanggal_list = [];

    // Memasukkan data ke dalam array siswa_data
    while ($row = $result->fetch_assoc()) {
        $siswa_data[$row['nis']]['nama'] = $row['nama'];
        $siswa_data[$row['nis']]['status'][$row['tanggal_hari']] = $row['status'];
        $tanggal_list[] = $row['tanggal_hari'];
    }

    // Menyusun tanggal unik (remove duplicates) dan urutkan tanggal
    $tanggal_list = array_unique($tanggal_list);


    // Menampilkan buku absen dalam format tabel
    echo '<h2 style="text-align: center;">Buku Absen Siswa Kelas ' . htmlspecialchars($kelas) . ' (' . $tanggal_mulai . ' sampai ' . $tanggal_akhir . ')</h2>';
    echo '<div style="text-align: center;">';
    echo '<button onclick="exportToExcel()" class="btn-excel">Export to Excel</button>';
    echo '<a href="../bulanan.php" class="btn-back">BACK</a>';
    echo '</div>';
    echo '<div style="overflow-x:auto; text-align: center;">';
    echo '<table id="attendanceTable" border="1" cellpadding="10" cellspacing="0" style="margin: 20px auto; border-collapse: collapse;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>No.</th>';
    echo '<th>Nama Siswa</th>';

    // Menampilkan tanggal sebagai header kolom (hanya hari)
    foreach ($tanggal_list as $tanggal) {
        echo '<th>' . $tanggal . '</th>';
    }

    // Menambahkan kolom untuk jumlah status
    echo '<th>H</th>';
    echo '<th>I</th>';
    echo '<th>S</th>';
    echo '<th>A</th>';

    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Menampilkan data siswa dan status per tanggal
    $no = 1;
    foreach ($siswa_data as $nis => $data) {
        echo '<tr>';
        echo '<td>' . $no++ . '</td>';
        echo '<td>' . htmlspecialchars($data['nama']) . '</td>';

        // Hitung status per siswa
        $jumlah_hadir = 0;
        $jumlah_izin = 0;
        $jumlah_sakit = 0;
        $jumlah_alpha = 0;

        // Menampilkan status untuk setiap tanggal dan hitung jumlah status
        foreach ($tanggal_list as $tanggal) {
            // Ganti status dengan keterangan singkat
            $status = isset($data['status'][$tanggal]) ? $data['status'][$tanggal] : 'Tidak Masuk';

            // Jika status adalah "Telat", anggap sebagai "Hadir" tanpa keterangan tambahan
            if ($status == 'Telat') {
                $status = ''; // Status Telat dianggap Hadir
            }

            // Keterangan singkat untuk status lainnya
            switch ($status) {
                case '':
                    $status = '';
                    $jumlah_hadir++;
                    break;
                case 'Izin':
                    $status = 'I';
                    $jumlah_izin++;
                    break;
                case 'Sakit':
                    $status = 'S';
                    $jumlah_sakit++;
                    break;
                case 'Tidak Masuk':
                case 'Alpha':
                    $status = 'A';  // Keterangan untuk Alpha
                    $jumlah_alpha++;
                    break;
                default:
                    $status = 'Tidak Masuk';  // Jika status tidak dikenal
                    break;
            }
            echo '<td>' . $status . '</td>';
        }

        // Menampilkan jumlah status
        echo '<td>' . $jumlah_hadir . '</td>';
        echo '<td>' . $jumlah_izin . '</td>';
        echo '<td>' . $jumlah_sakit . '</td>';
        echo '<td>' . $jumlah_alpha . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p style="text-align: center;">Data absen tidak ditemukan.</p>';
}

// Tutup koneksi
$koneksi->close();
?>

<!-- Styling -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fa;
        margin: 0;
        padding: 20px;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #fff;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    .btn-excel {
        background-color: #28a745;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
        margin-right: 1.4rem;
    }

    .btn-excel:hover {
        background-color: #218838;
    }

    .btn-back {
        text-decoration: none;
        background-color: red;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
    }

    .btn-back:hover {
        background-color: pink;
    }
</style>

<!-- JavaScript untuk fitur Export ke Excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
<script>
    let kelas = <?php echo json_encode($kelas); ?>;

    function exportToExcel() {
        var wb = XLSX.utils.table_to_book(document.getElementById('attendanceTable'), {
            sheet: "Sheet1"
        });
        XLSX.writeFile(wb, `${kelas}.xlsx`);
    }
</script>