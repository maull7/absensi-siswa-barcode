<?php
session_start();
include "koneksi.php";
if (isset($_POST['nis']) && isset($_POST['password'])) {
  // Get user input
  $nis = $_POST['nis'];
  $password = $_POST['password'];

// Query to check user credentials
$query = "SELECT * FROM login_siswa WHERE nis='$nis' AND password='$password'";
$result = $koneksi->query($query);

if ($result->num_rows == 1) {
    // Login successful
    $_SESSION['password'] = $password;
    $_SESSION['nis'] = $nis;
    header("Location: siswa/index.php"); // Redirect to dashboard or any other page
} else {
    // Login failed
    echo "<script>alert('nis atau password Anda salah. Silahkan coba lagi!')</script>";
}
}
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en" >
<head>
<link rel="icon" href="assets/img/smkmadya.png">
  <meta charset="UTF-8">
  <title>Absensi | Siswa Login</title>
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
  <input type="text" name="nis" id="nis" placeholder="NIS" >
  <input type="password" name="password" id="password" placeholder="password">
  <button type="submit" class="btn-login" name="btn-login">Login</button>
  <a href="admin.php" class="text-2">Go to Admin Page</a>

</form>
<!-- partial -->
  
</body>
</html>
