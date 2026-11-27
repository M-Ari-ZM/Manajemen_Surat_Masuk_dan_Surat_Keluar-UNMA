<?php
session_start();

include '../koneksi.php';

if (isset($_POST['kode'], $_POST['nama'], $_POST['uraian'])) {
    $kode = $_POST['kode'];
    $nama = $_POST['nama'];
    $uraian = $_POST['uraian'];

    $id_user = $_SESSION['id_user']; 

    $query = "INSERT INTO tbl_klasifikasi (kode, nama, uraian, id_user) 
              VALUES ('$kode', '$nama', '$uraian', '$id_user')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: ../klasifikasi.php?info=simpan");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
