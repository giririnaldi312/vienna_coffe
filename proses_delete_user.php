<?php
include "connect.php";

// Ambil ID dari POST
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

// Cek validitas ID
if ($id <= 0) {
    echo "<script>alert('ID tidak ditemukan!'); window.location.href='../user';</script>";
    exit;
}

// Cegah hapus akun utama
if ($id === 1) {
    echo "<script>alert('Akun utama tidak bisa dihapus!'); window.location.href='../user';</script>";
    exit;
}

// Cegah user hapus dirinya sendiri
session_start();
$queryUser = mysqli_query($conn, "SELECT username FROM tb_user WHERE id = '$id'");
$rowUser = mysqli_fetch_assoc($queryUser);
if ($rowUser && $rowUser['username'] === $_SESSION['username_vienna_coffee']) {
    echo "<script>alert('Anda tidak dapat menghapus akun Anda sendiri!'); window.location.href='../user';</script>";
    exit;
}

// Hapus user
$query = mysqli_query($conn, "DELETE FROM tb_user WHERE id = '$id'");
if ($query) {
    echo "<script>alert('User berhasil dihapus!'); window.location.href='../user';</script>";
} else {
    echo "<script>alert('Gagal menghapus user!'); window.location.href='../user';</script>";
}
