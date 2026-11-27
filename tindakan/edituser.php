<?php
session_start();
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'] ?? '';
    $username = $_POST['username'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $admin = isset($_POST['admin']) ? intval($_POST['admin']) : 3; 

    if (!$id_user || !$username || !$nama ) {
        echo "Data tidak lengkap!";
        exit;
    }

    $query = "UPDATE tbl_user SET username='$username', nama='$nama',admin='$admin' WHERE id_user='$id_user'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../pengguna.php?info=update");
        exit;
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
} else {
    echo "Metode tidak diperbolehkan.";
}
?>
