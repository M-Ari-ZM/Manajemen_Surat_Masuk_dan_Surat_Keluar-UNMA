<?php
session_start();

//notif
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

          // Redirect ke login jika belum login
          if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
            header("Location: login.php");
            exit();
          }
          include 'koneksi.php';
          include 'layouts/header.php';
          include 'layouts/navbar.php';
          include 'layouts/sidebar.php';
          ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Disposisi Surat</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <form method="GET" action="" class="form-inline d-flex justify-content-end mt-3 mb-1 position-relative" style="margin-right: 65px;">
      <div class="input-group input-group-sm">
        <input type="text" id="search" class="form-control" name="search" placeholder="Cari No surat dan agenda..." autocomplete="off" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </div>
      <div id="search-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
    </form>

    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Surat Disposisi</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Data
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <?php
                if (isset($_GET['info'])) {
                  if ($_GET['info'] == "hapus") { ?>
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-trash"></i> Sukses</h5>
                      Data berhasil dihapus
                    </div>
                  <?php } else if ($_GET['info'] == "simpan") { ?>
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-check"></i> Sukses</h5>
                      Data berhasil disimpan
                    </div>
                  <?php } else if ($_GET['info'] == "update") { ?>
                    <div class="alert alert-success alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                      <h5><i class="icon fas fa-edit"></i> Sukses</h5>
                      Data berhasil diupdate
                    </div>
                <?php }
                } ?>

                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tujuan Disposisi</th>
                      <th>Isi Disposisi</th>
                      <th>Sifat <br>Batas waktu</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    include "koneksi.php";

                    $search = isset($_GET['search']) ? $_GET['search'] : '';
                    if ($search != '') {
                      $query = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi WHERE no_surat LIKE '%$search%' OR no_agenda LIKE '%$search%'");
                    } else if (isset($_GET['id_surat'])) {
                      $id_surat = $_GET['id_surat'];
                      $query = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi WHERE id_surat = '$id_surat'");
                    } else {
                      $query = mysqli_query($koneksi, "SELECT * FROM tbl_disposisi");
                    }
                    if (!$query) {
                      die("Query error: " . mysqli_error($koneksi));
                    }

                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                        <?= $data['tujuan']; ?>
                    </td>
                        <td><?= $data['isi_disposisi'] ?></td>
                        <td><?= $data['sifat'] ?><br><?= date('d-m-Y', strtotime($data['batas_waktu'])) ?></td>
                        <td>
                           <?php if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2): ?>
                          <div class="mb-1">
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $data['id_disposisi'] ?>">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-hapus<?= $data['id_disposisi'] ?>">
                              <i class="fas fa-trash"></i> Hapus
                            </button>
                          </div>
                             <?php endif; ?>
                          <div>
                         <a href="tindakan/printdisp.php?id_surat=<?= $data['id_surat'] ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-print"></i> Print
                        </a>
                          </div>
                        </td>
                      </tr>

                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal-hapus<?= $data['id_disposisi']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Hapus Data Surat</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="tindakan/hapusdisp.php" method="post">
                              <div class="modal-body">
                                <p>Apakah Anda Yakin Akan Menghapus Surat dengan Isi Ringkas <b><?= $data['tujuan'] ?></b>?</p>
                                <input type="hidden" name="id_disposisi" value="<?= $data['id_disposisi']; ?>">
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
                      <div class="modal fade" id="modal-ubah<?= $data['id_disposisi']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Surat Disposisi</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form method="post" action="tindakan/editdisp.php">
                              <div class="modal-body">
                                <input type="hidden" name="id_disposisi" value="<?= $data['id_disposisi']; ?>">
                                <div class="form-group">
                                  <label>Tujuan</label>
                                  <input type="text" name="tujuan" value="<?= $data['tujuan']; ?>" class="form-control" placeholder="Tujuan">
                                </div>
                                <div class="form-group">
                                  <label>Isi Disposisi</label>
                                  <input type="text" name="isi_disposisi" value="<?= $data['isi_disposisi']; ?>" class="form-control" placeholder="Isi">
                                </div>
                                <div class="form-group">
                                  <label>Batas Waktu</label>
                                  <input type="date" name="batas_waktu" value="<?= $data['batas_waktu']; ?>" class="form-control" placeholder="Batas Waktu">
                                </div>
                                <div class="form-group">
                                  <label>Catatan</label>
                                  <input type="text" name="catatan" value="<?= $data['catatan']; ?>" class="form-control" placeholder="catatan">
                                </div>
                               <div class="form-group">
                                <label>Pilih Sifat Disposisi</label>
                                <select name="sifat" class="form-control">
                                    <option value="rahasia" <?= $data['sifat'] == 'rahasia' ? 'selected' : '' ?>>Rahasia</option>
                                    <option value="biasa" <?= $data['sifat'] == 'biasa' ? 'selected' : '' ?>>Biasa</option>
                                    <option value="penting" <?= $data['sifat'] == 'penting' ? 'selected' : '' ?>>Penting</option>
                                    <option value="segera" <?= $data['sifat'] == 'segera' ? 'selected' : '' ?>>Segera</option>
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

                    <?php } ?>
                  </tbody>
                </table>

                <!-- Modal Tambah -->
                <div class="modal fade" id="modal-tambah">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title">Tambah Surat Disposisi</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="post" action="tindakan/tambahdisp.php">
                        <input type="hidden" name="id_surat" value="<?= isset($_GET['id_surat']) ? $_GET['id_surat'] : '' ?>">
                        <div class="modal-body">
                          <div class="form-group">
                            <label>Tujuan Disposisi</label>
                            <input type="text" class="form-control" name="tujuan" placeholder="Tujuan">
                          </div>
                          <div class="form-group">
                            <label>Isi Disposisi</label>
                            <input type="text" class="form-control" name="isi_disposisi" placeholder="Nomor Surat">
                          </div>
                          <div class="form-group">
                            <label>Batas Waktu</label>
                            <input type="date" class="form-control" name="batas_waktu" placeholder="Batas Waktu">
                          </div>
                          <div class="form-group">
                            <label>Catatan</label>
                            <input type="text" class="form-control" name="catatan" placeholder="Catatan">
                          </div>
                          <div class="form-group">
                            <label>Pilih Sifat Disposisi</label>
                            <select name="sifat" class="form-control">
                                <option value="rahasia" <?= (isset($data['sifat']) && $data['sifat'] == 'rahasia') ? 'selected' : '' ?>>Rahasia</option>
                                <option value="biasa" <?= (isset($data['sifat']) && $data['sifat'] == 'biasa') ? 'selected' : '' ?>>Biasa</option>
                                <option value="penting" <?= (isset($data['sifat']) && $data['sifat'] == 'penting') ? 'selected' : '' ?>>Penting</option>
                                <option value="segera" <?= (isset($data['sifat']) && $data['sifat'] == 'segera') ? 'selected' : '' ?>>Segera</option>
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
  <!-- /.content-wrapper -->

<?php
include 'layouts/footer.php';
?>