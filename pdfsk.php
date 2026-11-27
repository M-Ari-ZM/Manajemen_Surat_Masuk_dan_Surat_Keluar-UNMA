<?php
require('vendor/fpdf.php');
include 'koneksi.php';

class PDF extends FPDF {
    protected $judul = 'Data Surat Keputusan';

    function setJudul($judul) {
        $this->judul = $judul;
    }

    // Header
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, $this->judul, 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Ln(5);

        // Table header
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 10, 'No', 1, 0, 'C');
        $this->Cell(60, 10, 'Perihal', 1, 0, 'C');
        $this->Cell(40, 10, 'Nomor SK', 1, 0, 'C');
        $this->Cell(30, 10, 'Tanggal SK', 1, 0, 'C');
        $this->Cell(40, 10, 'Sumber SK', 1, 1, 'C');
    }

    // Footer
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Ambil filter dari URL (GET)
$sumber_sk = isset($_GET['sumber_sk']) ? mysqli_real_escape_string($koneksi, $_GET['sumber_sk']) : '';
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';

$whereClause = "WHERE 1=1";

if (!empty($sumber_sk)) {
    $whereClause .= " AND sumber_sk = '$sumber_sk'";
}

if (!empty($search)) {
    $whereClause .= " AND (perihal LIKE '%$search%' OR no_sk LIKE '%$search%' OR sumber_sk LIKE '%$search%')";
}

// Query ambil data
$query = "SELECT * FROM sk $whereClause ORDER BY tgl_sk DESC";
$result = mysqli_query($koneksi, $query);

// Tentukan judul berdasarkan filter
$judul = "Data Surat Keputusan";
if (!empty($sumber_sk)) {
    $judul .= " (" . $sumber_sk . ")";
}

$pdf = new PDF();
$pdf->setJudul($judul);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $pdf->Cell(10, 8, $no++, 1, 0, 'C');
    $pdf->Cell(60, 8, $row['perihal'], 1);
    $pdf->Cell(40, 8, $row['no_sk'], 1);
    $pdf->Cell(30, 8, date('d-m-Y', strtotime($row['tgl_sk'])), 1);
    $pdf->Cell(40, 8, $row['sumber_sk'], 1, 1);
}

// Output file
$filename = 'data_sk';
if (!empty($sumber_sk)) {
    $filename .= '_' . strtolower($sumber_sk);
}
$pdf->Output('D', $filename . '.pdf');
exit;
?>
