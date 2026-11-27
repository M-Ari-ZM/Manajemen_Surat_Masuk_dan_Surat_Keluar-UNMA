<?php
include '../koneksi.php';

if (isset($_POST['id_sk'])) {
    $id_sk = $_POST['id_sk'];

    mysqli_query($koneksi, "DELETE FROM sk WHERE id_sk='$id_sk'") or die(mysqli_error($koneksi));
}

header("location:../rektorat.php?info=hapus");
exit;
?>
