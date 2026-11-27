<?php
session_start();    
include '../koneksi.php';

$username = $_POST['username'];
$password = md5($_POST['password']);
$nama     = $_POST['nama'];
$nip      = $_POST['nip'];
$admin    = isset($_POST['admin']) ? intval($_POST['admin']) : 3; 

if (!$username || !$password || !$nama) {
    echo "Data tidak lengkap!";
    exit;
}

mysqli_query($koneksi, "INSERT INTO tbl_user 
    (username, password, nama, nip, admin) 
    VALUES 
    ('$username', '$password', '$nama', '$nip', '$admin')") 
or die(mysqli_error($koneksi));

header("Location: ../pengguna.php?info=simpan");
exit;
?>