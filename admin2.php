<?php
include 'koneksi.php';
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'admin') {
        header("Location: admin/index.php");
        exit;
    } elseif ($_SESSION['sebagai'] == 'user') {
        header("Location: user/index.php");
        exit;
    }
}

// Proses login saat tombol ditekan
if (isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Menggunakan prepared statement
    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);  // Mengikat parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $rows = $result->fetch_assoc();
        $_SESSION['sebagai'] = $rows['sebagai'];
        $_SESSION['username'] = true;
        $_SESSION['nama'] = $rows['nama'];

        if ($rows['sebagai'] == 'admin') {
            header("Location: admin/index.php");
            exit;
        } elseif ($rows['sebagai'] == 'user') {
            header("Location: user/index.php");
            exit;
        }
    } else {
        echo "<script>alert('Username atau password Anda salah. Silahkan coba lagi!')</script>";
    }
    $stmt->close();
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="en" >
<head>
  <link rel="icon" href="assets/img/smkmadya.png">
  <meta charset="UTF-8">
  <title>Absensi | Admin Login</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet"><link rel="stylesheet" href="assets/css/style.css">

</head>
<style>
  .text-2{
    color: lightslategray;
    font-weight: bold;
  }
</style>
<body>
<!-- partial:index.partial.html -->
<form method="post" class="login">
  <input type="text" name="username" id="username" placeholder="Username" >
  <input type="password" name="password" id="password" placeholder="Password">
  <button type="submit" class="btn-login" name="btn-login">Login</button>
  <a href="index.php" class="text-2">Go to Siswa Page</a>
</form>
<!-- partial -->
  
</body>
</html>