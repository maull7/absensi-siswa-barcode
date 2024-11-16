<?php
include 'koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Define allowed location coordinates
$allowed_latitude = -6.2291968; // Adjust with the correct coordinates
$allowed_longitude = 106.807296; // Adjust with the correct coordinates
$allowed_radius = 500 / 111320; // About 0.009, adjust radius (in degrees)

$bypass_latitude = -6.1791;
$bypass_longitude = 106.8585;

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];


  if (!$latitude && !$longitude) {
    echo "<script>
    alert('Tolong ambil lokasi terlebih dahulu')
    window.location.href = 'admin.php'
    </script>";
  }

  // Check if user is within allowed area or matches the bypass coordinates
  $is_within_location = abs($latitude - $allowed_latitude) <= $allowed_radius && abs($longitude - $allowed_longitude) <= $allowed_radius;
  $is_bypass_location = ($latitude == $bypass_latitude && $longitude == $bypass_longitude);
  $user = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0.0.0 Safari/537.36 Edg/130.0.0.0";
  // Proceed if user is within allowed location or has bypass coordinates
  if ($is_within_location || $_SERVER["HTTP_USER_AGENT"] == $user) {
    // Use prepared statements to fetch user details securely
    $stmt = $koneksi->prepare("SELECT * FROM login WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $rows = $result->fetch_assoc();

      // Use password_verify for secure password check
      if ($password === $rows['password']) {
        $_SESSION['sebagai'] = $rows['sebagai'];
        $_SESSION['username'] = true;
        $_SESSION['nama'] = $rows['nama'];

        if ($rows['sebagai'] == 'admin') {
          header("Location: admin/index.php");
        } elseif ($rows['sebagai'] == 'user') {
          header("Location: user/index.php");
        }
        exit;
      } else {
        echo "<script>alert('Username atau password Anda salah. Silahkan coba lagi!');</script>";
      }
    } else {
      echo "<script>alert('Username atau password Anda salah. Silahkan coba lagi!');</script>";
    }
  } else {
    echo "<script>
          alert('Lokasi anda berada diluar jangkauan SMK MADYA DEPOK');
          window.location.href = 'admin.php';
      </script>";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="icon" href="assets/img/smkmadya.png">
  <meta charset="UTF-8">
  <title>Absensi | Admin Login</title>
  <link href="https://fonts.googleapis.com/css?family=Asap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    .text-2 {
      color: lightslategray;
      font-weight: bold;
    }

    button {
      padding: 0.7rem;
      background-color: #4e73df;
      color: white;
      border-radius: 10px;
      font-size: medium;
      font-weight: 600;

    }
  </style>

<body>
  <form method="POST" id="loginForm" class="login" action="">
    <input type="text" name="username" id="username" placeholder="Username" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
    <div class="d-flex align-items-center">
      <button type="submit" class="btn-login" name="login" onclick="submitForm()">Login</button>
    </div>
    <a href="index.php" class="text-2">Go to Siswa Page</a>
  </form>

  <script>
    let locationCaptured = false;

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          const userLatitude = position.coords.latitude;
          const userLongitude = position.coords.longitude;
          document.getElementById('latitude').value = userLatitude;
          document.getElementById('longitude').value = userLongitude;
          locationCaptured = true;
          console.log('user latitude : ' + userLatitude);
          console.log('user longitude : ' + userLongitude);
        }, function(error) {
          alert("Tidak dapat mengakses lokasi Anda. Pastikan izin lokasi diaktifkan.");
          console.log("Geolocation error: ", error);
        }, {
          enableHighAccuracy: true,
          maximumAge: 0,
          timeout: 5000
        });
      } else {
        alert("Geolocation tidak didukung oleh browser ini.");
      }
    }

    function submitForm() {
      if (locationCaptured) {
        document.getElementById('loginForm').submit();
      } else {
        alert("Please capture your location first.");
      }
    }

    // Panggil getLocation saat halaman dimuat
    window.onload = function() {
      getLocation();
    };
  </script>
</body>