<?php
session_start();
include_once '../../../config/koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $website = mysqli_real_escape_string($koneksi, $_POST['website']);
    $institusi = mysqli_real_escape_string($koneksi, $_POST['institusi']);
    $kepsek = mysqli_real_escape_string($koneksi, $_POST['kepsek']);
    $nidn = mysqli_real_escape_string($koneksi, $_POST['nidn']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);

    $upload_logo = '';
    $upload_dir = '../../../upload/';
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $max_size = 10 * 1024 * 1024; // 10MB

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $filename = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_ext)) {
            echo "<script>alert('Format file tidak diperbolehkan. Hanya JPG, JPEG, PNG.'); window.location.href='instansi.php';</script>";
            exit;
        }

        if ($file_size > $max_size) {
            echo "<script>alert('Ukuran file melebihi batas maksimal 10MB.'); window.location.href='instansi.php';</script>";
            exit;
        }

        $new_filename = 'logo_' . time() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_filename;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $upload_logo = $new_filename;

            // Update dengan logo
            $query_update = "UPDATE tbl_instansi SET 
                nama = '$nama',
                alamat = '$alamat',
                website = '$website',
                institusi = '$institusi',
                kepsek = '$kepsek',
                nidn = '$nidn',
                email = '$email',
                logo = '$upload_logo'
                LIMIT 1";
        } else {
            echo "<script>alert('Gagal upload logo.'); window.location.href='instansi.php';</script>";
            exit;
        }
    } else {
        // Update tanpa logo
        $query_update = "UPDATE tbl_instansi SET 
            nama = '$nama',
            alamat = '$alamat',
            website = '$website',
            institusi = '$institusi',
            kepsek = '$kepsek',
            nidn = '$nidn',
            email = '$email'
            LIMIT 1";
    }

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data berhasil disimpan'); window.location.href='instansi.php?info=update';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "'); window.location.href='instansi.php';</script>";
    }
}
?>
