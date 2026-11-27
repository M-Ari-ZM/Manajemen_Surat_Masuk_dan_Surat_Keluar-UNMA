<?php
session_start();
include_once '../../../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $perihal    = $_POST['perihal'];
    $no_sk      = $_POST['no_sk'];
    $tgl_sk     = $_POST['tgl_sk'];
    $sumber_sk  = $_POST['sumber_sk'];

    if (empty($perihal) || empty($no_sk) || empty($tgl_sk) || empty($sumber_sk)) {
        die("Semua field harus diisi.");
    }

    // Cek duplikasi no_sk
    $cek = mysqli_query($koneksi, "SELECT * FROM sk WHERE no_sk = '$no_sk'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Nomor SK sudah digunakan!');
                window.location.href = '../rektorat.php';
              </script>";
        exit();
    }

    $file_name = '';
    if (!empty($_FILES['file_surat']['name'])) {
        $file_tmp  = $_FILES['file_surat']['tmp_name'];
        $file_name = basename($_FILES['file_surat']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'pdf'];

        if ($_FILES['file_surat']['size'] > 10485760) {
            die("Ukuran file terlalu besar.");
        }

        if (!in_array($file_ext, $allowed_ext)) {
            die("Format file tidak diperbolehkan.");
        }

        // Rename file agar unik
        $new_file_name = uniqid('sk_', true) . '.' . $file_ext;
        $file_path = "../../../upload/surat_keluar/" . $new_file_name;

        move_uploaded_file($file_tmp, $file_path);
        $file_name = $new_file_name;
    }

    $query = mysqli_query($koneksi, "INSERT INTO sk 
    (perihal, no_sk, tgl_sk, sumber_sk, file)
    VALUES 
    ('$perihal', '$no_sk', '$tgl_sk', '$sumber_sk', '$file_name')");

    if ($query) {
        header("Location: ../rektorat.php?info=simpan");
        exit();
    } else {
        die("Gagal menyimpan data: " . mysqli_error($koneksi));
    }
}
?>
