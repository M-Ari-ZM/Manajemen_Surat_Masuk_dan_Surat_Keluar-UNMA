<?php
session_start();

if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include_once '../../../config/koneksi.php';
include '../../../layouts/header.php';
include '../../../layouts/navbar.php';
include '../../../layouts/sidebar.php';

$id_user = $_SESSION['id_user'];

// Ambil data user yang sedang login
$query = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE id_user = '$id_user'");
if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}
$data = mysqli_fetch_assoc($query);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fas fa-user"></i> Profil Saya</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container">
      <div class="card">
        <div class="card-header bg-default">
          <h3 class="card-title text-black">Informasi Pengguna</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <div class="form-group">
                <label><strong>Username</strong></label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" readonly>
              </div>
              <div class="form-group">
                <label><strong>Password</strong></label>
                <input type="password" class="form-control" value="********" readonly>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <div class="form-group">
                <label><strong>Nama</strong></label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" readonly>
              </div>
              <div class="form-group">
                <label><strong>NIP</strong></label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($data['nip']) ?>" readonly>
              </div>
            </div>
          </div>

          <div class="mb-1">
            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $id_user ?>">
              <i class="fas fa-edit"></i> Edit
            </button>
          </div>
        </div>

        <!-- Modal Edit Profil -->
        <div class="modal fade" id="modal-ubah<?= $id_user; ?>">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Edit Profil</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form method="post" action="edit_profil.php">
                <input type="hidden" name="id_user" value="<?= $id_user; ?>">

                <div class="modal-body">
                  <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>Password Baru <small class="text-muted">(Kosongkan jika tidak diganti)</small></label>
                        <input type="password" name="password_baru" class="form-control">
                      </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" value="<?= htmlspecialchars($data['nip']) ?>" class="form-control" required>
                      </div>
                      <small class="form-text text-danger mt-2">
                        *Setelah menekan tombol "Simpan", Anda akan diminta melakukan Login ulang.
                      </small>
                    </div>
                  </div>
                </div>

                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<?php include '../../../layouts/footer.php'; ?>

<script>
  $(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('edit') === '1') {
      $('#modal-ubah<?= $id_user ?>').modal('show');

      // Auto focus ke input password lama
      $('#modal-ubah<?= $id_user ?>').on('shown.bs.modal', function () {
        $('[name="password_lama"]').focus();
      });
    }
  });
</script>

