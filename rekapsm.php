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

          // Fungsi untuk format tanggal ke Bahasa Indonesia
            function indoDate($date) {
                $bulan = array(
                    1 => 'Januari', 'Februari', 'Maret', 'April',
                    'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                );
                $pecah = explode('-', $date);
                return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
            }

            // Cek apakah form disubmit
            $formSubmitted = isset($_POST['submit']);
            $result = [];

            if ($formSubmitted) {
                $dari_tanggal = $_POST['dari_tanggal'];
                $sampai_tanggal = $_POST['sampai_tanggal'];
                $search = isset($_GET['search']) ? $_GET['search'] : '';

                if ($dari_tanggal !== "" && $sampai_tanggal !== "") {
                    $queryString = "SELECT * FROM tbl_surat_masuk WHERE tgl_diterima BETWEEN '$dari_tanggal' AND '$sampai_tanggal'";
                    
                    if (!empty($search)) {
                        $queryString .= " AND (no_surat LIKE '%$search%' OR no_agenda LIKE '%$search%')";
                    }

                    $query = mysqli_query($koneksi, $queryString);
                    $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
                }
            }
          ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Rekap Surat Masuk</h1>
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
                <h3 class="card-title">Rekap Surat Masuk</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <div class="input-group-append">
                     <?php
                        $printURL = "tindakan/printrkp.php";
                        $params = [];

                        if (isset($_POST['dari_tanggal']) && isset($_POST['sampai_tanggal'])) {
                            $params[] = "dari_tanggal=" . urlencode($_POST['dari_tanggal']);
                            $params[] = "sampai_tanggal=" . urlencode($_POST['sampai_tanggal']);
                        }

                        if (isset($_GET['search']) && $_GET['search'] !== '') {
                            $params[] = "search=" . urlencode($_GET['search']);
                        }

                        if (!empty($params)) {
                            $printURL .= '?' . implode('&', $params);
                        }
                    ?>
                    <a href="<?= $printURL ?>" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Print Laporan
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
                        <?php } elseif ($_GET['info'] == "simpan") { ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Sukses</h5>
                                Data berhasil disimpan
                            </div>
                        <?php } elseif ($_GET['info'] == "update") { ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-edit"></i> Sukses</h5>
                                Data berhasil diupdate
                            </div>
                        <?php }
                    }  ?>
                          <script>
                          setTimeout(function() {
                            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                              $(this).remove(); 
                            });
                          }, 3000);
                        </script>
                      <form method="post" action="">
                        <input type="hidden" name="search" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
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

            <?php if ($formSubmitted): ?>
                <div class="alert alert-info mt-3" role="alert">
                    Agenda Surat Masuk dari tanggal <strong><?= indoDate($dari_tanggal) ?></strong> sampai dengan tanggal <strong><?= indoDate($sampai_tanggal) ?></strong>.
                </div>
            <?php endif; ?>

                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tgl Diterima</th>
                      <th>No. Surat</th>
                      <th>Asal Surat</th>
                      <th>Isi</th>
                      <th>Tgl Surat</th>
                      <th>File</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $no = 1;

                  if ($formSubmitted && !empty($result)) {
                      foreach ($result as $data) {
                  ?>
                      <tr>
                          <td><?= $no++ ?></td>
                          <td><?= $data['tgl_diterima']; ?></td>
                          <td><?= $data['no_surat'] ?><br><?= date('d-m-Y', strtotime($data['tgl_surat'])) ?></td>
                          <td><?= $data['asal_surat'] ?></td>
                          <td><?= $data['isi'] ?></td>
                          <td><?= $data['tgl_surat'] ?></td>
                          <td>
                              <div class="mt-2">
                              <?php if (!empty($data['file'])): ?>
                                  <a href="upload/surat_masuk/<?= htmlspecialchars($data['file']); ?>" 
                                  target="_blank" class="text-primary font-weight text-decoration-none">Lihat File</a>
                              <?php else: ?>
                                  <em class="text-muted">-</em>
                              <?php endif; ?>
                              </div>
                          </td>
                      </tr>
                  <?php
                      }
                  } else {
                      $search = isset($_GET['search']) ? $_GET['search'] : '';
                      if ($search != '') {
                          $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE no_surat LIKE '%$search%' OR no_agenda LIKE '%$search%'");
                      } else {
                          $query = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk ");
                      }

                      while ($data = mysqli_fetch_array($query)) {
                  ?>
                      <tr>
                          <td><?= $no++ ?></td>
                          <td><?= $data['tgl_diterima']; ?></td>
                          <td><?= $data['no_surat'] ?><br><?= date('d-m-Y', strtotime($data['tgl_surat'])) ?></td>
                          <td><?= $data['asal_surat'] ?></td>
                          <td><?= $data['isi'] ?></td>
                          <td><?= $data['tgl_surat'] ?></td>
                          <td>
                              <div class="mt-2">
                              <?php if (!empty($data['file'])): ?>
                                  <a href="upload/surat_masuk/<?= htmlspecialchars($data['file']); ?>" 
                                  target="_blank" class="text-primary font-weight text-decoration-none">Lihat File</a>
                              <?php else: ?>
                                  <em class="text-muted">-</em>
                              <?php endif; ?>
                              </div>
                          </td>
                      </tr>
                  <?php
                      }
                  }
                  ?>
                  </tbody>
                </table>
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