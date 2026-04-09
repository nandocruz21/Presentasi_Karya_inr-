<?php
// Hubungkan ke database (Sesuaikan path-nya jika perlu, ini asumsi file di sejajar databasekdr.php)
include '../admin/koneksi.php';

if (isset($_GET['id'])) {
    $id_santri = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Ambil data riwayat diurutkan dari yang terbaru (DESC)
    $query = mysqli_query($koneksi, "SELECT * FROM riwayat_progres WHERE id_santri = '$id_santri' ORDER BY tanggal_riwayat DESC");
    
    $data_riwayat = array();
    while ($row = mysqli_fetch_assoc($query)) {
        $data_riwayat[] = $row;
    }
    
    // Kembalikan data dalam bentuk JSON agar mudah dibaca oleh JavaScript
    echo json_encode($data_riwayat);
} else {
    echo json_encode([]);
}
?>