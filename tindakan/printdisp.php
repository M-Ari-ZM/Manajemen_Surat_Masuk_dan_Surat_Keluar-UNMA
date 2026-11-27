<?php
session_start();
include '../koneksi.php';
include '../layouts/header.php';

function indoDate($date) {
    $bulan = array(
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    );
    $pecah = explode('-', $date);
    return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
}

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
    #nama { font-size: 20px; text-transform: uppercase; font-weight: bold; margin: 2px 0; }
    #alamat { font-size:14px; margin: 2px 0; }
    .up { font-size:17px; font-weight:normal; margin: 2px 0; }
    .logodisp { width:80px; height:auto; margin-right: 15px; }

    table { width:100%; border-collapse:collapse; margin-top:10px; }
    table td, table th { border:1px solid #000; padding:5px; font-size: 12px; }

    #lbr { font-size:16px; text-align: center; }

    .header-container { display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
    .header-text { text-align: center; line-height: 1.3; }

    .label-cell { border-right: none; }
    .data-cell {
        border-left: none;
        padding-left: 10px;
        padding-top: 6px;
        vertical-align: top;
    }
   @page {
    size: 165mm 215mm;
    margin: 10mm; /* Atur sesuai kebutuhan */
}


@media print {
    body {
        font-size: 12px;
    }

    .header-container {
        page-break-inside: avoid;
    }

    table {
        page-break-inside: auto;
    }

    table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    body, html {
        margin: 0;
        padding: 0;
    }
}
</style>
</head>
<body onload="window.print()">

<div class="header-container">
    <?php
    $query2 = mysqli_query($koneksi, "SELECT institusi, nama, alamat, logo FROM tbl_instansi");
    list($institusi, $nama, $alamat, $logo) = mysqli_fetch_array($query2);
    echo '<img class="logodisp" src="../upload/'.$logo.'" alt="Logo">';
    ?>
    <div class="header-text">
        <div class="up"><?php echo $institusi; ?></div>
        <div id="nama"><?php echo $nama; ?></div>
        <div id="alamat"><?php echo $alamat; ?></div>
    </div>
</div>

<hr style="border:1px solid #000; margin:10px 0;">

<?php
$id_surat = mysqli_real_escape_string($koneksi, $_GET['id_surat']);
$query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE id_surat = '$id_surat'");
$data_surat = mysqli_fetch_array($query);

$query_disposisi = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi WHERE id_surat = '$id_surat'");
?>

<table>
    <tr>
        <td id="lbr" colspan="10"><b>KARTU DISPOSISI</b></td>
    </tr>
    <tr>
        <td colspan="4">INDEKS</td>
        <td colspan="6"><strong>TGL. PENYELESAIAN:</strong>
            <?php  
            $batas = mysqli_fetch_array(mysqli_query($koneksi, "SELECT batas_waktu FROM tbl_disposisi WHERE id_surat = '$id_surat' LIMIT 1"));
            echo ' ' . ($batas ? indoDate($batas['batas_waktu']) : '-');
            ?>
        </td>
    </tr>
    <tr>
        <td class="label-cell" colspan="2">DARI</td>
        <td class="data-cell" colspan="3">: <?= $data_surat['asal_surat']; ?></td>
        
    </tr>
    <tr>
        <td class="label-cell" colspan="2">PERIHAL</td>
        <td class="data-cell" colspan="3">: <?= $data_surat['isi']; ?></td>
        
    </tr>
    <tr>
        <td class="label-cell" colspan="2">TGL. SURAT</td>
        <td class="data-cell" colspan="3">: <?= indoDate($data_surat['tgl_surat']); ?></td>
       
    </tr>
    <tr>
        <td class="label-cell" colspan="2">NO. SURAT</td>
        <td class="data-cell" colspan="3">: <?= $data_surat['no_surat']; ?></td>
       
    </tr>

    <?php
    if (mysqli_num_rows($query_disposisi) > 0) {
        while ($disp = mysqli_fetch_array($query_disposisi)) {
            echo '
            <tr>
                <td colspan="4" style="height: 200px; vertical-align: top;">
                    <strong>INSTRUKSI/INFORMASI</strong><br/>' . $disp['isi_disposisi'] . '
                </td>
                <td colspan="6" style="height: 200px; vertical-align: top;">
                    <strong>DITERUSKAN KEPADA</strong><br/>' . $disp['tujuan'] . '
                </td>
            </tr>';
        }
    }
    ?>
</table>

</body>
</html>
