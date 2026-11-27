<?php
$host     = "";
$username = "";
$password = "";
$database = "";

$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (mysqli_connect_errno()) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit;
}
?>
