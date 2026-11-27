<?php
session_start();
include 'koneksi.php';

// Fungsi restore database
function restore($host, $username, $password, $database, $file) {
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (strtolower($ext) !== 'sql') {
        $_SESSION['errFormat'] = 'File harus berformat .sql';
        return;
    }

    $backupDir = 'backup/';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    $filename = basename($file['name']);
    $timestamp = date('Ymd_His');
    $destination = $backupDir . $timestamp . '_' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $_SESSION['errUpload'] = 'Gagal menyimpan file ke folder backup.';
        return;
    }

    $koneksi = new mysqli($host, $username, $password, $database);
    if ($koneksi->connect_error) {
        $_SESSION['errUpload'] = 'Gagal koneksi ke database.';
        return;
    }

    $sqlContent = file_get_contents($destination);

    if ($koneksi->multi_query($sqlContent)) {
        do {
            $koneksi->store_result();
        } while ($koneksi->next_result());
    } else {
        $_SESSION['errUpload'] = 'Gagal mengeksekusi file SQL: ' . $koneksi->error;
        return;
    }

    $koneksi->close();

    $_SESSION['succRestore'] = 'Restore database berhasil. File disimpan di folder backup.';
}


// Proses restore jika form disubmit, harus di atas agar header redirect bisa jalan tanpa error
if (isset($_POST['restore'])) {
      restore($host, $username, $password, $database, $_FILES['file']);
    header("Location: restore.php");
    exit;
}

include 'layouts/header.php';
include 'layouts/navbar.php';
include 'layouts/sidebar.php';

if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
    header("Location: login.php");
    exit;
} else {
    if ($_SESSION['admin'] != 1) {
        echo '<script>
                alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                window.location.href="logout.php";
              </script>';
        exit;
    }
}
?> 


<!-- Mulai konten -->
<div class="content-wrapper">
    <div class="container-fluid mt-4">
        <div class="row mb-3">
            <div class="col">
                <h4><i class="fas fa-database"></i> Restore Database</h4>
                <hr>
            </div>
        </div>

        <?php
        $alerts = [
            'errEmpty'     => ['type' => 'danger', 'icon' => 'times-circle'],
            'errFormat'    => ['type' => 'danger', 'icon' => 'times-circle'],
            'errUpload'    => ['type' => 'danger', 'icon' => 'times-circle'],
            'succRestore'  => ['type' => 'success', 'icon' => 'check-circle']
        ];

        foreach ($alerts as $key => $alert) {
            if (isset($_SESSION[$key])) {
                $message = $_SESSION[$key];
                echo '
                <div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">
                    <i class="fas fa-' . $alert['icon'] . '"></i> ' . $message . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                unset($_SESSION[$key]);
            }
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header bg-default text-black">
                        <h3 class="card-title"><i class=""></i> Restore Database</h3>
                    </div>
                    <div class="card-body">
                        <p>Silakan pilih file database lalu klik tombol <strong>"Restore"</strong> untuk melakukan restore database dari hasil backup yang telah dibuat sebelumnya. Jika belum ada file database hasil backup, silakan lakukan backup terlebih dahulu melalui menu<strong><a href="backup.php" class="text-blue">"Backup Database"</a></strong>.</p>
                        <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> <strong>PERINGATAN !</strong></p>
                        <p>Berhati - hatilah ketika merestore database karena <span class="text-danger">data yang ada akan diganti dengan data yang baru</span>. Pastikan bahwa file database yang akan digunakan untuk merestore adalah<strong>"benar-benar"</strong> file backup database yang telah dibuat sebelumnya sehingga sistem dapat berjalan dengan normal dan tidak mengalami error.</p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-row align-items-end">
                                <!-- Input File -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="file" class="sr-only">Upload File SQL</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file" accept=".sql" required>
                                            <label class="custom-file-label" for="file">Pilih file backup database sql</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input Password dengan ikon lock -->
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label for="password_lama" class="sr-only">Password</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            </div>
                                            <input type="password" class="form-control" id="password_lama" name="password" placeholder="Masukkan password Anda" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Restore -->
                                <div class="col-md-2">
                                    <div class="form-group position-relative">
                                        <button type="submit" name="restore" class="btn btn-warning btn-lg btn-block">
                                            <i class="fas fa-database"></i> Restore
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <script>
                        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                            var fileName = e.target.files[0].name;
                            var nextSibling = e.target.nextElementSibling;
                            nextSibling.innerText = fileName;
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

<?php
include 'layouts/footer.php';
?>
 <script>
            setTimeout(function() {
             $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
                  });
                }, 3000);
           </script>

