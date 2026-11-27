<?php
session_start();
include '../koneksi.php';

if (isset($_POST['tujuan'])) {
    //kode prepared statement yang baru
    $tujuan = $_POST['tujuan'];
    $isi_disposisi = $_POST['isi_disposisi'];
    $batas_waktu = $_POST['batas_waktu'];
    $catatan = $_POST['catatan'];
    $sifat = $_POST['sifat'];
    $id_surat = $_POST['id_surat'];
    $id_user = $_SESSION['id_user']; 

    $stmt = $koneksi->prepare("INSERT INTO tbl_disposisi (tujuan, isi_disposisi, batas_waktu, catatan, sifat, id_surat, id_user) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $tujuan, $isi_disposisi, $batas_waktu, $catatan, $sifat, $id_surat, $id_user);

    if ($stmt->execute()) {
        header("Location: ../disposisi.php?info=simpan");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
