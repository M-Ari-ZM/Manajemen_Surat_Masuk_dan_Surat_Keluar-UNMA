<?php
session_start();
$base_url = "/unma/";
include '../../config/koneksi.php';
if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    header("Location: ../../index.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manajemen Surat</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $base_url ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= $base_url ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $base_url ?>assets/dist/css/adminlte.min.css">
  <!-- icon -->
  <link rel="shortcut icon" href="<?= $base_url ?>upload/unma.png" type="image/x-icon">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <h1>UNIVERSITAS MAJALENGKA</h1>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Aplikasi Manajemen Surat</p>
       <?php
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
        ?>
          <script>
            setTimeout(function() {
             $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
                  });
                }, 3000);
           </script>

        <form action="cek_login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="fas fa-user"></i>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="fas fa-lock"></i>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </div>
      </form>
       <p class="mt-3 mb-1">
        <!-- <a href="lupasandi.php">lupa sandi?</a> -->
      </p>
      </div>
      <!-- /.social-auth-links -->

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= $base_url ?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?= $base_url ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= $base_url ?>assets/dist/js/adminlte.min.js"></script>
</body>
</html>
