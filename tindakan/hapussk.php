<?php
include '../koneksi.php';

if (isset($_POST['id_surat'])) {
    $id_surat = $_POST['id_surat'];

    mysqli_query($koneksi, "DELETE FROM tbl_surat_keluar WHERE id_surat='$id_surat'") or die(mysqli_error($koneksi));
}

header("location:../jumlah_surat_keluar.php?info=hapus");
exit;
?>
