<?php
session_start(); // Tambahkan session_start() di awal

include 'koneksi.php';

// ========== PERBAIKAN UTAMA ==========
// Proses tanggal lahir: jika kosong, set NULL, jika ada, set dengan tanda kutip
$tanggal_lahir_raw = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '';
if (empty($tanggal_lahir_raw)) {
    $tanggal_lahir = "NULL";
} else {
    $tanggal_lahir = "'" . mysqli_real_escape_string($koneksi, $tanggal_lahir_raw) . "'";
}

// Ambil data utama dari form
$id      = isset($_POST['id_santri']) ? $_POST['id_santri'] : '';
$nama    = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$capaian = mysqli_real_escape_string($koneksi, $_POST['capaian'] ?? '');
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

// Ambil data biodata
$tempat_lahir  = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir'] ?? '');
$alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? '');
$nama_ortu     = mysqli_real_escape_string($koneksi, $_POST['nama_ortu'] ?? '');

// ---> INI BAGIAN YANG KURANG SEBELUMNYA: Menangkap No WA Ortu <---
$no_wa_ortu    = mysqli_real_escape_string($koneksi, $_POST['no_wa_ortu'] ?? '');

// Set default value untuk field yang kosong agar rapi di tampilan
if (empty($tempat_lahir)) $tempat_lahir = "-";
if (empty($alamat)) $alamat = "-";
if (empty($nama_ortu)) $nama_ortu = "-";
if (empty($no_wa_ortu)) $no_wa_ortu = "-"; // Beri strip jika WA tidak diisi
if (empty($catatan)) $catatan = "- Belum ada catatan -";

// Jalankan query
if ($id == "") {
    // Mode INSERT (Tambah Baru) - Tambahkan no_wa_ortu di sini
    $query = "INSERT INTO santri (nama_lengkap, tempat_lahir, tanggal_lahir, alamat, nama_ortu, no_wa_ortu, capaian_hafalan, catatan_pengajar, kehadiran) 
              VALUES ('$nama', '$tempat_lahir', $tanggal_lahir, '$alamat', '$nama_ortu', '$no_wa_ortu', '$capaian', '$catatan', 'hadir')";
} else {
    // Mode UPDATE (Edit) - Tambahkan no_wa_ortu di sini
    $query = "UPDATE santri SET 
              nama_lengkap = '$nama', 
              tempat_lahir = '$tempat_lahir',
              tanggal_lahir = $tanggal_lahir,
              alamat = '$alamat',
              nama_ortu = '$nama_ortu',
              no_wa_ortu = '$no_wa_ortu', 
              capaian_hafalan = '$capaian', 
              catatan_pengajar = '$catatan' 
              WHERE id_santri = '$id'";
}

// Eksekusi query
if (mysqli_query($koneksi, $query)) {
    // Setelah berhasil simpan
    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = ($id == "") ? 'Data santri berhasil ditambahkan!' : 'Data santri berhasil diperbarui!';
    
    // Redirect kembali ke halaman admin
    header("Location: admin_santri.php?status=success");
    exit;
} else {
    // Set session alert untuk error dan tampilkan
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}

// Tutup koneksi
mysqli_close($koneksi);
?>