<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

include_once '../../config/koneksi.php';

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Buat header tabel
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Perihal');
$sheet->setCellValue('C1', 'Nomor SK');
$sheet->setCellValue('D1', 'Tanggal SK');
$sheet->setCellValue('E1', 'Sumber SK');

// Ambil parameter filter dari URL
$sumber_sk = isset($_GET['sumber_sk']) ? mysqli_real_escape_string($koneksi, $_GET['sumber_sk']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

// Buat WHERE clause
$whereClause = "WHERE 1=1";

if (!empty($sumber_sk)) {
    $whereClause .= " AND sumber_sk = '$sumber_sk'";
}

if (!empty($search)) {
    $whereClause .= " AND (perihal LIKE '%$search%' OR no_sk LIKE '%$search%' OR sumber_sk LIKE '%$search%')";
}

// Ambil data dari database dengan filter
$query = mysqli_query($koneksi, "SELECT * FROM sk $whereClause ORDER BY id_sk ASC");

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

// Header khusus CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="data_sk.csv"');
header('Cache-Control: max-age=0');

$writer = new Csv($spreadsheet);
$writer->setDelimiter(',');
$writer->setEnclosure('"');
$writer->setLineEnding("\r\n");
$writer->setUseBOM(true);
$writer->save('php://output');
exit;
