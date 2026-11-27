<body class="hold-transition sidebar-mini layout-fixed">


  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= $base_url ?>index.php" class="brand-link">
      <img src="<?= $base_url ?>upload/unma.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light ">UNIVERSITAS <br>MAJALENGKA</span>
    </a>

     <!-- Sidebar -->
    <div class="sidebar">
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

      <li class="nav-item">
        <a href="<?= $base_url ?>index.php" class="nav-link">
          <i class="nav-icon fas fa-th"></i>
          <p>
            Beranda
          </p>
        </a>
      </li>

      <li class="nav-item has-treeview active">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-copy"></i>
          <p>
            Transaksi Surat
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_masuk/jumlah_surat_masuk.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Surat Masuk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_keluar/jumlah_surat_keluar.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Surat Keluar</p>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-chart-pie"></i>
          <p>
           Buku Agenda
            <i class="right fas fa-angle-left"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_masuk/agendasm.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Agenda Masuk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_keluar/agendask.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Agenda Keluar</p>
            </a>
          </li>
        </ul>
      </li>

       <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon far fa-image"></i>
          <p>
            Galeri
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_masuk/galerism.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Galeri Masuk</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/surat_keluar/galerisk.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Galeri Keluar</p>
            </a>
          </li>
        </ul>
      </li>

       <?php if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2): ?>
       <li class="nav-item has-treeview">
      <a href="<?= $base_url ?>modules/master/klasifikasi/klasifikasi.php" class="nav-link">
        <i class="nav-icon fas fa-tree"></i>
        <p>Referensi</p>
      </a>
    </li>
     <?php endif; ?>

    <?php if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2): ?>
      <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
          <i class="nav-icon fas fa-cog"></i>
          <p>
           Pengaturan
            <i class="fas fa-angle-left right"></i>
          </p>
        </a>
        <ul class="nav nav-treeview">
          <?php if ($_SESSION['id_user'] == 1): ?>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/master/instansi/instansi.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Instansi</p>
            </a>
          </li>
           <?php endif; ?>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/master/user/pengguna.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>User</p>
            </a>
          </li>
          <?php if ($_SESSION['admin'] == 1): ?>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/maintenance/backup.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Backup Database</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= $base_url ?>modules/maintenance/restore.php" class="nav-link">
              <i class="far fa-circle nav-icon"></i>
              <p>Restore Database</p>
            </a>
          </li>
         <?php endif; ?>
           <?php endif; ?>
        </ul>
      </li>
      
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->

  </aside>
