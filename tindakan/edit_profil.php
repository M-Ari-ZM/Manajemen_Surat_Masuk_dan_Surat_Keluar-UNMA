<?php
session_start();
require '../koneksi.php'; 

$id_user = $_POST['id_user'];
$username = $_POST['username'];
$nama = $_POST['nama'];
$nip = $_POST['nip'];
$password_lama = md5($_POST['password_lama']);  

$query = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE id_user = '$id_user'");
$data = mysqli_fetch_assoc($query);

if ($password_lama === $data['password']) {
    $update_query = "UPDATE tbl_user SET username = '$username', nama = '$nama', nip = '$nip'";
    if (!empty($_POST['password_baru'])) {
        $password_baru = md5($_POST['password_baru']); 
        $update_query .= ", password = '$password_baru'";
    }

    $update_query .= " WHERE id_user = '$id_user'";
    mysqli_query($koneksi, $update_query);
    session_destroy();
    echo "<script>
        alert('Profil berhasil diupdate. Silakan login ulang.');
        window.location='../login.php';
    </script>";
} else {
    echo "<script>
        alert('Password lama tidak cocok.');
        window.location='../profil.php';
    </script>";
}
?>
