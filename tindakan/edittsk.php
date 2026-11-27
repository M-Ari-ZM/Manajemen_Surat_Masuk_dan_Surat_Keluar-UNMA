<?php
include '../koneksi.php';

if (isset($_POST['id_sk'])) {
    $id_sk      = $_POST['id_sk'];
    $perihal    = $_POST['perihal'];
    $no_sk      = $_POST['no_sk'];
    $tgl_sk     = $_POST['tgl_sk'];
    $sumber_sk  = $_POST['sumber_sk'];

    $folder     = '../upload/surat_keluar/';
    $file_name  = '';

    $result = mysqli_query($koneksi, "SELECT file FROM sk WHERE id_sk = '$id_sk'");
    $row = mysqli_fetch_assoc($result);
    $old_file = $row['file'];

    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === 0) {
        $uploaded_name = $_FILES['file_surat']['name'];
        $uploaded_tmp  = $_FILES['file_surat']['tmp_name'];
        $file_ext      = strtolower(pathinfo($uploaded_name, PATHINFO_EXTENSION));
        $allowed_ext   = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'pdf'];

        // Validasi ukuran
        if ($_FILES['file_surat']['size'] > 10 * 1024 * 1024) {
            die("Ukuran file melebihi 10MB.");
        }

        // Validasi tipe
        if (!in_array($file_ext, $allowed_ext)) {
            die("Format file tidak diperbolehkan.");
        }
        $new_file_name = uniqid('sk_', true) . '.' . $file_ext;
        $file_path = $folder . $new_file_name;

        if (!empty($old_file) && file_exists($folder . $old_file)) {
            unlink($folder . $old_file);
        }
        if (!move_uploaded_file($uploaded_tmp, $file_path)) {
            die("Gagal mengupload file baru.");
        }

        $file_name = $new_file_name;
    }

    // Query update
    if (!empty($file_name)) {
        $query = "UPDATE sk SET 
                    perihal = '$perihal',
                    no_sk = '$no_sk',
                    tgl_sk = '$tgl_sk',
                    sumber_sk = '$sumber_sk',
                    file = '$file_name'
                  WHERE id_sk = '$id_sk'";
    } else {
        $query = "UPDATE sk SET 
                    perihal = '$perihal',
                    no_sk = '$no_sk',
                    tgl_sk = '$tgl_sk',
                    sumber_sk = '$sumber_sk'
                  WHERE id_sk = '$id_sk'";
    }
    if (mysqli_query($koneksi, $query)) {
        header("location:../rektorat.php?info=update");
        exit;
    } else {
        die("Gagal update: " . mysqli_error($koneksi));
    }
}
?>
