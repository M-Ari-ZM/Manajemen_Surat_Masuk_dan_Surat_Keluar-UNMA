<?php
session_start();
include '../koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $no_agenda    = $_POST['no_agenda'];
    $isi          = $_POST['isi'];
    $tgl_surat    = $_POST['tgl_surat'];
    $tgl_diterima = date('Y-m-d'); 
    $no_surat     = $_POST['no_surat'];
    $asal_surat   = $_POST['asal_surat'];
    $keterangan   = $_POST['keterangan'];

    $id_user = $_SESSION['id_user']; 

    // Cek duplikasi no_agenda atau no_surat
    $cek = mysqli_query($koneksi, "SELECT * FROM tbl_surat_masuk WHERE no_agenda = '$no_agenda' OR no_surat = '$no_surat'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Nomor Agenda atau Nomor Surat sudah digunakan!');
                window.location.href = '../jumlah_surat_masuk.php';
              </script>";
        exit();
    }

    $file_name = '';
    if (!empty($_FILES['file_surat']['name'])) {
        $file_tmp  = $_FILES['file_surat']['tmp_name'];
        $file_name = basename($_FILES['file_surat']['name']);
        $file_path = "../upload/surat_masuk/" . $file_name;

        // Validasi ukuran
        if ($_FILES['file_surat']['size'] > 10485760) { // 10MB
            die("Ukuran file terlalu besar.");
        }

        move_uploaded_file($file_tmp, $file_path);
    }

    $query = mysqli_query($koneksi, "INSERT INTO tbl_surat_masuk 
    (no_agenda, isi, tgl_surat, tgl_diterima, no_surat, asal_surat, keterangan, file, id_user)
    VALUES 
    ('$no_agenda', '$isi', '$tgl_surat', '$tgl_diterima', '$no_surat', '$asal_surat', '$keterangan', '$file_name', '$id_user')");

    if ($query) {
        header("Location: ../jumlah_surat_masuk.php?info=simpan");
        exit();
    } else {
        die("Gagal menyimpan data: " . mysqli_error($koneksi));
    }
}
?>
