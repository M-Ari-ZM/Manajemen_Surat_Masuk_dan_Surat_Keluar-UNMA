<?php
session_start();
include_once '../../../config/koneksi.php';
include '../../../layouts/header.php';

// indoDate() telah dimuat secara terpusat dari config/helpers.php

if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: ../login.php");
    die();
}

$dari_tanggal = isset($_GET['dari_tanggal']) ? $_GET['dari_tanggal'] : '';
$sampai_tanggal = isset($_GET['sampai_tanggal']) ? $_GET['sampai_tanggal'] : '';
$result = [];

if ($dari_tanggal !== '' && $sampai_tanggal !== '') {
    $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE tgl_catat BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
    if ($query) {
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Disposisi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #nama { font-size: 20px; text-transform: uppercase; margin: 2px 0; font-weight: bold; }
        #alamat { font-size: 14px; margin: 2px 0; }
        .up { font-size: 17px; font-weight: normal; margin: 2px 0; }
        .logodisp { width: 80px; height: auto; margin-right: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        table td, table th { border: 1px solid #000; padding: 5px; vertical-align: top; }
        .text-center { text-align: center; }
        .header-container { display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .header-text { text-align: center; line-height: 1.3; }
    </style>
</head>
<body onload="window.print()">

<div class="header-container">
    <?php
    $query2 = mysqli_query($koneksi, "SELECT institusi, nama, alamat, logo FROM tbl_instansi");
    list($institusi, $nama, $alamat, $logo) = mysqli_fetch_array($query2);
    echo '<img class="logodisp" src="../../../upload/' . $logo . '" alt="Logo">';
    ?>
    <div class="header-text">
        <div class="up"><?php echo $institusi; ?></div>
        <div id="nama"><?php echo $nama; ?></div>
        <div id="alamat"><?php echo $alamat; ?></div>
    </div>
</div>

<hr style="border:1px solid #000; margin:10px 0;">
<table class="table table-bordered">
    <thead>
       <tr>
         <th>No</th>
         <th>Isi Ringkas</th>
         <th>Tujuan Surat</th>
         <th>No. Surat</th>
         <th>Tgl Surat</th>
         <th>Pengelola</th>
         <th>Keterangan</th>
       </tr>
    </thead>
    <tbody>
     <tbody>
<?php
if (!empty($result)) {
    $no = 1;
    foreach ($result as $row) {
        // Ambil nama user
        $nama_user = 'Administrator';
        if ($row['id_user'] != 1) {
            $id_user = $row['id_user'];
            $query3 = mysqli_query($koneksi, "SELECT nama FROM tbl_user WHERE id_user='$id_user'");
            if ($query3 && mysqli_num_rows($query3) > 0) {
                $data_user = mysqli_fetch_assoc($query3);
                $nama_user = $data_user['nama'];
            }
        }
?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['isi']); ?></td>
            <td><?= htmlspecialchars($row['tujuan']); ?></td>
            <td><?= htmlspecialchars($row['no_surat']); ?></td>
            <td><?= indoDate($row['tgl_surat']); ?></td>
            <td><?= htmlspecialchars($nama_user); ?></td>
            <td><?= htmlspecialchars($row['keterangan']); ?></td>
        </tr>
<?php
    }
} else {
    echo '<tr><td colspan="7" class="text-center">Tidak ada data pada rentang tanggal ini.</td></tr>';
}
?>
</tbody>

    </tbody>
</table>
</body>
</html>
