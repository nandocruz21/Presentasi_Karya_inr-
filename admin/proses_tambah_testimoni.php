<?php
session_start();
if(!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"]!="login"){
  header("Location:login.php");
  exit;
}
include '../config/koneksi.php'; 

if(isset($_POST['simpan'])){
    // 1. Tangkap semua data dari Form
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_wali']);
    $kelas = 'Wali Santri';
    $inisial = mysqli_real_escape_string($koneksi, $_POST['inisial']);
    $rating = (int) $_POST['rating'];
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_testimoni']);

    // 2. Buat Query INSERT ke tabel testimoni
    $query = "INSERT INTO testimoni (nama_wali, kelas_santri, inisial, rating, isi_testimoni) 
              VALUES ('$nama', '$kelas', '$inisial', '$rating', '$isi')";

    // 3. Eksekusi Query
    if(mysqli_query($koneksi, $query)){
        echo "<script>alert('Testimoni berhasil ditambahkan!'); window.location='input_testimoni.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!'); window.history.back();</script>";
    }
}
?>
