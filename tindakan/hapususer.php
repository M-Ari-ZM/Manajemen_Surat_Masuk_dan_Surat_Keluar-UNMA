<?php
include '../koneksi.php';

if (isset($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    mysqli_query($koneksi, "DELETE FROM tbl_user WHERE id_user='$id_user'") or die(mysqli_error($koneksi));
}

header("location:../pengguna.php?info=hapus");
exit;
?>
