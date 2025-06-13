<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$jenismenu = (isset($_POST['jenismenu'])) ? htmlentities($_POST['jenismenu']) : "";
$katmenu = (isset($_POST['katmenu'])) ? htmlentities($_POST['katmenu']) : "";

if (!empty($_POST['input_katmenu_validate'])) {
    // Cek duplikat tapi kecualikan data yang sedang diedit
    $select = mysqli_query($conn, "SELECT kategori_menu FROM tb_kategori_menu WHERE kategori_menu = '$katmenu' AND id_kat_menu != '$id'");
    if (mysqli_num_rows($select) > 0) {
        $message = '<script>alert("Kategori menu yang dimasukan sudah digunakan!");
        window.location="../katmenu"</script>';
    } else {
        $query = mysqli_query($conn, "UPDATE tb_kategori_menu SET jenis_menu='$jenismenu', kategori_menu='$katmenu' WHERE id_kat_menu='$id'");
        if ($query) {
            $message = '<script>alert("Data Berhasil diUpdate");
            window.location="../katmenu"</script>';
        } else {
            $message = '<script>alert("Data Gagal diUpdate");
            window.location="../katmenu"</script>';
        }
    }
}
echo $message;
