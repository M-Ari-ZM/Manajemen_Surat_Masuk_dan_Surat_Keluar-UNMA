<?php
include '../koneksi.php';

if (isset($_POST['id_klasifikasi'])) {
    $id_klasifikasi = $_POST['id_klasifikasi'];

    mysqli_query($koneksi, "DELETE FROM tbl_klasifikasi WHERE id_klasifikasi='$id_klasifikasi'") or die(mysqli_error($koneksi));
}

header("location:../klasifikasi.php?info=hapus");
exit;
?>
