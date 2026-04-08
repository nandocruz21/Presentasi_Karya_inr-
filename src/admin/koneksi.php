<?php
$host       = "localhost";
$user       = "root";
$password   = "";             // Password default Laragon adalah kosong
$database   = "db_msantri";   // Nama database yang kita buat tadi

// Membuat koneksi menggunakan mysqli
$koneksi = mysqli_connect($host, $user, $password, $database);

// Atur zona waktu PHP ke Makassar (WITA)
date_default_timezone_set('Asia/Makassar');
// Atur zona waktu database MySQL ke WITA (UTC +8)
mysqli_query($koneksi, "SET time_zone = '+08:00'");

// Cek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Aduh! Koneksi database gagal, Brader: " . mysqli_connect_error());
}

// echo "Mantap! Koneksi ke database db_msantri berhasil! 🚀";

?>