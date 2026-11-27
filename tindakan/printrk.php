<?php
session_start();
include '../koneksi.php';

// Fungsi format tanggal ke Bahasa Indonesia
function indoDate($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    );
    $pecah = explode('-', $date);
    if(count($pecah) < 3) return $date; // jika format salah, kembalikan apa adanya
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}

if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: ../login.php");
    exit();
}

// Ambil parameter filter
$dari_tanggal = isset($_GET['dari_tanggal']) ? $_GET['dari_tanggal'] : '';
$sampai_tanggal = isset($_GET['sampai_tanggal']) ? $_GET['sampai_tanggal'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Buat kondisi WHERE sesuai filter
$where = [];
if ($dari_tanggal !== '' && $sampai_tanggal !== '') {
    $where[] = "tgl_catat BETWEEN '$dari_tanggal' AND '$sampai_tanggal'";
}
if ($search !== '') {
    $searchEscaped = mysqli_real_escape_string($koneksi, $search);
    $where[] = "(no_surat LIKE '%$searchEscaped%' OR no_agenda LIKE '%$searchEscaped%')";
}
$whereSql = '';
if (count($where) > 0) {
    $whereSql = "WHERE " . implode(' AND ', $where);
}

// Query data surat keluar dengan filter
$query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar $whereSql ORDER BY tgl_catat DESC");
if (!$query) {
    die("Query Error: " . mysqli_error($koneksi));
}

// Ambil data instansi untuk header
$instansiQuery = mysqli_query($koneksi, "SELECT institusi, nama, alamat, logo FROM tbl_instansi");
$instansi = mysqli_fetch_assoc($instansiQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Cetak Rekap Surat Keluar</title>
    <style>
        body { font-family: Arial, sans-serif; }
        #nama { font-size: 20px; text-transform: uppercase; margin: 2px 0; font-weight: bold; }
        #alamat { font-size: 14px; margin: 2px 0; }
        .up { font-size: 17px; font-weight: normal; margin: 2px 0; }
        .logodisp { width: 80px; height: auto; margin-right: 15px; }
        .header-container { display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .header-text { text-align: center; line-height: 1.3; }
        hr { border: 1px solid #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        table th, table td { border: 1px solid #000; padding: 5px; vertical-align: top; }
    </style>
</head>
<body onload="window.print()">

<div class="header-container">
    <?php if (!empty($instansi['logo'])): ?>
        <img class="logodisp" src="../upload/<?= htmlspecialchars($instansi['logo']) ?>" alt="Logo">
    <?php endif; ?>
    <div class="header-text">
        <div class="up"><?= htmlspecialchars($instansi['institusi']) ?></div>
        <div id="nama"><?= htmlspecialchars($instansi['nama']) ?></div>
        <div id="alamat"><?= htmlspecialchars($instansi['alamat']) ?></div>
    </div>
</div>

<hr>

<?php if ($dari_tanggal && $sampai_tanggal): ?>
    <p><strong>Agenda Surat Keluar dari tanggal <?= indoDate($dari_tanggal) ?> sampai dengan tanggal <?= indoDate($sampai_tanggal) ?>.</strong></p>
<?php elseif ($search): ?>
    <p><strong>Hasil pencarian untuk: <?= htmlspecialchars($search) ?></strong></p>
<?php else: ?>
    <p><strong>Semua data Surat Keluar</strong></p>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>No Agenda</th>
            <th>Tujuan</th>
            <th>No. Surat</th>
            <th>Isi</th>
            <th>Tgl Surat</th>
            <th>Tgl Catat</th>
            <th>Keterangan</th>
            <th>File</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; ?>
        <?php while ($data = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $data['no_agenda'] ?></td>
                <td><?= htmlspecialchars($data['tujuan']) ?></td>
                <td><?= htmlspecialchars($data['no_surat']) ?><br><?= date('d-m-Y', strtotime($data['tgl_surat'])) ?></td>
                <td><?= htmlspecialchars($data['isi']) ?></td>
                <td><?= $data['tgl_surat'] ?></td>
                <td><?= $data['tgl_catat'] ?></td>
                <td><?= htmlspecialchars($data['keterangan']) ?></td>
                <td>
                    <?php if (!empty($data['file'])): ?>
                        <a href="../upload/surat_keluar/<?= htmlspecialchars($data['file']) ?>" target="_blank">Lihat File</a>
                    <?php else: ?>
                        <em>-</em>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
