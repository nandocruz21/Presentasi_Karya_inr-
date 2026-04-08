<?php
session_start(); // Tambahkan session_start() di awal

include 'koneksi.php';

// ========== PERBAIKAN UTAMA DARI KAMU ==========
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
$no_wa_ortu    = mysqli_real_escape_string($koneksi, $_POST['no_wa_ortu'] ?? '');

// Set default value untuk field yang kosong agar rapi di tampilan
if (empty($tempat_lahir)) $tempat_lahir = "-";
if (empty($alamat)) $alamat = "-";
if (empty($nama_ortu)) $nama_ortu = "-";
if (empty($no_wa_ortu)) $no_wa_ortu = "-"; // Beri strip jika WA tidak diisi
if (empty($catatan)) $catatan = "- Belum ada catatan -";

// ========== KONFIGURASI UPLOAD FOTO ==========
$nama_file_foto = ""; 
$folder_upload = "../../uploads/"; // SESUAIKAN PATH INI DENGAN FOLDERMU

// Cek apakah ada file foto yang diunggah
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto_tmp = $_FILES['foto']['tmp_name'];
    $foto_nama_asli = $_FILES['foto']['name'];
    $foto_size = $_FILES['foto']['size'];
    
    // Ambil ekstensi file
    $ekstensi_diperbolehkan = array('png','jpg','jpeg');
    $x = explode('.', $foto_nama_asli);
    $ekstensi = strtolower(end($x));
    
    // Ganti nama file agar unik (menghindari nama kembar)
    $nama_file_baru = "santri_" . time() . "_" . rand(100,999) . "." . $ekstensi;

    if(in_array($ekstensi, $ekstensi_diperbolehkan) === true){
        if($foto_size < 2048000){ // Maksimal 2MB
            // Pindahkan file ke folder uploads
            move_uploaded_file($foto_tmp, $folder_upload . $nama_file_baru);
            $nama_file_foto = $nama_file_baru;
        } else {
            $_SESSION['alert_type'] = 'error';
            $_SESSION['alert_message'] = "Ukuran file terlalu besar (Maks 2MB).";
            header("Location: admin_santri.php?status=error");
            exit;
        }
    } else {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = "Ekstensi file tidak diperbolehkan (Hanya JPG/PNG).";
        header("Location: admin_santri.php?status=error");
        exit;
    }
}

// ========== JALANKAN QUERY ==========
if ($id == "") {
    // Mode INSERT (Tambah Baru)
    if(empty($nama_file_foto)){
        $nama_file_foto = 'default.png'; // Foto default jika tidak diupload
    }

    $query = "INSERT INTO santri (nama_lengkap, tempat_lahir, tanggal_lahir, alamat, nama_ortu, no_wa_ortu, capaian_hafalan, catatan_pengajar, kehadiran, foto) 
              VALUES ('$nama', '$tempat_lahir', $tanggal_lahir, '$alamat', '$nama_ortu', '$no_wa_ortu', '$capaian', '$catatan', 'hadir', '$nama_file_foto')";
} else {
    // Mode UPDATE (Edit)
    if(!empty($nama_file_foto)){
        // Jika upload foto baru, update kolom foto juga
        $query = "UPDATE santri SET 
                  nama_lengkap = '$nama', 
                  tempat_lahir = '$tempat_lahir',
                  tanggal_lahir = $tanggal_lahir,
                  alamat = '$alamat',
                  nama_ortu = '$nama_ortu',
                  no_wa_ortu = '$no_wa_ortu', 
                  capaian_hafalan = '$capaian', 
                  catatan_pengajar = '$catatan',
                  foto = '$nama_file_foto'
                  WHERE id_santri = '$id'";
    } else {
        // Jika tidak upload foto, abaikan update kolom foto
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
}

// ========== EKSEKUSI QUERY ==========
if (mysqli_query($koneksi, $query)) {
    // Setelah berhasil simpan
    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = ($id == "") ? 'Data santri berhasil ditambahkan!' : 'Data santri berhasil diperbarui!';
    
    // Redirect kembali ke halaman admin
    header("Location: admin_santri.php?status=success");
    exit;
} else {
    // Set session alert untuk error dan redirect
    $_SESSION['alert_type'] = 'error';
    $_SESSION['alert_message'] = "Gagal menyimpan data: " . mysqli_error($koneksi);
    header("Location: admin_santri.php?status=error");
    exit;
}

// Tutup koneksi
mysqli_close($koneksi);
?>