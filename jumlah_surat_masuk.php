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
          $itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;
          $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
          if ($page < 1) $page = 1;
          $offset = ($page - 1) * $itemsPerPage;
          $search = isset($_GET['search']) ? $_GET['search'] : '';
          if ($search != '') {
            $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_surat_masuk WHERE no_surat LIKE '%$search%' OR asal_surat LIKE '%$search%'");
          } else {
            $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_surat_masuk");
          }
          $row = mysqli_fetch_assoc($result);
          $totalData = $row['total'];

          if ($search != '') {
            $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE no_surat LIKE '%$search%' OR asal_surat LIKE '%$search%' LIMIT $itemsPerPage OFFSET $offset");
          } else {
            $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk LIMIT $itemsPerPage OFFSET $offset");
          }

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
            <h1 class="m-0">Jumlah Surat Masuk</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <form method="GET" action="" class="form-inline d-flex justify-content-end mt-3 mb-1 position-relative" style="margin-right: 65px;">
      <div class="input-group input-group-sm">
        <input type="text" id="search" class="form-control" name="search" placeholder="Cari No surat dan asal surat..." autocomplete="off" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
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
                <h3 class="card-title">Data Surat Masuk</h3>
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
                      <th>Isi Ringkas <br>File</th>
                      <th>Asal Surat</th>
                      <th>No. Surat <br>Tgl Surat</th>
                      <th class="text-right" style="width: 18%;">
                      Tindakan
                      <button type="button" class="btn btn-link p-0 ml-2" data-toggle="modal" data-target="#modalPengaturan" title="Atur jumlah data yang ditampilkan">
                        <i class="fas fa-cog"></i>
                      </button>
                    </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    if (!$query) {
                      die("Query error: " . mysqli_error($koneksi));
                    }

                    while ($data = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td>
                        <?= $data['isi']; ?>
                        <div class="mt-2">
                        <strong>File:</strong>
                        <?php if (!empty($data['file'])): ?>
                            <a href="upload/surat_masuk/<?= htmlspecialchars($data['file']); ?>" 
                              class="btn btn-sm btn-outline-primary mt-1" target="_blank">
                                <i class="bi bi-file-earmark-text"></i> <?= htmlspecialchars($data['file']); ?>
                            </a>
                        <?php else: ?>
                            <em class="text-muted">Tidak ada file yang di-upload</em>
                        <?php endif; ?>
                    </div>
                    </td>
                        <td><?= $data['asal_surat'] ?></td>
                        <td><?= $data['no_surat'] ?><br><?= date('d-m-Y', strtotime($data['tgl_surat'])) ?></td>
                        <td>
                            <?php if ($_SESSION['id_user'] == $data['id_user'] || $_SESSION['admin'] == 1): ?>
                              <div class="mb-1">
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $data['id_surat'] ?>">
                                  <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-hapus<?= $data['id_surat'] ?>">
                                  <i class="fas fa-trash"></i> Hapus
                                </button>
                              </div>
                            <?php endif; ?>
                        <div>
                          <a href="tindakan/printsm.php?id_surat=<?= $data['id_surat'] ?>" target="_blank" class="btn btn-sm btn-primary">
                            <i class="fas fa-print"></i> Print
                          </a>
                       <a href="disposisi.php?id_surat=<?= $data['id_surat'] ?>">
                        <button class="btn btn-success btn-sm">
                          <i class="fas fa-random"></i> Disp
                        </button>
                      </a>
                        </div>
                      </td>
                      </tr>

                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal-hapus<?= $data['id_surat']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Hapus Data Surat</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="tindakan/hapussm.php" method="post">
                              <div class="modal-body">
                                <p>Apakah Anda Yakin Akan Menghapus Surat dengan Isi Ringkas <b><?= $data['isi'] ?></b>?</p>
                                <input type="hidden" name="id_surat" value="<?= $data['id_surat']; ?>">
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
                      <div class="modal fade" id="modal-ubah<?= $data['id_surat']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Surat</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                           <form method="post" action="tindakan/editsm.php" enctype="multipart/form-data">
                          <div class="modal-body">
                            <input type="hidden" name="id_surat" value="<?= $data['id_surat']; ?>">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Nomor Agenda</label>
                                  <input type="text" name="no_agenda" value="<?= $data['no_agenda']; ?>" class="form-control" placeholder="Nomor Agenda">
                                </div>
                                <div class="form-group">
                                  <label>Isi Ringkas</label>
                                  <textarea name="isi" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                  <label>Tanggal Surat</label>
                                  <input type="date" name="tgl_surat" value="<?= $data['tgl_surat']; ?>" class="form-control">
                                </div>
                              </div>
                              
                              <div class="col-md-6">
                                <div class="form-group">
                                  <label>Nomor Surat</label>
                                  <input type="text" name="no_surat" value="<?= $data['no_surat']; ?>" class="form-control" placeholder="Nomor Surat">
                                </div>
                                <div class="form-group">
                                  <label>Asal Surat</label>
                                  <input type="text" name="asal_surat" value="<?= $data['asal_surat']; ?>" class="form-control" placeholder="Asal Surat">
                                </div>
                                <div class="form-group">
                                  <label>Keterangan</label>
                                  <textarea name="keterangan" class="form-control" rows="3"><?= $data['keterangan']; ?></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="fileUpload<?= $data['id_surat'] ?>" class="d-block font-weight-bold">FILE</label>
                              <div class="custom-file">
                                <input type="file" class="custom-file-input" id="fileUpload<?= $data['id_surat'] ?>" name="file_surat"
                                      accept=".jpg,.jpeg,.png,.doc,.docx,.pdf">
                                <label class="custom-file-label"
                                      for="fileUpload<?= $data['id_surat'] ?>"
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
                    <?php } ?>
                  </tbody>
                </table>

               <!-- Pagination -->
              <nav aria-label="Page navigation example" class="mt-3">
                <ul class="pagination justify-content-center">
                  <?php
                  $queryStr = $_GET;
                  unset($queryStr['page']);
                  unset($queryStr['itemsPerPage']);

                  $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
                  $queryParams = http_build_query($queryStr);
                  function createPageLink($pageNum, $queryParams, $baseUrl, $itemsPerPage) {
                    $params = $queryParams ? $queryParams . '&' : '';
                    $params .= "page=$pageNum&itemsPerPage=$itemsPerPage";
                    return "$baseUrl?$params";
                  }
                  if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="'.createPageLink($page-1, $queryParams, $baseUrl, $itemsPerPage).'">&laquo;</a></li>';
                  } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>';
                  }
                  $startPage = max(1, $page - 2);
                  $endPage = min($totalPages, $page + 2);

                  for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $page) {
                      echo '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
                    } else {
                      echo '<li class="page-item"><a class="page-link" href="' . createPageLink($i, $queryParams, $baseUrl, $itemsPerPage) . '">' . $i . '</a></li>';
                    }
                  }
                  if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="' . createPageLink($page + 1, $queryParams, $baseUrl, $itemsPerPage) . '">&raquo;</a></li>';
                  } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>';
                  }
                  ?>
                </ul>
              </nav>

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
                  <form method="post" action="tindakan/tambahsm.php" enctype="multipart/form-data" id="formTambahSurat">
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Nomor Agenda</label>
                            <input type="text" class="form-control" name="no_agenda" placeholder="Nomor Agenda" required>
                          </div>
                          <div class="form-group">
                            <label>Isi Ringkas</label>
                            <input type="text" class="form-control" name="isi" placeholder="Isi Ringkas" required>
                          </div>
                          <div class="form-group">
                            <label>Tanggal Surat</label>
                            <input type="date" class="form-control" name="tgl_surat" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Nomor Surat</label>
                            <input type="text" class="form-control" name="no_surat" placeholder="Nomor Surat" id="no_surat" required>
                            <small id="nomorSuratHelp" class="text-danger" style="display:none;">Nomor surat sudah digunakan!</small>
                          </div>
                          <div class="form-group">
                            <label>Asal Surat</label>
                            <input type="text" class="form-control" name="asal_surat" placeholder="Asal Surat" required>
                          </div>
                          <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3"></textarea>
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

                 <!-- Modal Pengaturan Jumlah Data Per Halaman -->
              <div class="modal fade" id="modalPengaturan" tabindex="-1" role="dialog" aria-labelledby="modalPengaturanLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalPengaturanLabel">Jumlah data yang ditampilkan per halaman</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <select class="form-control" id="dataPerPage">
                        <option value="5" <?= $itemsPerPage == 5 ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $itemsPerPage == 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= $itemsPerPage == 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= $itemsPerPage == 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= $itemsPerPage == 100 ? 'selected' : '' ?>>100</option>
                      </select>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="button" class="btn btn-primary" id="saveSettings">Simpan</button>
                    </div>
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
  // Tombol Simpan pengaturan
  $('#saveSettings').click(function() {
    var itemsPerPage = $('#dataPerPage').val();
    localStorage.setItem('itemsPerPage', itemsPerPage);
    var url = new URL(window.location.href);
    url.searchParams.set('itemsPerPage', itemsPerPage);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });

  // Search suggestion (autocomplete)
  $('#search').on('input', function() {
    var query = $(this).val();
    if(query.length >= 2) {
      $.ajax({
        url: 'autocomplete_klasifikasi.php',
        method: 'GET',
        data: {q: query},
        success: function(data) {
          $('#search-suggestions').html(data).show();
        }
      });
    } else {
      $('#search-suggestions').hide();
    }
  });

  // Klik suggestion untuk isi input dan submit form
  $(document).on('click', '.search-suggestion-item', function() {
    var val = $(this).text();
    $('#search').val(val);
    $('#search-suggestions').hide();
    $('form').submit();
  });
</script>


