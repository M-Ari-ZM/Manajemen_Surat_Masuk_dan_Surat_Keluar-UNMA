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

          // Redirect ke login jika belum login
          if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
            header("Location: login.php");
            exit();
          }
          include 'koneksi.php';
          $itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
          $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
          if ($page < 1) $page = 1;
          $offset = ($page - 1) * $itemsPerPage;
          $search = isset($_GET['search']) ? $_GET['search'] : '';
          $sumber_sk = isset($_GET['sumber_sk']) ? $_GET['sumber_sk'] : '';
          $whereClause = "WHERE 1=1"; 
          if ($search != '') {
            $searchSafe = mysqli_real_escape_string($koneksi, $search);
            $whereClause .= " AND (sumber_sk LIKE '%$searchSafe%' OR no_sk LIKE '%$searchSafe%')";
          }

          if ($sumber_sk != '') {
            $sumberSafe = mysqli_real_escape_string($koneksi, $sumber_sk);
            $whereClause .= " AND sumber_sk = '$sumberSafe'";
          }

          $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM sk $whereClause");

          $row = mysqli_fetch_assoc($result);
          $totalData = $row['total'];

          $query = mysqli_query($koneksi, "SELECT * FROM sk $whereClause LIMIT $itemsPerPage OFFSET $offset");

          $totalPages = ceil($totalData / $itemsPerPage);
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
            <h1 class="m-0">Surat Keputusan</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
   <div class="row mt-3 mb-2 px-3">
  <div class="col-md-6 d-flex align-items-start">
    <form method="GET" action="" class="form-inline">
      <div class="input-group input-group-sm">
        <select name="sumber_sk" class="form-control" onchange="this.form.submit()">
          <option value="">-- Semua Sumber --</option>
          <option value="Rektorat" <?= (isset($_GET['sumber_sk']) && $_GET['sumber_sk'] == 'Rektorat') ? 'selected' : '' ?>>Rektorat</option>
          <option value="Fakultas" <?= (isset($_GET['sumber_sk']) && $_GET['sumber_sk'] == 'Fakultas') ? 'selected' : '' ?>>Fakultas</option>
          <option value="Yayasan" <?= (isset($_GET['sumber_sk']) && $_GET['sumber_sk'] == 'Yayasan') ? 'selected' : '' ?>>Yayasan</option>
        </select>
      </div>
    </form>
  </div>
  <div class="col-md-6 d-flex justify-content-end">
    <form method="GET" action="" class="form-inline">
      <input type="hidden" name="sumber_sk" value="<?= isset($_GET['sumber_sk']) ? htmlspecialchars($_GET['sumber_sk']) : '' ?>">
      <div class="input-group input-group-sm">
        <input type="text" class="form-control" name="search" placeholder="Cari No surat atau asal surat..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
      </div>
    </form>
  </div>
