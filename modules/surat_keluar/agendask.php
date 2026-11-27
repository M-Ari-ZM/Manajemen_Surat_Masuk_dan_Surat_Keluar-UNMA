<?php
session_start();
include_once '../../config/koneksi.php';

// Redirect ke login jika belum login
if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include '../../layouts/header.php';
include '../../layouts/navbar.php';
include '../../layouts/sidebar.php';

// Fungsi untuk format tanggal ke Bahasa Indonesia
// indoDate() telah dimuat secara terpusat dari config/helpers.php

// Cek apakah form disubmit
$formSubmitted = isset($_POST['submit']);
$result = [];

if ($formSubmitted) {
    $dari_tanggal = $_POST['dari_tanggal'];
    $sampai_tanggal = $_POST['sampai_tanggal'];

    if ($dari_tanggal !== "" && $sampai_tanggal !== "") {
        $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE tgl_catat BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container">
            <h3 class="mb-3">Cetak Agenda Surat Keluar</h3>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <!-- Filter Form -->
            <form method="post" action="">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="dari_tanggal">Dari Tanggal</label>
                        <input type="date" class="form-control" name="dari_tanggal" value="<?= $formSubmitted ? $dari_tanggal : '' ?>" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="sampai_tanggal">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="sampai_tanggal" value="<?= $formSubmitted ? $sampai_tanggal : '' ?>" required>
                    </div>
                    <div class="form-group col-md-2">
                        <button type="submit" name="submit" class="btn btn-success btn-block">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Notifikasi Tanggal -->
            <?php if ($formSubmitted): ?>
                <div class="alert alert-info mt-3" role="alert">
                    Agenda Surat Masuk dari tanggal <strong><?= indoDate($dari_tanggal) ?></strong> sampai dengan tanggal <strong><?= indoDate($sampai_tanggal) ?></strong>.
                </div>
            <?php endif; ?>

            <!-- Data Tabel -->
            <?php if ($formSubmitted): ?>
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between">
                        <h5>Data Agenda Surat Masuk</h5>
                        <a href="actions/printask.php?dari_tanggal=<?= $dari_tanggal ?>&sampai_tanggal=<?= $sampai_tanggal ?>" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fas fa-print"></i> Cetak
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if (count($result) > 0): ?>
                            <table class="table table-bordered table-striped">
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
                                    <?php foreach ($result as $row): ?>
                                        <tr>
                                            <td><?= $row['no_agenda']; ?></td>
                                            <td><?= $row['isi']; ?></td>
                                            <td><?= $row['tujuan']; ?></td>
                                            <td><?= $row['no_surat']; ?></td>
                                            <td><?= $row['tgl_surat']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['id_user'] == 1) {
                                                    echo '<span>Administrator</span>';
                                                } else {
                                                    $id_user = $row['id_user'];
                                                    $query3 = mysqli_query($koneksi, "SELECT nama FROM tbl_user WHERE id_user='$id_user'");
                                                    list($nama) = mysqli_fetch_array($query3);
                                                    echo '<span class="badge bg-secondary">' . htmlspecialchars($nama) . '</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $row['keterangan']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                Tidak ada data pada rentang tanggal ini.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../layouts/footer.php'; ?>
