<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = md5($_POST['password']); 

$query = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username='$username' AND password='$password'");

if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);

    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama']    = $user['nama'];       
    $_SESSION['nip']     = $user['nip'];
    $_SESSION['admin']   = $user['admin'];  
    $_SESSION['status']  = "login";

    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?info=gagal");
    exit();
}
?>
