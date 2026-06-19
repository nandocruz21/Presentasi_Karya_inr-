<?php
include '../config/koneksi.php';

// Ambil ID dari URL (?id=...)
$id = $_GET['id'];

// Jalankan perintah hapus
$hapus = mysqli_query($koneksi, "DELETE FROM santri WHERE id_santri = '$id'");

if ($hapus) {
    header("Location: ../admin/admin_santri.php");
} else {
    echo "Gagal menghapus data";
}
?>
