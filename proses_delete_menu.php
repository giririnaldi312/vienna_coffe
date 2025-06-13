<?php
include "connect.php";
$id = (isset($_POST['id'])) ? htmlentities($_POST['id']) : "";
$foto = (isset($_POST['foto'])) ? htmlentities($_POST['foto']) : "";

if(!empty($_POST['input_delete_validate'])){
    $query = mysqli_query($conn, "DELETE FROM tb_daftar_menu WHERE id = '$id'");
    if($query){
        unlink("../assets/img/$foto");
        $message = '<script>alert("Menu Berhasil diHapus");
        window.location="../menu"</script>';
       
    }else{
        $message = '<script>alert("Menu Gagal diHapus");
          window.location="../menu"</script>';
    }
}echo $message;
?>

