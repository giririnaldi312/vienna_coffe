<?php
include "connect.php";

$nama_menu  = isset($_POST['nama_menu']) ? htmlentities($_POST['nama_menu']) : "";
$keterangan = isset($_POST['keterangan']) ? htmlentities($_POST['keterangan']) : "";
$kat_menu   = isset($_POST['kat_menu']) ? htmlentities($_POST['kat_menu']) : "";
$harga      = isset($_POST['harga']) ? htmlentities($_POST['harga']) : "";
$stok       = isset($_POST['stok']) ? htmlentities($_POST['stok']) : "";

$target_dir = "../assets/img/";
$statusUpload = 1;
$message = "";
$nama_file = "";

if (!empty($_POST['input_menu_validate'])) {
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        // Validasi gambar
        $image_info = getimagesize($_FILES['foto']['tmp_name']);
        $imageType = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['foto']['size'];

        // Buat nama file unik berdasarkan nama menu + timestamp
        $safe_name = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($nama_menu));
        $nama_file_baru = $safe_name . "_" . time() . "." . $imageType;
        $target_file = $target_dir . $nama_file_baru;

        if ($image_info === false) {
            $statusUpload = 0;
            $message = "File bukan gambar.";
        } elseif ($file_size > 500000) {
            $statusUpload = 0;
            $message = "Ukuran gambar maksimal 500KB.";
        } elseif (!in_array($imageType, array('jpg', 'jpeg', 'png', 'gif'))) {
            $statusUpload = 0;
            $message = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau GIF.";
        } else {
            // Cek apakah nama menu sudah ada di database
            $select = mysqli_query($conn, "SELECT * FROM tb_daftar_menu WHERE nama_menu = '$nama_menu'");
            if (mysqli_num_rows($select) > 0) {
                $statusUpload = 0;
                $message = "Nama menu yang dimasukkan telah ada!";
            }
        }

        if ($statusUpload) {
            // Upload file gambar
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $nama_file = $nama_file_baru;

                // Insert data ke database
                $query = mysqli_query($conn, "INSERT INTO tb_daftar_menu (foto, nama_menu, keterangan, kategori, harga, stok) 
                    VALUES ('$nama_file', '$nama_menu', '$keterangan', '$kat_menu', '$harga', '$stok')");

                if ($query) {
                    $message = '<script>alert("Menu berhasil dimasukkan"); window.location="../menu"</script>';
                } else {
                    $message = '<script>alert("Menu gagal dimasukkan"); window.location="../menu"</script>';
                }
            } else {
                $message = '<script>alert("Gagal mengupload gambar"); window.location="../menu"</script>';
            }
        } else {
            $message = '<script>alert("' . $message . '"); window.location="../menu"</script>';
        }
    } else {
        $message = '<script>alert("Silakan pilih gambar terlebih dahulu"); window.location="../menu"</script>';
    }
}

echo $message;
