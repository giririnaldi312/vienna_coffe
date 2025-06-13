<?php
session_start();
include "connect.php";

$kode_order = isset($_POST['kode_order']) ? htmlentities($_POST['kode_order']) : "";
$meja       = isset($_POST['meja']) ? htmlentities($_POST['meja']) : "";
$pelanggan  = isset($_POST['pelanggan']) ? htmlentities($_POST['pelanggan']) : "";

if (!empty($_POST['input_order_validate'])) {
    // Cek apakah kode order sudah ada
    $select = mysqli_query($conn, "SELECT * FROM tb_order WHERE id_order = '$kode_order'");
    if (mysqli_num_rows($select) > 0) {
        echo '<script>
            alert("Kode order sudah digunakan!");
            window.location = "../order";
        </script>';
    } else {
        // Insert data order
        $pelayan = $_SESSION['id_vienna_coffee']; // pastikan ini sudah diset saat login
        $query = mysqli_query($conn, "INSERT INTO tb_order (id_order, meja, pelanggan, pelayan) 
                                      VALUES ('$kode_order', '$meja', '$pelanggan', '$pelayan')");

        if ($query) {
            echo '<script>
                alert("Data berhasil dimasukkan!");
                window.location = "../?x=orderitem&order=' . $kode_order . '&meja=' . $meja . '&pelanggan=' . $pelanggan . '";
            </script>';
        } else {
            echo '<script>
                alert("Data gagal dimasukkan!");
                window.location = "../order";
            </script>';
        }
    }
}
