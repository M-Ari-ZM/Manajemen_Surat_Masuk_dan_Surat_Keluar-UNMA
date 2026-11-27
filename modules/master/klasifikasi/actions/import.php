<?php
session_start();
include_once '../../../../config/koneksi.php';

require '../vendor/autoload.php'; // Pastikan path ini benar

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['admin'] != 1 && $_SESSION['admin'] != 2) {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location.href='../logout.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Akses langsung tidak diperbolehkan.";
    exit();
}

if (!isset($_FILES['file_excel']) || $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['errUpload'] = 'ERROR! File gagal diunggah.';
    header("Location: ../klasifikasi.php?info=gagal");
    exit();
}

$fileTmpPath = $_FILES['file_excel']['tmp_name'];
$fileName = $_FILES['file_excel']['name'];
$ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$id_user = $_SESSION['id_user'];

// Fungsi untuk insert data
function insertData($koneksi, $kode, $nama, $uraian, $id_user) {
    $kode = mysqli_real_escape_string($koneksi, trim($kode));
    $nama = mysqli_real_escape_string($koneksi, trim($nama));
    $uraian = mysqli_real_escape_string($koneksi, trim($uraian));
    
    if ($kode !== '' && $nama !== '' && $uraian !== '') {
        $cek = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE kode='$kode'");
        if (mysqli_num_rows($cek) == 0) {
            mysqli_query($koneksi, "INSERT INTO tbl_klasifikasi (kode, nama, uraian, id_user) VALUES ('$kode', '$nama', '$uraian', '$id_user')");
        }
    }
}

if ($ext === 'csv') {
    $handle = fopen($fileTmpPath, 'r');
    $baris = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $baris++;
        if ($baris == 1 && strtolower(trim($data[0])) == 'kode') continue;

        $kode   = $data[0] ?? '';
        $nama   = $data[1] ?? '';
        $uraian = $data[2] ?? '';

        insertData($koneksi, $kode, $nama, $uraian, $id_user);
    }

    fclose($handle);

} elseif ($ext === 'xlsx' || $ext === 'xls') {
    try {
        $spreadsheet = IOFactory::load($fileTmpPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        foreach ($rows as $index => $row) {
            if ($index === 0 && strtolower(trim($row[0])) == 'kode') continue;

            $kode   = $row[0] ?? '';
            $nama   = $row[1] ?? '';
            $uraian = $row[2] ?? '';

            insertData($koneksi, $kode, $nama, $uraian, $id_user);
        }

    } catch (Exception $e) {
        $_SESSION['errUpload'] = 'ERROR saat membaca file Excel: ' . $e->getMessage();
        header("Location: ../klasifikasi.php?info=gagal");
        exit();
    }

} else {
    $_SESSION['errFormat'] = 'ERROR! Format file harus CSV atau XLSX.';
    header("Location: ../klasifikasi.php?info=gagal");
    exit();
}

$_SESSION['succUpload'] = 'SUKSES! Data berhasil diimport.';
header("Location: ../klasifikasi.php?info=simpan");
exit();
