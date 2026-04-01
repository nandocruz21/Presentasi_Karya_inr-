<?php
session_start();

// Hubungkan ke database
include 'koneksi.php';

// Cek apakah ada parameter 'id' yang dikirim lewat URL
if (isset($_GET['id'])) {
    // Tangkap ID-nya
    $id_info = $_GET['id'];

    // Keamanan: Pastikan ID bersih dari karakter aneh
    $id_info = mysqli_real_escape_string($koneksi, $id_info);

    // Buat perintah SQL untuk menghapus data berdasarkan ID tersebut
    $query = "DELETE FROM informasi WHERE id_info = '$id_info'";

    // Eksekusi perintahnya
    if (mysqli_query($koneksi, $query)) {
        // Jika sukses terhapus, tendang kembali ke halaman Papan Informasi secara diam-diam
        header("Location: admin_info.php");
        exit;
    } else {
        // Jika gagal
        echo "Aduh! Gagal menghapus pengumuman: " . mysqli_error($koneksi);
    }
} else {
    // Jika file ini diakses langsung tanpa bawa ID (Mencegah error)
    header("Location: admin_info.php");
    exit;
}
?>