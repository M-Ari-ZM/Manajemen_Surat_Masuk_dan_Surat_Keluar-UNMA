<?php
session_start();

if (isset($_GET['info'])) {
  if ($_GET['info'] == "gagal") { ?>
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h5><i class="icon fas fa-exclamation-triangle"></i> Mohon Maaf</h5>
      Login gagal! Username atau password salah.
    </div>
<?php 
  } else if ($_GET['info'] == "logout") { ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h5><i class="icon fas fa-check"></i> Terimakasih</h5>
      Anda telah berhasil logout.
    </div>
<?php 
  } else if ($_GET['info'] == "login") { ?>
    <div class="alert alert-info alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h5><i class="icon fas fa-info"></i> Mohon Maaf</h5>
      Anda harus login terlebih dahulu.
    </div>
<?php 
  }
}
if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}
include_once '../../../config/koneksi.php';
include '../../../layouts/header.php';
include '../../../layouts/navbar.php';
include '../../../layouts/sidebar.php';
?>

<!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
        </div>
      </div>
    </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center"> 
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Manajemen Instansi</h3>
            </div>
          <form method="POST" action="svinstan.php" enctype="multipart/form-data">
          <div class="card-body">
             <?php
              include_once '../../../config/koneksi.php';

              $query = mysqli_query($koneksi, "SELECT * FROM tbl_instansi");
              if (!$query) {
                die("Query error: " . mysqli_error($koneksi));
              }
              $data = mysqli_fetch_array($query); 
              ?>

            <div class="row">
              <div class="col-md-6">
                 <div class="form-group">
                   <label>Nama Instansi</label>
                    <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
                <div class="form-group">
                   <label>Alamat</label>
                    <input type="text" name="alamat" value="<?= $data['alamat']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
                <div class="form-group">
                   <label>Website</label>
                    <input type="text" name="website" value="<?= $data['website']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                   <label>Nama Fakultas</label>
                    <input type="text" name="institusi" value="<?= $data['institusi']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
                <div class="form-group">
                   <label>Nama Dekan</label>
                    <input type="text" name="kepsek" value="<?= $data['kepsek']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
                <div class="form-group">
                   <label>NIDN Dekan</label>
                    <input type="text" name="nidn" value="<?= $data['nidn']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
                <div class="form-group">
                   <label>Email Fakultas</label>
                    <input type="email" name="email" value="<?= $data['email']; ?>" class="form-control" placeholder="Nomor Agenda">
                  </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="fileUpload" class="font-weight-bold">FILE</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="fileUpload" name="file"
                      accept=".jpg,.jpeg,.png,.doc,.docx,.pdf">
                    <label class="custom-file-label" for="fileUpload"><?= $data['file']; ?></label>
                  </div>
                  <small class="form-text text-danger mt-2">
                  *Format file yang diperbolehkan hanya *.JPG, *.PNG dan ukuran maksimal file 10 MB. Disarankan gambar berbentuk kotak atau lingkaran!
                  </small>
                </div>
              </div>
                <div class="col-md-6 d-flex align-items-center">
                <div>
                  <img src="<?= $base_url ?>upload/<?= $data['logo']; ?>" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                </div>
              </div>
            </div>
          </div> <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-default">Batal</button>
          </div>
        </form>
         </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </section>
</div>
<!-- /.content-wrapper -->

<?php
include '../../../layouts/footer.php';
?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const fileInputs = document.querySelectorAll('.custom-file-input');

    fileInputs.forEach(function(input) {
      input.addEventListener('change', function(e) {
        const fileName = e.target.files.length > 0 ? e.target.files[0].name : 'Upload file/scan gambar surat keluar';
        const label = e.target.nextElementSibling;
        if (label) {
          label.textContent = fileName;
        }
      });
    });
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
      modal.addEventListener('shown.bs.modal', function () {
        const fileInput = modal.querySelector('.custom-file-input');
        const fileLabel = modal.querySelector('.custom-file-label');
        if (fileInput && fileLabel && fileInput.files.length === 0) {
          const defaultLabel = fileLabel.getAttribute('data-default-label');
          if (defaultLabel) {
            fileLabel.textContent = defaultLabel;
          }
        }
      });
    });
  });
</script>

