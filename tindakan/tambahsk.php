<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_agenda   = $_POST['no_agenda'];
    $no_surat    = $_POST['no_surat'];
    $keterangan  = $_POST['keterangan'];
    $tujuan      = $_POST['tujuan'];
    $tgl_surat   = $_POST['tgl_surat'];
    $tgl_catat   = date('Y-m-d');
    $isi         = $_POST['isi'];

    $id_user = $_SESSION['id_user'];

    // Cek duplikasi no_agenda atau no_surat
    $cek = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE no_agenda = '$no_agenda' OR no_surat = '$no_surat'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Nomor Agenda atau Nomor Surat sudah digunakan!');
                window.location.href = '../jumlah_surat_keluar.php';
              </script>";
        exit();
    }

    $file_name = '';
    if (!empty($_FILES['file_surat']['name'])) {
        $file_tmp  = $_FILES['file_surat']['tmp_name'];
        $file_name = basename($_FILES['file_surat']['name']);
        $file_path = "../upload/surat_keluar/" . $file_name;

        // Validasi ukuran
        if ($_FILES['file_surat']['size'] > 10485760) { // 10MB
            die("Ukuran file terlalu besar.");
        }

        move_uploaded_file($file_tmp, $file_path);
    }

    $query = mysqli_query($koneksi, "INSERT INTO tbl_surat_keluar
    (no_agenda, no_surat, keterangan, tujuan, tgl_surat, tgl_catat,isi, file, id_user)
    VALUES 
    ('$no_agenda', '$no_surat', '$keterangan', '$tujuan','$tgl_surat','$tgl_catat', '$isi', '$file_name', '$id_user')");

    if ($query) {
        header("Location: ../jumlah_surat_keluar.php?info=simpan");
        exit();
    } else {
        die("Gagal menyimpan data: " . mysqli_error($koneksi));
    }
}
?>
