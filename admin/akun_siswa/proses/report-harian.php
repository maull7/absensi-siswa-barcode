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
    LEFT JOIN absensi a ON s.nis = a.nis AND a.tanggal = '$tanggal'
    WHERE s.kelas = '$class'";

    $result = $koneksi->query($sql);

    $no = "SELECT no_tlp FROM guru WHERE kelas = '$class'";
    $no_telp = $koneksi->query($no);

    if ($no_telp->num_rows > 0) {
        while ($row = $no_telp->fetch_assoc()) {
            $phoneNumbers[] = $row['no_tlp'];
        }
    }
    

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
   
<h2 class="text-center">Data siswa absen masuk kelas <?=$class; ?></h2>
<h4 class="text-center">Tanggal : <?= $tanggal?></h4>
    <div class="data-tables datatable-dark">
    <button class="send btn btn-primary mb-3">SEND</button>
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
            ],
            "paging": false,      // Menonaktifkan pagination
            "searching": false,   // Jika tidak perlu searching
            "ordering": true,     // Jika tidak perlu sorting
            "info": false,        // Menyembunyikan informasi total data
            "pageLength": -1      // Menampilkan semua data
        });
    });

    



document.querySelector('.send').addEventListener('click', function() {
    let kelas = <?php echo json_encode($class); ?>;
    let tanggal = <?php echo json_encode($tanggal); ?>;
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Menyiapkan teks judul
    let titleText = `Report Harian masuk Siswa kelas ${kelas}`;

    // Mendapatkan lebar halaman PDF
    let pageWidth = doc.internal.pageSize.getWidth();

    // Mengatur ukuran font seperti h3 untuk judul
    doc.setFontSize(15); // Ukuran font untuk judul (seperti h3)

    // Mendapatkan lebar teks judul dan menempatkannya di tengah
    let titleWidth = doc.getTextWidth(titleText);
    doc.text(titleText, (pageWidth - titleWidth) / 2, 10);

    // Mengatur ukuran font seperti h5 untuk tanggal
    doc.setFontSize(14); // Ukuran font untuk tanggal (seperti h5)

    // Mendapatkan lebar teks tanggal dan menempatkannya di tengah
    let dateWidth = doc.getTextWidth(tanggal);
    doc.text(tanggal, (pageWidth - dateWidth) / 2, 20);

    // Mengambil data dari tabel
    let rows = document.querySelectorAll('#mauexport tbody tr');
    let data = [];
    
    rows.forEach(row => {
        let rowData = [];
        let cells = row.querySelectorAll('td');
        cells.forEach(cell => {
            rowData.push(cell.innerText);
        });
        data.push(rowData);
    });

    // Mengatur font kembali ke ukuran normal untuk tabel
    doc.setFontSize(12); // Ukuran standar untuk isi tabel

    // Membuat tabel di dalam PDF, dimulai setelah tanggal
    doc.autoTable({
        startY: 30, // Menentukan posisi awal tabel di bawah tanggal
        head: [['No', 'NIS', 'Nama', 'Kelas', 'Tanggal', 'Jam Kehadiran', 'Status']],
        body: data
    });

    // Menyimpan PDF ke dalam blob
    const pdfBlob = doc.output('blob');

    // Menyiapkan pengiriman melalui WhatsApp
    const url = URL.createObjectURL(pdfBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `${kelas} ${tanggal}`;
    link.click();
    
    let phoneNumbers = <?php echo json_encode($phoneNumbers); ?>;

    // Memilih nomor telepon dari database
    let phoneNumber = phoneNumbers.length > 0 ? phoneNumbers[0] : ''; // Pilih nomor pertama atau kosongkan jika tidak ada

    if (phoneNumber) {
        let message = encodeURIComponent(`Assalamualaikum wr. wb. Ibu, berikut adalah data absen hari ${tanggal} dari kelas ${kelas}. 
        Untuk yang tidak hadir, mohon diperhatikan ya ibu.`);
        window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
    } else {
        alert("Nomor telepon tidak ditemukan.");
    }
});
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

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