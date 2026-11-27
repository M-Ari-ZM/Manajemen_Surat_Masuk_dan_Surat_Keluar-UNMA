<?php
session_start();
include 'koneksi.php';
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Akses ditolak!");
}

if (isset($_POST['ubah_password_admin'])) {
    $id_user = intval($_POST['id_user_password']);
    $password_baru = md5($_POST['password_baru_admin']);

    $update = mysqli_query($koneksi, "UPDATE tbl_user SET password='$password_baru' WHERE id_user=$id_user");

    if ($update) {
        echo "<script>alert('Password berhasil diubah!'); window.location='pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah password!'); window.location='pengguna.php';</script>";
    }
}
?>
