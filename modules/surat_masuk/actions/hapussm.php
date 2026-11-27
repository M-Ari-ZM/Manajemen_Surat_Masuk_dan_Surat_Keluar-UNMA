<?php
include_once '../../../config/koneksi.php';

if (isset($_POST['id_surat'])) {
    $id_surat = $_POST['id_surat'];

    mysqli_query($koneksi, "DELETE FROM tbl_surat_masuk WHERE id_surat='$id_surat'") or die(mysqli_error($koneksi));
}

header("location:../jumlah_surat_masuk.php?info=hapus");
exit;
?>
