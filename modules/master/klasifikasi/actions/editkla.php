<?php
include_once '../../../../config/koneksi.php';

if (isset($_POST['id_klasifikasi'])) {
    $id_klasifikasi = $_POST['id_klasifikasi'];
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $uraian = $_POST['uraian'];

    $query = "UPDATE tbl_klasifikasi SET kode = '$kode', nama = '$nama', uraian = '$uraian' WHERE id_klasifikasi = '$id_klasifikasi'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../klasifikasi.php?info=update");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
