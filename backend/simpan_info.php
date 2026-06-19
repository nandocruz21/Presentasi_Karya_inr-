<?php
session_start();

// Hubungkan ke database
include '../config/koneksi.php';

// Cek apakah ada data yang dikirim melalui tombol Submit (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Tangkap data dari form (atribut name)
    $id_info = $_POST['id_info'];
    $judul   = $_POST['judul'];
    $isi     = $_POST['isi'];

    // 2. Keamanan: Bersihkan teks dari karakter aneh (SQL Injection)
    $judul = mysqli_real_escape_string($koneksi, $judul);
    $isi   = mysqli_real_escape_string($koneksi, $isi);

    // 3. Logika Percabangan: TAMBAH BARU atau EDIT LAMA?
    if (empty($id_info)) {
        // JIKA ID KOSONG = Berarti ini pengumuman baru
        $query = "INSERT INTO informasi (kategori, judul_info, isi_info, tanggal_posting) 
                  VALUES ('PENGUMUMAN', '$judul', '$isi', CURRENT_DATE)";
    } else {
        // JIKA ID ADA ISINYA = Berarti ini sedang mengedit pengumuman lama
        $query = "UPDATE informasi 
                  SET judul_info = '$judul', isi_info = '$isi' 
                  WHERE id_info = '$id_info'";
    }

    // 4. Eksekusi Perintah SQL di atas
    if (mysqli_query($koneksi, $query)) {
        // Jika sukses, tendang kembali ke halaman Papan Informasi
        header("Location: ../admin/admin_info.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Aduh! Gagal menyimpan pengumuman: " . mysqli_error($koneksi);
    }

} else {
    // Jika file ini diakses langsung dari URL tanpa isi form, kembalikan ke halaman info
    header("Location: ../admin/admin_info.php");
    exit;
}
?>
