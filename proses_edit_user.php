<?php
include "connect.php";
session_start();

$id = $_POST['id'];
$username_session = $_SESSION['username_vienna_coffee']; // Sesuaikan dengan session kamu

// Cegah user lain edit akun utama
if ($id == 1 && $username_session != 'admin@gmail.com') {
    echo "<script>alert('Anda tidak diizinkan mengedit akun utama!'); window.location.href='../user';</script>";
    exit;
}


$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$nama = (isset($_POST['nama'])) ? htmlentities($_POST['nama']) : "";
$username = (isset($_POST['username'])) ? htmlentities($_POST['username']) : "";
$level = (isset($_POST['level'])) ? htmlentities($_POST['level']) : "";
$nohp = (isset($_POST['nohp'])) ? htmlentities($_POST['nohp']) : "";
$alamat = (isset($_POST['alamat'])) ? htmlentities($_POST['alamat']) : "";

if (!empty($_POST['input_user_validate'])) {
    // Cek username sudah dipakai user lain
    $select = mysqli_query($conn, "SELECT * FROM tb_user WHERE username = '$username' AND id != '$id'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Username yang dimasukan sudah digunakan!")
        window.location="../user"</script>';
    } else {
        $query = mysqli_query($conn, "UPDATE tb_user SET nama='$nama', username='$username', level='$level', nohp='$nohp', alamat='$alamat' WHERE id='$id'");
        if ($query) {
            // Update session jika user sedang login dan mengganti username
            if ($_SESSION['username_vienna_coffee'] == $_POST['username']) {
                $_SESSION['username_vienna_coffee'] = $username;
            }
            $message = '<script>alert("Data Berhasil diUpdate")
            window.location="../user"</script>';
        } else {
            $message = '<script>alert("Data Gagal diUpdate")
                window.location="../user"</script>';
        }
    }
}
echo $message;
