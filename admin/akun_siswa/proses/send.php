<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $class = $_POST['class'];
    $tanggal = $_POST['tgl2'];

    // Include the database connection file
    include '../../../koneksi.php';

    // Prepare SQL query to get all students from the selected class and their attendance details
    $sql = "
    SELECT s.nis, s.nama, s.kelas, a.tanggal, a.jam_masuk, a.status
    FROM data_siswa s
    LEFT JOIN masuk a ON s.nis = a.nis AND a.tanggal = '$tanggal'
    WHERE s.kelas = '$class'";

    $result = $koneksi->query($sql);

    // Initialize an empty array to store fetched data
    $data = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        echo "No results found.";
    }

    $koneksi->close();
    ?>

<div class="container">
   
    <h2>Data Siswa</h2>
    <div class="data-tables datatable-dark">
    <a href="send.php"></a>
        <table class="display" id="mauexport" style="width:100%">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Jam Kehadiran</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($data as $row) {
                    echo "<tr>
                            <td align='center'>" . $no++ . "</td>
                            <td>" . $row['nis'] . "</td>
                            <td>" . $row['nama'] . "</td>
                            <td>" . $row['kelas'] . "</td>
                            <td>" . ($row['tanggal'] ? $row['tanggal'] : '-') . "</td>
                            <td>" . ($row['jam_masuk'] ? $row['jam_masuk'] : '-') . "</td>
                        <td>" . ($row['status'] !== null ? $row['status'] : 'Tidak hadir') . "</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- /.card -->
    </div>
    </div>
    </div>
    </div>
</body>
</html>