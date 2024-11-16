<!doctype html>
<html class="no-js" lang="en">

<head>
    <title>Laporan Harian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/images/icon/logosafety.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-144808195-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-144808195-1');
    </script>

</head>

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

    <script>
        $(document).ready(function() {
            $('#mauexport').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'pdf', 'print'
                ]
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>



</body>

</html>