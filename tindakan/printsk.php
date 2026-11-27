<?php
session_start();
include '../koneksi.php';
include '../layouts/header.php';

function indoDate($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];
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
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
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

    .logodisp {
        width: 80px;
        height: auto;
        margin-right: 15px;
    }

    .up {
        font-size: 17px;
        font-weight: normal;
        margin: 2px 0;
    }

    #nama {
        font-size: 20px;
        text-transform: uppercase;
        margin: 2px 0;
        font-weight: bold;
    }

    #alamat {
        font-size: 14px;
        margin: 2px 0;
    }

    hr {
        border: 1px solid #000;
        margin: 10px 0 20px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    table td, table th {
        border: 1px solid #000;
        padding: 6px 8px;
        vertical-align: top;
    }

    .text-center {
        text-align: center;
    }

    .title {
        font-size: 16px;
        font-weight: bold;
        padding: 10px 0;
    }

    .spacer-100 {
        height: 100px;
    }

    .spacer-150 {
        height: 150px;
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

<hr>

<?php
$id_surat = mysqli_real_escape_string($koneksi, $_REQUEST['id_surat']);
$query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE id_surat='$id_surat'");

if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_array($query)){
        echo '
        <table>
            <tr>
                <td colspan="5" class="text-center title">LEMBAR DISPOSISI</td>
            </tr>
            <tr>
                <td width="20%">No. Agenda</td>
                <td colspan="4">: '.$row['no_agenda'].'</td>
            </tr>
            <tr>
                <td>No. Surat</td>
                <td colspan="4">: '.$row['no_surat'].'</td>
            </tr>
            <tr>
                <td>Tgl. Surat</td>
                <td colspan="4">: '.indoDate($row['tgl_surat']).'</td>
            </tr>
            <tr>
                <td>Tgl. Catat</td>
                <td colspan="4">: '.$row['tgl_catat'].'</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td colspan="4">: '.$row['keterangan'].'</td>
            </tr>';

        $query3 = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi 
            JOIN tbl_surat_masuk ON tbl_disposisi.id_surat = tbl_surat_masuk.id_surat 
            WHERE tbl_disposisi.id_surat='$id_surat'");

        if(mysqli_num_rows($query3) > 0){
            $row3 = mysqli_fetch_array($query3);
            echo '
            <tr>
            <td colspan="3">  
                <strong>Instruksi/Informasi:</strong><br>
                '.$row3['isi'].'<br><br>
                <div class="spacer-100"></div>
                <strong>Catatan:</strong><br>
                '.$row3['catatan'].'
                <div class="spacer-150"></div>
            </td>
            <td colspan="2">
                <strong>Diteruskan Kepada:</strong><br>
                '.$row3['tujuan'].'
            </td>
        </tr>';
        } else {
            echo '
            <tr>
                <td colspan="3" style="height: 200px; vertical-align: top;"><strong>Instruksi/Informasi:</strong><br>'.$row['isi'].'</td>
                <td colspan="2" style="height: 200px; vertical-align: top;"><strong>Diteruskan Kepada:</strong><br>'.$row['tujuan'].'</td>
            </tr>';
        }

        echo '</table>';
    }
}
?>

</body>
</html>
