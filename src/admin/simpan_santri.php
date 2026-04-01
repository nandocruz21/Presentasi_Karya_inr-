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

// Ambil data dari form
$id      = isset($_POST['id_santri']) ? $_POST['id_santri'] : '';
$nama    = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$capaian = mysqli_real_escape_string($koneksi, $_POST['capaian'] ?? '');
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

// Ambil data biodata (set default "-" jika kosong)
$tempat_lahir  = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir'] ?? '');
$alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? '');
$nama_ortu     = mysqli_real_escape_string($koneksi, $_POST['nama_ortu'] ?? '');

// Set default value untuk field yang kosong
if (empty($tempat_lahir)) $tempat_lahir = "-";
if (empty($alamat)) $alamat = "-";
if (empty($nama_ortu)) $nama_ortu = "-";
if (empty($catatan)) $catatan = "- Belum ada catatan -";

// Jalankan query
if ($id == "") {
    // Mode INSERT (Tambah Baru)
    $query = "INSERT INTO santri (nama_lengkap, tempat_lahir, tanggal_lahir, alamat, nama_ortu, capaian_hafalan, catatan_pengajar, kehadiran) 
              VALUES ('$nama', '$tempat_lahir', $tanggal_lahir, '$alamat', '$nama_ortu', '$capaian', '$catatan', 'hadir')";
} else {
    // Mode UPDATE (Edit)
    $query = "UPDATE santri SET 
              nama_lengkap = '$nama', 
              tempat_lahir = '$tempat_lahir',
              tanggal_lahir = $tanggal_lahir,
              alamat = '$alamat',
              nama_ortu = '$nama_ortu',
              capaian_hafalan = '$capaian', 
              catatan_pengajar = '$catatan' 
              WHERE id_santri = '$id'";
}

// Eksekusi query
if (mysqli_query($koneksi, $query)) {
    // Set session alert untuk sukses
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => ($id == "") ? 'Data santri berhasil ditambahkan!' : 'Data santri berhasil diperbarui!'
    ];
} else {
    // Set session alert untuk error
    $_SESSION['alert'] = [
        'type' => 'error',
        'message' => 'Gagal menyimpan data: ' . mysqli_error($koneksi)
    ];
}

// Setelah berhasil simpan
$_SESSION['alert_type'] = 'success';
$_SESSION['alert_message'] = 'Data santri berhasil ditambahkan!';
header("Location: admin_santri.php?status=success");
exit;

// Tutup koneksi
mysqli_close($koneksi);

// Redirect kembali
header("Location: admin_santri.php");
exit;
?>

