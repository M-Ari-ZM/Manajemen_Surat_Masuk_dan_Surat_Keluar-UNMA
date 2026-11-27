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
// indoDate() telah dimuat secara terpusat dari config/helpers.php
$formSubmitted = isset($_POST['submit']);
$result = [];
$dari_tanggal = '';
$sampai_tanggal = '';

if ($formSubmitted) {
    $dari_tanggal = $_POST['dari_tanggal'];
    $sampai_tanggal = $_POST['sampai_tanggal'];

    if ($dari_tanggal !== "" && $sampai_tanggal !== "") {
        $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE tgl_diterima BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER BY tgl_diterima DESC");
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
    }
} else {
    $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk ORDER BY tgl_diterima DESC LIMIT 10");
    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container">
            <h3 class="mb-3">Galeri Surat Masuk</h3>
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

                    <?php if ($formSubmitted): ?>
                    <div class="form-group col-md-2">
                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-danger btn-block">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Notifikasi Tanggal -->
            <?php if ($formSubmitted): ?>
                <div class="alert alert-info mt-3" role="alert">
                    Galeri Surat Masuk dari tanggal <strong><?= indoDate($dari_tanggal) ?></strong> sampai dengan tanggal <strong><?= indoDate($sampai_tanggal) ?></strong>.
                </div>
            <?php endif; ?>

            <!-- Data Tabel -->
       <div class="row">
    <?php if (!empty($result)): ?>
        <?php foreach ($result as $surat): ?>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <?php
                        $file = '../../upload/surat_masuk/' . $surat['file'];
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    ?>
                    
                    <!-- Tampilkan thumbnail gambar atau ikon -->
                  <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?= $base_url . 'upload/surat_masuk/' . $surat['file'] ?>" class="card-img-top" alt="Surat Masuk" style="height: 250px; object-fit: cover;">
                    <?php elseif ($ext === 'pdf'): ?>
                        <img src="assets/img/pdf.png" class="card-img-top" alt="PDF File" style="height: 250px; object-fit: contain; padding: 20px;">
                    <?php else: ?>
                        <img src="assets/img/word.png" class="card-img-top" alt="File" style="height: 250px; object-fit: contain; padding: 20px;">
                    <?php endif; ?>

                    <div class="card-body text-center">
                        <p class="card-text"><?= htmlspecialchars($surat['file']) ?></p>
                        <a href="detail.php?id_surat=<?= $surat['id_surat'] ?>" class="btn btn-success btn-sm">Lihat Detail File</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <div class="alert alert-warning">Tidak ada data surat ditemukan.</div>
                </div>
            <?php endif; ?>
        </div>


        </div>
    </div>
</div>


<?php include '../../layouts/footer.php'; ?>