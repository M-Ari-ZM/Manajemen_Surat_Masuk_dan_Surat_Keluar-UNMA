<?php
include_once '../../../config/koneksi.php';

if (isset($_POST['id_surat'])) {
    // Ambil data dari form
    $id_surat     = $_POST['id_surat'];
    $no_agenda    = $_POST['no_agenda'];
    $no_surat     = $_POST['no_surat'];
    $keterangan   = $_POST['keterangan'];
    $tujuan       = $_POST['tujuan'];
    $tgl_surat    = $_POST['tgl_surat'];

    // Cek apakah no_agenda sudah digunakan (oleh data lain)
    $cek_agenda = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE no_agenda = '$no_agenda' AND id_surat != '$id_surat'");
    if (mysqli_num_rows($cek_agenda) > 0) {
        echo "<script>alert('Nomor Agenda atau Nomor Surat sudah digunakan!');window.history.back();</script>";
        exit;
    }

    // Cek apakah no_surat sudah digunakan (oleh data lain)
    $cek_surat = mysqli_query($koneksi, "SELECT * FROM tbl_surat_keluar WHERE no_surat = '$no_surat' AND id_surat != '$id_surat'");
    if (mysqli_num_rows($cek_surat) > 0) {
        echo "<script>alert('Nomor Agenda atau Nomor Surat sudah digunakan!');window.history.back();</script>";
        exit;
    }

    $query = "";

    // Cek apakah ada file baru yang diupload
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] === 0) {
        $folder     = '../../../upload/surat_keluar/';
        $fileName   = basename($_FILES['file_surat']['name']);
        $targetFile = $folder . $fileName;

        // Upload file
        if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $targetFile)) {
            // Update termasuk file
            $query = "UPDATE tbl_surat_keluar SET 
                        no_agenda = '$no_agenda',
                        no_surat = '$no_surat',
                        keterangan = '$keterangan',
                        tujuan = '$tujuan',
                        tgl_surat = '$tgl_surat',
                        file = '$fileName'
                      WHERE id_surat = '$id_surat'";
        } else {
            // Upload gagal, update tanpa file
            $query = "UPDATE tbl_surat_keluar SET 
                        no_agenda = '$no_agenda',
                        no_surat = '$no_surat',
                        keterangan = '$keterangan',
                        tujuan = '$tujuan',
                        tgl_surat = '$tgl_surat'
                      WHERE id_surat = '$id_surat'";
        }
    } else {
        // Tidak ada file baru, update tanpa file
        $query = "UPDATE tbl_surat_keluar SET 
                    no_agenda = '$no_agenda',
                    no_surat = '$no_surat',
                    keterangan = '$keterangan',
                    tujuan = '$tujuan',
                    tgl_surat = '$tgl_surat'
                  WHERE id_surat = '$id_surat'";
    }

    // Eksekusi query
    mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));

    // Redirect setelah berhasil
    header("location:../jumlah_surat_keluar.php?info=update");
    exit;
}
?>
