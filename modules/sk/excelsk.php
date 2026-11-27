<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once '../../config/koneksi.php';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Perihal');
$sheet->setCellValue('C1', 'Nomor SK');
$sheet->setCellValue('D1', 'Tanggal SK');
$sheet->setCellValue('E1', 'Sumber SK');

// Ambil filter dari URL
$sumber_sk = isset($_GET['sumber_sk']) ? mysqli_real_escape_string($koneksi, $_GET['sumber_sk']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Buat WHERE clause
$whereClause = "WHERE 1=1";

if ($sumber_sk !== '') {
    $whereClause .= " AND sumber_sk = '$sumber_sk'";
}

if ($search !== '') {
    $whereClause .= " AND (sumber_sk LIKE '%$search%' OR no_sk LIKE '%$search%')";
}

// Jalankan query
$query = mysqli_query($koneksi, "SELECT * FROM sk $whereClause ORDER BY id_sk ASC");

// Tulis data ke dalam spreadsheet
$row = 2;
$no = 1;

while ($data = mysqli_fetch_assoc($query)) {
    $sheet->setCellValue('A' . $row, $no++);
    $sheet->setCellValue('B' . $row, $data['perihal']);
    $sheet->setCellValue('C' . $row, $data['no_sk']);
    $sheet->setCellValue('D' . $row, $data['tgl_sk']);
    $sheet->setCellValue('E' . $row, $data['sumber_sk']);
    $row++;
}

// Output ke browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_sk.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
