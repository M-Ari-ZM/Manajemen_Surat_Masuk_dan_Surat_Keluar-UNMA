<?php
session_start();
// Redirect ke login jika belum login
if (!isset($_SESSION['nama']) || !isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
include 'layouts/header.php';
include 'layouts/navbar.php';
include 'layouts/sidebar.php';

function backup($host, $user, $pass, $dbname, $nama_file, $tables = '*') {
    $koneksi = new mysqli($host, $user, $pass, $dbname);
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    $return = '';
    $koneksi->query("SET NAMES 'utf8'");

    if ($tables == '*') {
        $tables = array();
        $result = $koneksi->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
    } else {
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }

    foreach ($tables as $table) {
        $result = $koneksi->query("SELECT * FROM `$table`");
        $num_fields = $result->field_count;

        $return .= "DROP TABLE IF EXISTS `$table`;\n";
        $row2 = $koneksi->query("SHOW CREATE TABLE `$table`")->fetch_row();
        $return .= $row2[1] . ";\n\n";

       while ($row = $result->fetch_assoc()) {
    $values = array();
    foreach ($row as $value) {
        if (is_null($value)) {
            $values[] = "NULL";
        } else {
            $values[] = "'" . $koneksi->real_escape_string($value) . "'";
        }
    }

    $return .= "INSERT INTO `$table` VALUES(" . implode(',', $values) . ");\n";
}


    $return .= "\n\n";
} 
    // Simpan ke file
    $folder = 'backup/';
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $handle = fopen($folder . $nama_file, 'w+');
    fwrite($handle, $return);
    fclose($handle);

    $koneksi->close();
}

if (isset($_REQUEST['nama_file'])) {
    $back_dir = "backup/";
    $file     = $back_dir . $_REQUEST['nama_file'];
    $x        = explode('.', $file);
    $eks      = strtolower(end($x));

    if ($eks == 'sql') {
        if (file_exists($file)) {
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: private');
            header('Pragma: private');
            header('Content-Length: ' . filesize($file));
            flush();
            readfile($file);
            exit;
        } else {
            echo '<script>alert("File tidak ditemukan!"); window.location.href="backup.php";</script>';
        }
    } else {
        echo '<script>alert("Hanya file SQL yang bisa didownload!"); window.location.href="logout.php";</script>';
    }
}

$file = date("Y-m-d_His") . '.sql';

if (isset($_POST['backup'])) {
    backup($host, $username, $password, $database, $file,"*");
    $success = true;
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h1><i class="fas fa-database"></i> Backup Database</h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Success alert -->
            <?php if (!empty($success)) { ?>
                <div class="alert alert-success">
                    <strong>Sukses!</strong> Backup database berhasil. 
                    <a href="backup/<?= $file ?>" class="btn btn-primary btn-sm ml-2">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-header bg-default text-black">
                    <h3 class="card-title"><i class=""></i> Backup Database</h3>
                </div>
                <div class="card-body">
                    <p>
                        Lakukan backup database secara berkala untuk membuat cadangan database yang bisa direstore kapan saja ketika dibutuhkan. Klik tombol <strong>"Backup"</strong> untuk memulai proses backup. Setelah proses backup selesai, silakan download file backup database tersebut dan simpan di lokasi yang aman.
                        <span class="text-danger">*</span>
                    </p>
                    <p class="text-danger">
                        <strong>*</strong> Tidak disarankan menyimpan file backup database dalam my documents / Local Disk C.
                    </p>
                </div>
                <div class="card-footer">
                    <form method="post">
                        <button type="submit" name="backup" class="btn btn-success btn-lg">
                            <i class="fas fa-cloud-download-alt"></i> BACKUP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include 'layouts/footer.php'; ?>
