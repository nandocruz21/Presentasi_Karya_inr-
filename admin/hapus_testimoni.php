<?php
session_start();
if(!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"]!="login"){
  header("Location:login.php");
  exit;
}
include '../config/koneksi.php';

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $hapus = mysqli_query($koneksi, "DELETE FROM testimoni WHERE id_testi = '$id'");
    
    if($hapus){
        echo "<script>alert('Testimoni berhasil dihapus!'); window.location='input_testimoni.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus!'); window.location='input_testimoni.php';</script>";
    }
} else {
    header("Location:input_testimoni.php");
}
?>
