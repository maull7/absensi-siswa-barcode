<!doctype html>
<html class="no-js" lang="en">

<?php 
	include '../../../koneksi.php';
    
?>

<html>
<head>
<title>Absensi | Export Data Siswa</title>
	   <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="../../../assets/img/smkmadya.png">
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
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-144808195-1');
	</script>

</head>

<body>
		<div class="container">
			<h2>Data Siswa</h2>
				<div class="data-tables datatable-dark">
					<table id="dataTable3" class="display" style="width:100%"><thead class="thead-dark">
											<tr align="center">
												<th>No</th>
                                                <th>NIS</th>
												<th>Nama</th>
												<th>Kelas</th>
												<th>Jurusan</th>
												<th>TTL</th>
												<th>Jenis Kelamin</th>
												<th>Alamat</th>
												
												<!--<th>Opsi</th>-->
											</tr></thead><tbody>
											<?php 
											$brgs=mysqli_query($koneksi,"SELECT * from data_siswa");
											$no=1;
											while($p=mysqli_fetch_array($brgs)){

												?>
												
												<tr>
													<td><?php echo $no++ ?></td>
                                                    <td><?php echo $p['nis'] ?></td>
                                                    <td><?php echo $p['nama'] ?></td>
                                                    <td><?php echo $p['kelas'] ?></td>
                                                    <td><?php echo $p['jurusan'] ?></td>
                                                    <td><?php echo $p['tempat_l'] ?>, <?php echo $p['tanggal_l'] ?></td>
                                                    <td><?php echo $p['jenis_kelamin'] ?></td>
                                                    <td><?php echo $p['alamat'] ?></td>
												</tr>		
												<?php 
											}
											?>
										</tbody>
										</table>
								</div>
						</div>
	
<script>
$(document).ready(function() {
    $('#dataTable3').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print',
        ]
    } );
} );

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