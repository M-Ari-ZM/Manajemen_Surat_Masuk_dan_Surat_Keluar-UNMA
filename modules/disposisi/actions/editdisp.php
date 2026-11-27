<?php
include_once '../../../config/koneksi.php'; 

if (isset($_POST['id_disposisi'])) {
    $id_disposisi = $_POST['id_disposisi'];
    $tujuan = $_POST['tujuan'];
    $isi_disposisi = $_POST['isi_disposisi'];
    $batas_waktu = $_POST['batas_waktu'];
    $catatan = $_POST['catatan'];
    $sifat = $_POST['sifat'];

    $query = "UPDATE tbl_disposisi SET 
                tujuan = '$tujuan',
                isi_disposisi = '$isi_disposisi',
                batas_waktu = '$batas_waktu',
                catatan = '$catatan',
                sifat = '$sifat'
              WHERE id_disposisi = '$id_disposisi'";
    if (mysqli_query($koneksi, $query)) {
        header("Location: ../disposisi.php?info=update"); 
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} else {
    echo "ID disposisi tidak ditemukan!";
}
?>
