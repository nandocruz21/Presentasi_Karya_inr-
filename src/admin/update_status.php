<?php
// Hubungkan ke database
include 'koneksi.php';

// Cek apakah ada data 'id' dan 'status' yang dikirim oleh kurir JavaScript
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id_santri = $_POST['id'];
    $status_baru = $_POST['status'];

    // Lakukan proses UPDATE ke tabel santri
    $query = "UPDATE santri SET kehadiran = '$status_baru' WHERE id_santri = '$id_santri'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "Berhasil diupdate";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
} else {
    echo "Data tidak lengkap";
}
?>