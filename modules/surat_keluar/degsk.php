<?php
session_start();
include_once '../../config/koneksi.php'; 
include '../../layouts/header.php';
include '../../layouts/navbar.php';
include '../../layouts/sidebar.php';

// indoDate() telah dimuat secara terpusat dari config/helpers.php

if(empty($_SESSION['admin'])){
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id_surat'])){
    echo "ID Surat tidak ditemukan.";
    exit();
}

$id_surat = mysqli_real_escape_string($koneksi, $_GET['id_surat']);
$query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE id_surat='$id_surat'");

if(mysqli_num_rows($query) == 0){
    echo "Data surat tidak ditemukan.";
    exit();
}

$row = mysqli_fetch_assoc($query);
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid pt-3">
      
      <h3 class="mb-4">Detail Surat Keluar</h3>
      <a href="galerisk.php" class="btn btn-primary mb-4">
          <i class="bi bi-arrow-left"></i> Kembali
      </a>

      <ul class="nav flex-column">
          <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#detailSuratCollapse" role="button" aria-expanded="false" aria-controls="detailSuratCollapse">
                  Tampilkan detail data surat Keluar
                   <i class="fas fa-chevron-down" id="toggleIcon"></i>
              </a>
          </li>
      </ul>

      <div class="collapse show mt-3" id="detailSuratCollapse">
          <div class="card card-body">
              <table class="table table-bordered">
                  <tbody>
                      <tr>
                          <th style="width: 20%;">No. Agenda</th>
                          <td><?= htmlspecialchars($row['no_agenda']) ?></td>
                      </tr>
                       <tr>
                          <th>No. Surat</th>
                          <td><?= htmlspecialchars($row['no_surat']) ?></td>
                      </tr>
                      <tr>
                          <th>Tujuan</th>
                          <td><?= htmlspecialchars($row['tujuan']) ?></td>
                      </tr>
                      <tr>
                          <th>Tanggal Catat</th>
                          <td><?= indoDate($row['tgl_catat']) ?></td>
                      </tr>
                      <tr>
                          <th>Isi Ringkas</th>
                          <td><?= htmlspecialchars($row['isi']) ?></td>
                      </tr>
                      <tr>
                          <th>Keterangan</th>
                          <td><?= htmlspecialchars($row['keterangan']) ?></td>
                      </tr>
                  </tbody>
              </table>
          </div>
      </div>

      <?php 
      if(empty($row['file'])): ?>
          <div class="alert alert-warning mt-3">File lampiran tidak tersedia.</div>
      <?php else: 
          $file = $row['file'];
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          $file_path = '../../upload/surat_keluar/' . $file;

          $image_ext = ['jpg','jpeg','png','gif'];
          $doc_ext = ['doc','docx'];
      ?>

          <?php if(in_array($ext, $image_ext)): ?>
              <img src="<?= $file_path ?>" alt="File Surat Keluar" class="img-thumbnail mb-3 d-block mx-auto" style="max-height: 450px;" />
          <?php elseif(in_array($ext, $doc_ext)): ?>
              <div class="card mb-3">
                  <div class="card-body d-flex align-items-center">
                      <div class="flex-grow-1">
                          <p>File lampiran surat Keluar ini bertipe <strong>document</strong>, silakan klik link di bawah ini untuk melihat file lampiran tersebut.</p>
                          <a href="<?= $file_path ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                      </div>
                      <img src="./assets/img/word.png" alt="Word Icon" class="file-icon ms-3" style="max-width: 100px;" />
                  </div>
              </div>
          <?php else: ?>
              <div class="card mb-3">
                  <div class="card-body d-flex align-items-center">
                      <div class="flex-grow-1">
                          <p>File lampiran surat Keluar ini bertipe <strong>PDF</strong>, silakan klik link di bawah ini untuk melihat file lampiran tersebut.</p>
                          <a href="<?= $file_path ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                      </div>
                      <img src="./assets/img/pdf.png" alt="PDF Icon" class="file-icon ms-3" style="max-width: 100px;" />
                  </div>
              </div>
          <?php endif; ?>
      <?php endif; ?>

    </div>
  </section>
</div>

<script>
  const toggleLink = document.querySelector('[data-toggle="collapse"]');
  const toggleIcon = document.getElementById('toggleIcon');
  const collapseTarget = document.getElementById('detailSuratCollapse');

  if(collapseTarget && toggleIcon){
    $(collapseTarget).on('show.bs.collapse', function () {
      toggleIcon.classList.remove('fa-chevron-down');
      toggleIcon.classList.add('fa-chevron-up');
    });

    $(collapseTarget).on('hide.bs.collapse', function () {
      toggleIcon.classList.remove('fa-chevron-up');
      toggleIcon.classList.add('fa-chevron-down');
    });
  }
</script>


<?php include '../../layouts/footer.php'; ?>
