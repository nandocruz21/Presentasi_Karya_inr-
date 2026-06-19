<?php
// Hubungkan ke database
include '../config/koneksi.php';

// Panggil file kurir WA yang ada di folder backend
include 'kirim_wa.php'; 

// Cek apakah ada data 'id' dan 'status' yang dikirim oleh kurir JavaScript
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id_santri = mysqli_real_escape_string($koneksi, $_POST['id']);
    $status_baru = mysqli_real_escape_string($koneksi, $_POST['status']);

    // 1. UPDATE TABEL UTAMA SANTRI
    $query_update = "UPDATE santri SET kehadiran = '$status_baru' WHERE id_santri = '$id_santri'";
    mysqli_query($koneksi, $query_update);
    
    // 2. AMBIL DATA UNTUK RIWAYAT DAN WHATSAPP SEKALIGUS
    $query_data = mysqli_query($koneksi, "SELECT nama_lengkap, no_wa_ortu, capaian_hafalan, catatan_pengajar FROM santri WHERE id_santri = '$id_santri'");
    $dt = mysqli_fetch_assoc($query_data);
    
    // Siapkan variabel
    $nama_anak = $dt['nama_lengkap'];
    $no_hp_tujuan = $dt['no_wa_ortu'];
    $capaian = mysqli_real_escape_string($koneksi, $dt['capaian_hafalan']);
    $catatan = mysqli_real_escape_string($koneksi, $dt['catatan_pengajar']);

    // 3. REKAM KE TABEL RIWAYAT (Untuk Timeline)
    $query_riwayat = "INSERT INTO riwayat_progres (id_santri, capaian_hafalan, catatan_pengajar, kehadiran) 
                      VALUES ('$id_santri', '$capaian', '$catatan', '$status_baru')";
    
    if (mysqli_query($koneksi, $query_riwayat)) {
        
        // 4. --- TAMBAHAN UNTUK WHATSAPP ---
        // Jika status yang dipilih adalah "hadir", maka kirim pesan WA
        if ($status_baru == 'hadir') {
            
            // Pastikan nomor WA tidak kosong dan bukan tanda strip "-"
            if (!empty($no_hp_tujuan) && $no_hp_tujuan != '-') {
                
                // Susun isi pesan WhatsApp
                $pesan = "Assalamu'alaikum Ayah/Bunda.\n\nAlhamdulillah, ananda *{$nama_anak}* telah tercatat *HADIR* di TPQ pada hari ini.\n\nSemoga ananda mendapatkan ilmu yang bermanfaat. Aamiin.\n\n_Berikanlah apresiasi kepada anak anda walaupun hanya dengan kalimat sederhana tetapi sangat berarti baginya ;)_";
                
                // Eksekusi pengiriman WA dengan memanggil fungsi dari kirim_wa.php
                if (function_exists('kirimWhatsApp')) {
                    @kirimWhatsApp($no_hp_tujuan, $pesan);
                }
            }
        }
        // -------------------------------

        echo "Berhasil diupdate";
    } else {
        echo "Gagal Riwayat: " . mysqli_error($koneksi);
    }
} else {
    echo "Data tidak lengkap";
}
?>
