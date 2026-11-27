<?php
include '../koneksi.php';

if (isset($_POST['id_disposisi'])) {
    $id_disposisi = $_POST['id_disposisi'];

    mysqli_query($koneksi, "DELETE FROM tbl_disposisi WHERE id_disposisi='$id_disposisi'") or die(mysqli_error($koneksi));
}

header("location:../disposisi.php?info=hapus");
exit;
?>
