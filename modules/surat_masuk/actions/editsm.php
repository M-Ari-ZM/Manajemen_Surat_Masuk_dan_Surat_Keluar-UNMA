<?php
include_once '../../../config/koneksi.php';

if (isset($_POST['id_surat'])) {
    $id_surat = $_POST['id_surat'];
    $no_agenda = $_POST['no_agenda'];
    $no_surat = $_POST['no_surat'];
    $isi = $_POST['isi'];
    $asal_surat = $_POST['asal_surat'];
    $tgl_surat = $_POST['tgl_surat'];
    $keterangan = $_POST['keterangan'];
    
    $cek_agenda = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE no_agenda = '$no_agenda' AND id_surat != '$id_surat'");
    if (mysqli_num_rows($cek_agenda) > 0) {
        echo "<script>alert('Nomor Agenda atau Nomor Surat sudah digunakan!');window.history.back();</script>";
        exit;
    }

    $cek_surat = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE no_surat = '$no_surat' AND id_surat != '$id_surat'");
    if (mysqli_num_rows($cek_surat) > 0) {
        echo "<script>alert('Nomor Agenda atau Nomor Surat sudah digunakan!');window.history.back();</script>";
        exit;
    }


    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $folder = '../../../upload/surat_masuk/';
        $fileName = basename($_FILES['file_surat']['name']);
        $targetFile = $folder . $fileName;

        if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetFile)) {
            $query = "UPDATE tbl_surat_masuk SET 
                no_agenda = '$no_agenda',
                no_surat = '$no_surat',
                isi = '$isi',
                asal_surat = '$asal_surat',
                tgl_surat = '$tgl_surat',
                keterangan = '$keterangan',
                file = '$fileName'
                WHERE id_surat = '$id_surat'";
        } else {
            $query = "UPDATE tbl_surat_masuk SET 
                no_agenda = '$no_agenda',
                no_surat = '$no_surat',
                isi = '$isi',
                asal_surat = '$asal_surat',
                tgl_surat = '$tgl_surat',
                keterangan = '$keterangan'
                WHERE id_surat = '$id_surat'";
        }
    } else {
        $query = "UPDATE tbl_surat_masuk SET 
            no_agenda = '$no_agenda',
            no_surat = '$no_surat',
            isi = '$isi',
            asal_surat = '$asal_surat',
            tgl_surat = '$tgl_surat',
            keterangan = '$keterangan'
            WHERE id_surat = '$id_surat'";
    }

    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    header("location:../jumlah_surat_masuk.php?info=update");
    exit;
}
?>
