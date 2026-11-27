<?php
session_start();
include_once '../../../config/koneksi.php';
include '../../../layouts/header.php';

// indoDate() telah dimuat secara terpusat dari config/helpers.php

if(empty($_SESSION['admin'])){
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: ../login.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Disposisi</title>
<style>
    body { font-family: Arial, sans-serif; }
    #nama { font-size: 20px; text-transform: uppercase; }
    #alamat { font-size:14px; }
    .up { font-size:17px; font-weight:normal; }
    .status { font-size:17px; }
    .logodisp { width:80px; height:auto; }
    table { width:100%; border-collapse:collapse; margin-top:10px; }
    table td, table th { border:1px solid #000; padding:5px; vertical-align:top; }
    #lbr { font-size:16px; }
    #tbl {
    font-size: 13px;
}
#nama {
    font-size: 20px;
    text-transform: uppercase;
    margin: 2px 0;
    font-weight: bold; /* <-- Tambahkan ini untuk bold */
}

.text-center {
    text-align: center; /* <-- Tambahkan class ini agar teks benar-benar di tengah */
}

.header-container {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
}

.header-text {
    text-align: center;
    line-height: 1.3;
}

#nama {
    font-size: 20px;
    text-transform: uppercase;
    margin: 2px 0;
}

#alamat {
    font-size: 14px;
    margin: 2px 0;
}

.up {
    font-size: 17px;
    font-weight: normal;
    margin: 2px 0;
}

.logodisp {
    width: 80px;
    height: auto;
    margin-right: 15px;
}

</style>
</head>
<body onload="window.print()">

<div class="header-container">
    <?php
    $query2 = mysqli_query($koneksi, "SELECT institusi, nama, alamat, logo FROM tbl_instansi");
    list($institusi, $nama, $alamat, $logo) = mysqli_fetch_array($query2);
    echo '<img class="logodisp" src="../../../upload/'.$logo.'" alt="Logo">';
    ?>
    <div class="header-text">
        <div class="up"><?php echo $institusi; ?></div>
        <div id="nama"><?php echo $nama; ?></div>
        <div id="alamat"><?php echo $alamat; ?></div>
    </div>
</div>

    <hr style="border:1px solid #000; margin:10px 0;">

    <?php
    $id_surat = mysqli_real_escape_string($koneksi, $_REQUEST['id_surat']);
    $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE id_surat='$id_surat'");

    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_array($query)){
            echo '
            <table id="tbl">
                <tbody>
                    <tr>
                        <td class="text-center" id="lbr" colspan="5"><b>LEMBAR DISPOSISI</b></td>
                    </tr>
                    <tr>
                        <td>Tanggal Surat</td>
                        <td colspan="2">: '.indoDate($row['tgl_surat']).'</td>
                    </tr>
                    <tr>
                        <td>Nomor Surat</td>
                        <td colspan="2">: '.$row['no_surat'].'</td>
                    </tr>
                    <tr>
                        <td>Asal Surat</td>
                        <td colspan="2">: '.$row['asal_surat'].'</td>
                    </tr>
                    <tr>
                        <td>Isi Ringkas</td>
                        <td colspan="2">: '.$row['isi'].'</td>
                    </tr>
                    <tr>
                        <td>Diterima Tanggal</td>
                        <td>: '.indoDate($row['tgl_diterima']).'</td>
                        <td>No. Agenda: '.$row['no_agenda'].'</td>
                    </tr>
                    <tr>
                        <td>Tanggal Penyelesaian</td>
                        <td colspan="2">: </td>
                    </tr>';
                        $query3 = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi 
                            JOIN tbl_surat_masuk ON tbl_disposisi.id_surat = tbl_surat_masuk.id_surat 
                            WHERE tbl_disposisi.id_surat='$id_surat'");

                        if(mysqli_num_rows($query3) > 0){
                            $row = mysqli_fetch_array($query3);
                            echo '
                    <tr class="isi">
                        <td colspan="2">
                            <strong>Isi Disposisi :</strong><br/>'.$row['isi_disposisi'].'
                            <div style="height: 50px;"></div>
                            <strong>Catatan</strong> :<br/> '.$row['catatan'].'
                            <div style="height: 150px;"></div>
                        </td>
                        <td><strong>Diteruskan Kepada</strong> : <br/>'.$row['tujuan'].'</td>
                    </tr>';
                        } else {
                            echo '
                    <tr class="isi">
                        <td colspan="2"><strong>Isi Disposisi :</strong></td>
                        <td><strong>Diteruskan Kepada</strong> : </td>
                    </tr>';
                        }
                    echo '
                </tbody>
            </table>';
        } 
    }
    ?>
</div>
</body>