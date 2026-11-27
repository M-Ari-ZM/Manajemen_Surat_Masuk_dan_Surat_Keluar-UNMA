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

         if(!isset($_SESSION['status']) || $_SESSION['status'] != "login"){
    header("Location: login.php?info=login");
    exit();
}
          include 'koneksi.php';
          include 'layouts/header.php';
          include 'layouts/navbar.php';
          include 'layouts/sidebar.php';
          ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-4">
          <div class="col-sm-4">
            <h1 class="m-2">Manajemen Surat Masuk dan Surat Keluar</h1>
          </div><!-- /.col -->
           <!-- awal -->
          <div class="col-12">
          <div class="card shadow-sm">
            <div class="card-body">
              <h4 class="card-title">Selamat Datang <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Pengguna'; ?></h4>
              <p class="card-text">
                Anda login sebagai
                <?php
               if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
                 echo "<strong>Super Admin</strong>. Anda memiliki akses penuh terhadap sistem.";
                } elseif ($_SESSION['admin'] == 2) {
                    echo "<strong>Administrator</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                } else {
                    echo "<strong>Petugas</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.";
                }?> </p>
            </div>
            <!-- akhir -->
          </div>
        </div>

        <!-- count -->
          <?php
                $count1 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk"));
                $count2 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar"));
                $count3 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_disposisi"));
                $count4 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_klasifikasi"));
                $count5 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tbl_user"));
                $count6 = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM sk"));
                
           ?>
         <!-- count end -->

          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          
          <!-- Cards -->
       <?php
        $cards = [
          ['title' => 'Jumlah Surat Masuk', 'icon' => 'fas fa-envelope', 'count' => $count1, 'link' => 'jumlah_surat_masuk.php'],
          ['title' => 'Jumlah Surat Keluar', 'icon' => 'fas fa-envelope-open', 'count' => $count2, 'link' => 'jumlah_surat_keluar.php'],
          ['title' => 'Disposisi', 'icon' => 'fas fa-book', 'count' => $count3, 'link' => 'disposisi.php'],
          ['title' => 'Klasifikasi Surat', 'icon' => 'fas fa-book-open', 'count' => $count4, 'link' => 'klasifikasi.php'],
        ];

        if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2) {
          $cards[] = ['title' => 'Kumpulan SK', 'icon' => 'fas fa-book-open', 'count' => $count6, 'link' => 'rektorat.php'];
            $cards[] = ['title' => 'Pengguna', 'icon' => 'fas fa-users', 'count' => $count5, 'link' => 'pengguna.php'];
            $cards[] = ['title' => 'Rekap Surat Masuk', 'icon' => 'fas fa-bookmark', 'count' => $count1, 'link' => 'rekapsm.php'];
            $cards[] = ['title' => 'Rekap Surat Keluar', 'icon' => 'fas fa-bookmark', 'count' => $count2, 'link' => 'rekapsk.php'];
        }

        foreach ($cards as $card) {
            ?>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= htmlspecialchars($card['count']); ?></h3>
                        <p><?= htmlspecialchars($card['title']); ?></p>
                    </div>
                    <div class="icon">
                        <i class="<?= htmlspecialchars($card['icon']); ?>"></i>
                    </div>
                    <a href="<?= htmlspecialchars($card['link']); ?>" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <?php
            }
            ?>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php
include 'layouts/footer.php';
?>