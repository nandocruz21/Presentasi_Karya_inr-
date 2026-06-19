<?php
session_start();

// Hubungkan ke database
include '../config/koneksi.php';

// ========== PROSES TANGGAL LAHIR ==========
$tanggal_lahir_raw = isset($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : '';
if (empty($tanggal_lahir_raw)) {
    $tanggal_lahir = "NULL";
} else {
    $tanggal_lahir = "'" . mysqli_real_escape_string($koneksi, $tanggal_lahir_raw) . "'";
}

// ========== AMBIL DATA DARI FORM ==========
$id      = isset($_POST['id_santri']) ? $_POST['id_santri'] : '';
$nama    = mysqli_real_escape_string($koneksi, $_POST['nama'] ?? '');
$capaian = mysqli_real_escape_string($koneksi, $_POST['capaian'] ?? '');
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');

$tempat_lahir  = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir'] ?? '');
$alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? '');
$nama_ortu     = mysqli_real_escape_string($koneksi, $_POST['nama_ortu'] ?? '');
$no_wa_ortu    = mysqli_real_escape_string($koneksi, $_POST['no_wa_ortu'] ?? '');

// Set default value untuk field kosong
if (empty($tempat_lahir)) $tempat_lahir = "-";
if (empty($alamat)) $alamat = "-";
if (empty($nama_ortu)) $nama_ortu = "-";
if (empty($no_wa_ortu)) $no_wa_ortu = "-"; 
if (empty($catatan)) $catatan = "- Belum ada catatan -";

// ========== KONFIGURASI UPLOAD FOTO ==========
$nama_file_foto = ""; 
$folder_upload = "../public/uploads/"; // Mengarah ke /public/uploads/

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_nama_asli = $_FILES['foto']['name'];
    $foto_size = $_FILES['foto']['size'];
    
    $ekstensi_diperbolehkan = array('png','jpg','jpeg');
    $x = explode('.', $foto_nama_asli);
    $ekstensi = strtolower(end($x));
    
    $nama_file_baru = "santri_" . time() . "_" . rand(100,999) . "." . $ekstensi;

    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        if($foto_size < 2048000){ 
            move_uploaded_file($foto_tmp, $folder_upload . $nama_file_baru);
            $nama_file_foto = $nama_file_baru;
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = "Ukuran file terlalu besar (Maks 2MB).";
            header("Location: ../admin/admin_santri.php?status=error");
            exit;
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = "Ekstensi file tidak diperbolehkan (Hanya JPG/PNG).";
        header("Location: ../admin/admin_santri.php?status=error");
        exit;
    }
}

// ========== JALANKAN QUERY UTAMA ==========
if ($id == "") {
    // INSERT DATA BARU
    if(empty($nama_file_foto)){ $nama_file_foto = 'default.png'; }

    $query = "INSERT INTO santri (nama_lengkap, tempat_lahir, tanggal_lahir, alamat, nama_ortu, no_wa_ortu, capaian_hafalan, catatan_pengajar, kehadiran, foto) 
              VALUES ('$nama', '$tempat_lahir', $tanggal_lahir, '$alamat', '$nama_ortu', '$no_wa_ortu', '$capaian', '$catatan', 'hadir', '$nama_file_foto')";
} else {
    // UPDATE DATA LAMA
    if(!empty($nama_file_foto)){
        $query = "UPDATE santri SET 
                  nama_lengkap = '$nama', tempat_lahir = '$tempat_lahir', tanggal_lahir = $tanggal_lahir, alamat = '$alamat', nama_ortu = '$nama_ortu', no_wa_ortu = '$no_wa_ortu', capaian_hafalan = '$capaian', catatan_pengajar = '$catatan', foto = '$nama_file_foto' WHERE id_santri = '$id'";
    } else {
        $query = "UPDATE santri SET 
                  nama_lengkap = '$nama', tempat_lahir = '$tempat_lahir', tanggal_lahir = $tanggal_lahir, alamat = '$alamat', nama_ortu = '$nama_ortu', no_wa_ortu = '$no_wa_ortu', capaian_hafalan = '$capaian', catatan_pengajar = '$catatan' WHERE id_santri = '$id'";
    }
}

// ========== EKSEKUSI SEMUA PROSES ==========
if (mysqli_query($koneksi, $query)) {
    
    // --- 1. PROSES REKAM RIWAYAT (TIMELINE) ---
    if ($id == "") {
        $id_log = mysqli_insert_id($koneksi);
        $query_riwayat = "INSERT INTO riwayat_progres (id_santri, capaian_hafalan, catatan_pengajar, kehadiran) 
                          VALUES ('$id_log', '$capaian', '$catatan', 'hadir')";
        mysqli_query($koneksi, $query_riwayat);
    }

    // SELESAI!
    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = ($id == "") ? 'Data santri berhasil ditambahkan!' : 'Data santri berhasil diperbarui!';
    
    header("Location: ../admin/admin_santri.php?status=success");
    exit;
} else {
    $_SESSION['alert_type'] = 'error';
    $_SESSION['alert_message'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
    header("Location: ../admin/admin_santri.php?status=error");
    exit;
}

mysqli_close($koneksi);
?>
