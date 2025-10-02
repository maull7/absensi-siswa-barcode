<?php
session_start();

unset($_SESSION['orang_tua_id'], $_SESSION['orang_tua_nama'], $_SESSION['orang_tua_nik'], $_SESSION['orang_tua_nis']);

if (empty($_SESSION)) {
    session_destroy();
}

header('Location: index.php');
exit();
