<?php
session_start();
include_once '../../../config/koneksi.php';

// Cek session admin
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: ../login.php");
    exit();
}

// indoDate() telah dimuat secara terpusat dari config/helpers.php

$dari_tanggal = isset($_GET['dari_tanggal']) ? $_GET['dari_tanggal'] : '';
$sampai_tanggal = isset($_GET['sampai_tanggal']) ? $_GET['sampai_tanggal'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$queryString = "SELECT * FROM tbl_surat_masuk WHERE 1=1 ";
if ($dari_tanggal != '' && $sampai_tanggal != '') {
    $queryString .= " AND tgl_diterima BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ";
}
if ($search != '') {
    $search_esc = mysqli_real_escape_string($koneksi, $search);
    $queryString .= " AND (no_surat LIKE '%$search_esc%' OR no_agenda LIKE '%$search_esc%')";
}

$query = mysqli_query($koneksi, $queryString);
if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

include '../../../layouts/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Surat Masuk</title>
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
    echo '<img class="logodisp" src="../../../upload/' . htmlspecialchars($logo) . '" alt="Logo">';
    ?>
    <div class="header-text">
        <div class="up"><?php echo htmlspecialchars($institusi); ?></div>
        <div id="nama"><?php echo htmlspecialchars($nama); ?></div>
        <div id="alamat"><?php echo htmlspecialchars($alamat); ?></div>
    </div>
</div>

<hr style="border:1px solid #000; margin:10px 0;">

<?php if ($dari_tanggal && $sampai_tanggal): ?>
    <p><strong>Periode:</strong> <?= indoDate($dari_tanggal) ?> s.d <?= indoDate($sampai_tanggal) ?></p>
<?php endif; ?>

<?php if ($search): ?>
    <p><strong>Pencarian:</strong> <?= htmlspecialchars($search) ?></p>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Tgl. Diterima</th>
            <th>No. Surat / Tgl Surat</th>
            <th>Asal Surat</th>
            <th>Isi</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    if (mysqli_num_rows($query) > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= indoDate($data['tgl_diterima']) ?></td>
                <td>
                    <?= htmlspecialchars($data['no_surat']) ?><br>
                    <?= indoDate($data['tgl_surat']) ?>
                </td>
                <td><?= htmlspecialchars($data['asal_surat']) ?></td>
                <td><?= htmlspecialchars($data['isi']) ?></td>
                <td>
                    <?php if (!empty($data['file']) && file_exists("../../../upload/surat_masuk/" . $data['file'])): ?>
                        <a href="../../../upload/surat_masuk/<?= htmlspecialchars($data['file']) ?>" target="_blank" class="text-primary">Lihat File</a>
                    <?php else: ?>
                        <em>-</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
        }
    } else {
        echo '<tr><td colspan="6" class="text-center">Data tidak ditemukan</td></tr>';
    }
    ?>
    </tbody>
</table>

</body>
</html>
