<?php
session_start();
include_once '../../../config/koneksi.php';

if (isset($_POST['ubah_password_admin'])) {
    if ($_SESSION['admin']['level'] !== 'Super Admin') {
        die("Akses ditolak!");
    }

    $id_user = intval($_POST['id_user_password']);
    $password_baru = md5($_POST['password_baru_admin']);

    $update = mysqli_query($koneksi, "UPDATE tbl_user SET password='$password_baru' WHERE id_user=$id_user");

    if ($update) {
        echo "<script>alert('Password berhasil diubah!'); window.location='pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal mengubah password!'); window.location='pengguna.php';</script>";
    }
}


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
  } else if ($_GET['info'] == "noaction") { ?>
    <div class="alert alert-warning alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h5><i class="icon fas fa-ban"></i> Tidak Diizinkan</h5>
      Super Admin tidak dapat diedit atau dihapus.
    </div>
<?php }
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
        <div class="col-sm-6">
          <h1 class="m-0">Manajemen User</h1>
        </div>
      </div>
    </div>
  </div>

  <form method="GET" action="" class="form-inline d-flex justify-content-end mt-3 mb-1 position-relative" style="margin-right: 65px;">
    <div class="input-group input-group-sm">
      <input type="text" id="search" class="form-control" name="search" placeholder="Cari user dan nip..." autocomplete="off" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
      <div class="input-group-append">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
      </div>
    </div>
  </form>

  <div class="content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data User</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                  <i class="fas fa-user-plus"></i> Tambah User
                </button>
              </div>
            </div>

            <div class="card-body">
              <?php
              if (isset($_GET['info'])) {
                if ($_GET['info'] == "hapus" || $_GET['info'] == "simpan" || $_GET['info'] == "update") {
                  $pesan = $_GET['info'] == "hapus" ? "Data berhasil dihapus" :
                          ($_GET['info'] == "simpan" ? "Data berhasil disimpan" : "Data berhasil diupdate");
                  $ikon = $_GET['info'] == "hapus" ? "fa-trash" :
                          ($_GET['info'] == "simpan" ? "fa-check" : "fa-edit");
              ?>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas <?= $ikon ?>"></i> Sukses</h5>
                    <?= $pesan ?>
                  </div>
              <?php
                }
              }
              ?>

              <script>
                setTimeout(function () {
                  $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                  });
                }, 3000);
              </script>

              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama <br>NIP</th>
                    <th>Level</th>
                    <th>Tindakan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                  $search = isset($_GET['search']) ? $_GET['search'] : '';
                  $query = $search != ''
                    ? mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username LIKE '%$search%' OR nip LIKE '%$search%'")
                    : mysqli_query($koneksi, "SELECT * FROM tbl_user");

                  while ($data = mysqli_fetch_array($query)) {
                    $level = $data['admin'] == 1 ? 'Super Admin' : ($data['admin'] == 2 ? 'Administrator' : 'User Biasa');
                  ?>
                    <tr>
                      <td><?= $no++ ?></td>
                      <td><?= htmlspecialchars($data['username']) ?></td>
                      <td><?= htmlspecialchars($data['nama']) ?><br><?= htmlspecialchars($data['nip']) ?></td>
                      <td><?= $level ?></td>
                      <td>
                        <div class="mb-1">
                          <?php if ($data['admin'] != 1): ?>
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $data['id_user'] ?>">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-hapus<?= $data['id_user'] ?>">
                              <i class="fas fa-trash"></i> Hapus
                            </button>
                          <?php else: ?>
                            <button class="btn btn-secondary btn-sm" disabled style="pointer-events: none; opacity: 0.65;">
                              <i class="fas fa-exclamation-circle mr-1"></i> No Action
                            </button>
                          <?php endif; ?>
                         </div>
                          <div>
                          <?php if ($_SESSION['id_user'] == 1): ?>
                          <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-password<?= $data['id_user'] ?>">
                            Ubah Password
                          </button>
                        <?php endif; ?>
                        </div>
                      </td>
                    </tr>

                     <!-- Modal Ubah Password -->
                  <div class="modal fade" id="modal-password<?= $data['id_user']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <form method="post" action="ubah_password.php">
                          <input type="hidden" name="id_user_password" value="<?= $data['id_user']; ?>">
                          <div class="modal-header">
                            <h5 class="modal-title">Ubah Password: <?= $data['username']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal">
                              <span>&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="form-group">
                              <label>Password Baru</label>
                              <input type="password" name="password_baru_admin" class="form-control" required>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" name="ubah_password_admin" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                    <?php if ($data['admin'] != 1): ?>
                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal-hapus<?= $data['id_user']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Hapus Data User</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="actions/hapususer.php" method="post">
                              <div class="modal-body">
                                <p>Yakin ingin menghapus <b><?= $data['username'] ?></b>?</p>
                                <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Hapus</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Ubah -->
                      <div class="modal fade" id="modal-ubah<?= $data['id_user']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit User</h4>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <form method="post" action="actions/edituser.php">
                              <div class="modal-body">
                                <input type="hidden" name="id_user" value="<?= $data['id_user']; ?>">
                                <div class="form-group">
                                  <label>Username</label>
                                  <input type="text" name="username" value="<?= $data['username']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label>Nama</label>
                                  <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label>Tipe User</label>
                                  <select name="admin" class="form-control">
                                    <option value="1" <?= $data['admin'] == 1 ? 'selected' : '' ?>>Super Admin</option>
                                    <option value="2" <?= $data['admin'] == 2 ? 'selected' : '' ?>>Administrator</option>
                                    <option value="3" <?= $data['admin'] == 3 ? 'selected' : '' ?>>User Biasa</option>
                                  </select>
                                </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php } ?>
                </tbody>
              </table>


              <!-- Modal Tambah -->
              <div class="modal fade" id="modal-tambah">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form method="post" action="actions/tambahuser.php">
                      <div class="modal-header">
                        <h4 class="modal-title">Tambah User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label>Username</label>
                          <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="form-group">
                          <label>Password</label>
                          <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                          <label>Nama</label>
                          <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="form-group">
                          <label>NIP</label>
                          <input type="text" class="form-control" name="nip">
                        </div>
                        <div class="form-group">
                          <label>Tipe User</label>
                          <select name="admin" class="form-control">
                            <option value="1">Super Admin</option>
                            <option value="2">Administrator</option>
                            <option value="3" selected>User Biasa</option>
                          </select>
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
              <!-- End Modal -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<?php include '../../../layouts/footer.php'; ?>
