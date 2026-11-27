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

include_once '../../../config/koneksi.php';

// Ambil jumlah data per halaman dari GET atau default 5
$itemsPerPage = isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 5;

// Ambil halaman aktif dari GET atau default 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Hitung offset untuk LIMIT
$offset = ($page - 1) * $itemsPerPage;

// Query total data untuk paging
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query total data dulu tanpa limit
if ($search != '') {
  $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_klasifikasi WHERE kode LIKE '%$search%' OR nama LIKE '%$search%'");
} else {
  $result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tbl_klasifikasi");
}
$row = mysqli_fetch_assoc($result);
$totalData = $row['total'];

// Query data dengan limit & offset
if ($search != '') {
  $query = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE kode LIKE '%$search%' OR nama LIKE '%$search%' LIMIT $itemsPerPage OFFSET $offset");
} else {
  $query = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi LIMIT $itemsPerPage OFFSET $offset");
}

// Hitung total halaman
$totalPages = ceil($totalData / $itemsPerPage);

include '../../../layouts/header.php';
include '../../../layouts/navbar.php';
include '../../../layouts/sidebar.php';
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Klasifikasi Surat</h1>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <form method="GET" action="" class="form-inline d-flex justify-content-end mt-3 mb-1 position-relative" style="margin-right: 65px;">
    <div class="input-group input-group-sm">
      <input type="text" id="search" class="form-control" name="search" placeholder="Cari Kode surat dan Nama..." autocomplete="off" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
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
              <h3 class="card-title">Data Klasifikasi Surat</h3>

              <?php if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2): ?>
                <div class="card-tools d-flex gap-2">
                  <button class="btn btn-primary btn-sm mr-2" data-toggle="modal" data-target="#modal-tambah">
                    <i class="fas fa-plus"></i> Tambah Data
                  </button>
                  <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-import">
                    <i class="fas fa-file-import"></i> Import Data
                  </button>
                </div>
              <?php endif; ?>
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
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Uraian</th>
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

                  if ($search != '') {
                    $queryAll = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi WHERE kode LIKE '%$search%' OR nama LIKE '%$search%' LIMIT $itemsPerPage OFFSET $offset");
                  } else {
                    $queryAll = mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi LIMIT $itemsPerPage OFFSET $offset");
                  }

                  if (!$queryAll) {
                    die("Query error: " . mysqli_error($koneksi));
                  }

                  $jumlah_data = mysqli_num_rows($queryAll);

                  if ($jumlah_data > 0) {
                    while ($data = mysqli_fetch_array($queryAll)) {
                  ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['kode']; ?> </td>
                        <td><?= $data['nama'] ?></td>
                        <td><?= $data['uraian'] ?></td>
                        <td>
                          <div class="mb-1">
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-ubah<?= $data['id_klasifikasi'] ?>">
                              <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-hapus<?= $data['id_klasifikasi'] ?>">
                              <i class="fas fa-trash"></i> Hapus
                            </button>
                          </div>
                        </td>
                      </tr>

                      <!-- Modal Hapus -->
                      <div class="modal fade" id="modal-hapus<?= $data['id_klasifikasi']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-default">
                              <h4 class="modal-title">Konfirmasi Hapus</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="actions/hapuskla.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_klasifikasi" value="<?= $data['id_klasifikasi']; ?>">
                                <p>Apakah anda yakin ingin menghapus data ini?</p>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Hapus</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Ubah -->
                      <div class="modal fade" id="modal-ubah<?= $data['id_klasifikasi']; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header bg-default">
                              <h4 class="modal-title">Ubah Data Klasifikasi</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="actions/editkla.php" method="post">
                              <div class="modal-body">
                                <input type="hidden" name="id_klasifikasi" value="<?= $data['id_klasifikasi']; ?>">
                                <div class="form-group">
                                  <label for="kode">Kode</label>
                                  <input type="text" name="kode" class="form-control" id="kode" value="<?= $data['kode']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <label for="nama">Nama</label>
                                  <input type="text" name="nama" class="form-control" id="nama" value="<?= $data['nama']; ?>" required>
                                </div>
                                <div class="form-group">
                                  <label for="uraian">Uraian</label>
                                  <textarea name="uraian" class="form-control" id="uraian" rows="4" required><?= $data['uraian']; ?></textarea>
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

                  <?php
                    }
                  } else {
                    echo "<tr><td colspan='5' class='text-center'>Data tidak ditemukan</td></tr>";
                  }
                  ?>
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

                  // Tombol previous
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

                  // Tombol next
                  if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="' . createPageLink($page + 1, $queryParams, $baseUrl, $itemsPerPage) . '">&raquo;</a></li>';
                  } else {
                    echo '<li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>';
                  }
                  ?>
                </ul>
              </nav>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Tambah Data Klasifikasi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="simpan_klasifikasi.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode">Kode</label>
              <input type="text" name="kode" class="form-control" id="kode" placeholder="Masukkan kode surat" required>
            </div>
            <div class="form-group">
              <label for="nama">Nama</label>
              <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan nama surat" required>
            </div>
            <div class="form-group">
              <label for="uraian">Uraian</label>
              <textarea name="uraian" class="form-control" id="uraian" rows="4" placeholder="Masukkan uraian surat" required></textarea>
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

  <!-- Modal Import -->
  <div class="modal fade" id="modal-import">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title">Import Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       <form action="actions/import.php" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label for="file_excel">Pilih File Excel</label>
            <input type="file" name="file_excel" id="file_excel" class="form-control" required>
            <small class="text-muted">Format file harus .csv</small>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Import</button>
        </div>
      </form>
      </div>
    </div>
  </div>

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
</div>

<?php include '../../../layouts/footer.php'; ?>

<script>
$(document).ready(function() {
  if(localStorage.getItem('itemsPerPage')) {
    $('#dataPerPage').val(localStorage.getItem('itemsPerPage'));
  } else {
    $('#dataPerPage').val('<?= $itemsPerPage ?>');
  }
  $('#saveSettings').click(function() {
    var itemsPerPage = $('#dataPerPage').val();
    localStorage.setItem('itemsPerPage', itemsPerPage);

    var url = new URL(window.location.href);
    url.searchParams.set('itemsPerPage', itemsPerPage);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
  });

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

  $(document).on('click', '.search-suggestion-item', function() {
    var val = $(this).text();
    $('#search').val(val);
    $('#search-suggestions').hide();
    $('form').submit();
  });
});
</script>
