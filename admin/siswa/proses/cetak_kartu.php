<!doctype html>
<html class="no-js" lang="en">

<?php 
	include '../../../koneksi.php';
    
?>

<html>
<head>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Data Siswa</title>
  <link rel="icon" href="../../../assets/img/smkmadya.png">
</head>
</head>
<style>
    .container {
        position: relative;
        width: 8cm;
        height: 11cm;
    }

    .image {
        width: 100%;
        height: 100%;
    }

    .text-overlay {
        color: #fff;
        position: absolute;
        top: 3px;
        left: 85px;
        width: 300px;
        height: 40px;
        text-transform: uppercase;
        text-align: center;
        letter-spacing: 2px;
        font-size: 12px;
    }

    .title {
        position: absolute;
        top: 90px;
        left: 150px;
        width: 240px;
        text-align: left;
        font-size: 15px;
        letter-spacing: 2px;
    }

    .table-container {
        position: absolute;
        top: 130px;
        left: 85px;
        width: 400px;
        height: 100px;

        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 2px;
    }

    body {
        font-family: roboto;
    }

    .bold-text {
        font-weight: 700;
        font-size:medium;

    }
</style>
<body>

        <?php 
        $brgs=mysqli_query($koneksi,"SELECT * from data_siswa");
        while($row=mysqli_fetch_array($brgs)){

            ?>
            <body onload="window.print()">
            <div class="container">
    <?php
        $kelas = $row["kelas"];
        $imgSrc = "";
        $padding = "";

        // Tentukan gambar berdasarkan kelas
        switch($kelas) {
            case "X PPLG 1":
            case "X PPLG 2":
            case "XI PPLG 1":
            case "XI PPLG 2":
                $imgSrc = "../../../assets/img/pplg.jpeg";
                $padding = "40px"; // Padding untuk kelas PPLG
                break;
            case "XI PEMASARAN":
                $imgSrc = "../../../assets/img/bdp.png";
                $padding = "30px"; // Padding untuk kelas PEMASARAN
                break;
            case "XI AKUNTANSI":
             $imgSrc = "../../../assets/img/akl.png";
              $padding = "30px"; // Padding untuk kelas akuntansi
               break;
                
            // Tambahkan case lain jika ada kelas lainnya
            default:
                $imgSrc = "../../../assets/img/default.png"; // Jika kelas tidak sesuai dengan yang ada
                break;
        }
    ?>

    <!-- Menampilkan gambar kartu -->
    <img src="<?php echo $imgSrc; ?>" alt="Kartu" class="image">

        <div class="table-container">
        <table cellspacing="0">
            <tr>
                <td><img src="../../../assets/images/<?php echo $row["img"]; ?>" alt="Kartu" style="width: 3cm; height: 3cm;"></td>
            </tr>
            <tr>
                <td class="bold-text" style="color:black;font-size: 15px"><?php echo $row["nama"]; ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td class="bold-text" style="color:black;"><?php echo $row["nis"]; ?></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td style="color:wheat; padding-left: <?php echo $padding; ?>;"><?php echo $row["kelas"]; ?></td>
            </tr>
        </table>
        </div>
    </div>
        <br>

    <?php 
        }
        ?>
										
</body>

</html>