</div>

    <div class="content">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Data Surat Keputusan </h3>
                
                <div class="card-tools d-flex gap-2">
                  <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-tambah">
                    <i class="fas fa-plus"></i> Tambah Data
                  </button>
                 <div class="btn-group">
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-file-upload"></i> Export Data
                </button>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="excelsk.php?sumber_sk=<?= urlencode($sumber_sk) ?>&search=<?= urlencode($search) ?>">
                    <i class="fas fa-file-excel text-success"></i> Export ke Excel
                  </a>
                  <a class="dropdown-item" href="pdfsk.php?sumber_sk=<?= urlencode($sumber_sk) ?>&search=<?= urlencode($search) ?>">
                    <i class="fas fa-file-pdf text-danger"></i> Export ke PDF
                  </a>
                  <a class="dropdown-item" href="csvsk.php?sumber_sk=<?= urlencode($sumber_sk) ?>&search=<?= urlencode($search) ?>">
                    <i class="fas fa-file-csv text-primary"></i> Export ke CSV
                  </a>
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
                  <script>
                          setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                              $(this).remove(); 
                            });
                          }, 3000);
                        </script>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Perihal</th>
                      <th>Nomor SK</th>
                      <th>Tanggal SK  </th>
                      <th>Sumber SK</th>
                      <th>Tindakan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    if (!$query) {
                      die("Query error: " . mysqli_error($koneksi));
                    }

                    if (mysqli_num_rows($query) == 0) {
                    ?>
                      <tr>
                        <td colspan="6" class="text-center text-muted">Data tidak tersedia</td>
                      </tr>
                    <?php
                    } else {
                      while ($data = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                      <td><?= $no++ ?></td>
                      <td>
                        <?= $data['perihal']; ?>
                        <div class="mt-2">
                          <strong>File:</strong>
                          <?php if (!empty($data['file'])): ?>
                            <a href="upload/surat_keluar/<?= htmlspecialchars($data['file']); ?>" 
                              class="btn btn-sm btn-outline-primary mt-1" target="_blank">
                              <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($data['file']); ?>
                            </a>
                          <?php else: ?>
                            <em class="text-muted">Tidak ada file yang di-upload</em>
                          <?php endif; ?>
                        </div>
                      </td>
                      <td><?= $data['no_sk'] ?></td>
                      <td><?= $data['tgl_sk'] ?></td>
                      <td><?= $data['sumber_sk'] ?></td>
                      <td>
                        <div class="mb-1">
                          <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $data['id_sk'] ?>">
                            <i class="fas fa-edit"></i> Edit
                          </button>
                          <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-hapus<?= $data['id_sk'] ?>">
                            <i class="fas fa-trash"></i> Hapus
                          </button>
                        </div>
                      </td>
                    </tr>

                       <!-- Modal Hapus -->
                      <div class="modal fade" id="modal-hapus<?= $data['id_sk']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Hapus Data Surat</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="tindakan/hapustsk.php" method="post">
                              <div class="modal-body">
                                <p>Apakah Anda Yakin Akan Menghapus Surat dengan Isi Ringkas <b><?= $data['perihal'] ?></b>?</p>
                                <input type="hidden" name="id_sk" value="<?= $data['id_sk']; ?>">
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
                      <div class="modal fade" id="modal-ubah<?= $data['id_sk']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Surat</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                           <form method="post" action="tindakan/edittsk.php" enctype="multipart/form-data">
                          <div class="modal-body">
                            <input type="hidden" name="id_sk" value="<?= $data['id_sk']; ?>">
                            <div class="row">
                              <div class="col-md-12">
                                <div class="form-group">
                                  <label>Perihal</label>
                                   <textarea name="perihal"  class="form-control" rows="3"><?= $data['perihal']; ?></textarea>
                                </div>
                                <div class="form-group">
                                  <label>Nomor SK</label>
                                   <input type="text" name="no_sk" value="<?= $data['no_sk']; ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label>Tanggal sk</label>
                                  <input type="date" name="tgl_sk" value="<?= $data['tgl_sk']; ?>" class="form-control">
                                </div>
                                 <div class="form-group">
                                  <label>Sumber SK</label>
                                  <select name="sumber_sk" class="form-control">
                                    <option value="Rektorat" <?= $data['sumber_sk'] == 'Rektorat' ? 'selected' : '' ?>>Rektorat</option>
                                    <option value="Fakultas" <?= $data['sumber_sk'] == 'Fakultas' ? 'selected' : '' ?>>Fakultas</option>
                                    <option value="Yayasan" <?= $data['sumber_sk'] == 'Yayasan' ? 'selected' : '' ?>>Yayasan</option>
                                  </select>
                                </div>
                              </div>
                              
                             
                            <div class="form-group">
                              <label for="fileUpload<?= $data['id_sk'] ?>" class="d-block font-weight-bold">FILE</label>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fileUpload<?= $data['id_sk'] ?>" name="file_surat"
                                      accept=".jpg,.jpeg,.png,.doc,.docx,.pdf">
                                <label class="custom-file-label"
                                      for="fileUpload<?= $data['id_sk'] ?>"
                                      data-default-label="<?= $data['file'] ? htmlspecialchars($data['file']) : 'Upload file/scan gambar surat keluar' ?>">
                                  <?= $data['file'] ? htmlspecialchars($data['file']) : 'Upload file/scan gambar surat keluar' ?>
                                </label>
                              </div>
                              <small class="form-text text-danger mt-2">
                                *Format file yang diperbolehkan: *.JPG, *.PNG, *.DOC, *.DOCX, *.PDF dan ukuran maksimal file 10 MB!
                              </small>
                            </div>
                          </div>

                          <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                          </div>
                        </form>

                          </div>
                        </div>
                      </div>
                    <?php }  } ?>
                  </tbody>
                </table>
                <!-- Modal Tambah -->
               <div class="modal fade" id="modal-tambah">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Surat</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form method="post" action="tindakan/tsk.php" enctype="multipart/form-data" id="formTambahSurat">
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>Perihal</label>
                            <input type="text" class="form-control" name="perihal" placeholder="" required>
                          </div>
                          <div class="form-group">
                            <label>Nomor SK</label>
                            <input type="text" class="form-control" name="no_sk" placeholder="" required>
                          </div>
                          <div class="form-group">
                            <label>Tanggal SK</label>
                            <input type="date" class="form-control" name="tgl_sk" required>
                          </div>
                         <div class="form-group">
                          <label>Sumber SK</label>
                          <select name="sumber_sk" class="form-control" required>
                            <option value="">-- Pilih Sumber SK --</option>
                            <option value="Rektorat">Rektorat</option>
                            <option value="Fakultas">Fakultas</option>
                            <option value="Yayasan">Yayasan</option>
                          </select>
                        </div>
                        </div>
                        
                      </div>
                      <div class="form-group">
                      <label for="fileUpload" class="d-block font-weight-bold">FILE</label>
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="fileUpload" name="file_surat"
                              accept=".jpg,.jpeg,.png,.doc,.docx,.pdf">
                        <label class="custom-file-label" for="fileUpload">Upload file/scan gambar surat keluar</label>
                      </div>
                      <!-- Tombol hapus file -->
                      <button type="button" class="btn btn-sm btn-danger mt-2" onclick="resetFileInput()">Reset</button>

                      <small class="form-text text-danger mt-2">
                        *Format file yang diperbolehkan: *.JPG, *.PNG, *.DOC, *.DOCX, *.PDF dan ukuran maksimal file 10 MB!
                      </small>
                    </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
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
<script>
  function resetFileInput() {
    const fileInput = document.getElementById('fileUpload');
    const fileLabel = fileInput.nextElementSibling;
    fileInput.value = "";
    if (fileLabel) {
      fileLabel.textContent = "Upload file/scan gambar surat keluar";
    }
  }
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
  });
</script>



