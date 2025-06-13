<?php
include "connect.php";

$id         = isset($_POST['id']) ? htmlentities($_POST['id']) : "";
$nama_menu  = isset($_POST['nama_menu']) ? htmlentities($_POST['nama_menu']) : "";
$keterangan = isset($_POST['keterangan']) ? htmlentities($_POST['keterangan']) : "";
$kat_menu   = isset($_POST['kat_menu']) ? htmlentities($_POST['kat_menu']) : "";
$harga      = isset($_POST['harga']) ? htmlentities($_POST['harga']) : "";
$stok       = isset($_POST['stok']) ? htmlentities($_POST['stok']) : "";

$target_dir = "../assets/img/";
$uploadOk = 1;
$nama_file = "";

if (!empty($_POST['input_menu_validate'])) {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Validasi gambar
        $image_info = getimagesize($_FILES['foto']['tmp_name']);
        $imageType = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['foto']['size'];
        $original_name = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['foto']['name']));
        $nama_file_baru = strtolower(pathinfo($original_name, PATHINFO_FILENAME)) . "_" . time() . "." . $imageType;
        $target_file = $target_dir . $nama_file_baru;

        if ($image_info === false) {
            $uploadOk = 0;
            $message = "File bukan gambar.";
        } elseif ($file_size > 500000) {
            $uploadOk = 0;
            $message = "Ukuran gambar maksimal 500KB.";
        } elseif (!in_array($imageType, array('jpg', 'jpeg', 'png', 'gif'))) {
            $uploadOk = 0;
            $message = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        }

        if ($uploadOk) {
            // Hapus foto lama
            $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM tb_daftar_menu WHERE id = '$id'"));
            if ($old && file_exists($target_dir . $old['foto'])) {
                unlink($target_dir . $old['foto']);
            }

            // Simpan foto baru
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $nama_file = $nama_file_baru;
            } else {
                $uploadOk = 0;
                $message = "Gagal mengupload gambar baru.";
            }
        }
    } else {
        // Tidak mengubah gambar
        $old = mysqli_fetch_assoc(mysqli_query($conn, "SELECT foto FROM tb_daftar_menu WHERE id = '$id'"));
        $nama_file = $old['foto'];
    }

    // Proses update jika upload berhasil
    if ($uploadOk) {
        $query = mysqli_query(
            $conn,
            "UPDATE tb_daftar_menu SET 
                nama_menu = '$nama_menu',
                keterangan = '$keterangan',
                kategori = '$kat_menu',
                harga = '$harga',
                stok = '$stok',
                foto = '$nama_file'
            WHERE id = '$id'"
        );

        if ($query) {
            $message = '<script>alert("Data berhasil diubah"); window.location="../menu"</script>';
        } else {
            $message = '<script>alert("Gagal mengubah data"); window.location="../menu"</script>';
        }
    } else {
        $message = '<script>alert("' . $message . '"); window.location="../menu"</script>';
    }

    echo $message;
}
