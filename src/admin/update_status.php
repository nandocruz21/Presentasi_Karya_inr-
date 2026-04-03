<?php
// Hubungkan ke database
include 'koneksi.php';

// Panggil file kurir WA yang ada di Canvas
include '../pages/kirim_wa.php'; 

// Cek apakah ada data 'id' dan 'status' yang dikirim oleh kurir JavaScript
if (isset($_POST['id']) && isset($_POST['status'])) {
    $id_santri = $_POST['id'];
    $status_baru = $_POST['status'];

    // Lakukan proses UPDATE ke tabel santri
    $query = "UPDATE santri SET kehadiran = '$status_baru' WHERE id_santri = '$id_santri'";
    
    if (mysqli_query($koneksi, $query)) {
        
        // --- TAMBAHAN UNTUK WHATSAPP ---
        // Jika status yang dipilih adalah "hadir", maka kirim pesan WA
        if ($status_baru == 'hadir') {
            
            // Ambil nama santri dan nomor WA orang tuanya dari database
            $query_santri = mysqli_query($koneksi, "SELECT nama_lengkap, no_wa_ortu FROM santri WHERE id_santri = '$id_santri'");
            $data_santri = mysqli_fetch_assoc($query_santri);
            
            $nama_anak = $data_santri['nama_lengkap'];
            $no_hp_tujuan = $data_santri['no_wa_ortu'];
            
            // Pastikan nomor WA tidak kosong dan bukan tanda strip "-"
            if (!empty($no_hp_tujuan) && $no_hp_tujuan != '-') {
                
                // Susun isi pesan WhatsApp
                $pesan = "Assalamu'alaikum Ayah/Bunda.\n\nAlhamdulillah, ananda *{$nama_anak}* telah tercatat *HADIR* di TPQ pada hari ini.\n\nSemoga ananda mendapatkan ilmu yang bermanfaat. Aamiin.\n\n_Berikanlah apresiasi kepada anak anda walaupun hanya dengan kalimat sederhana tetapi sangat berarti baginya ;)_";
                
                // Eksekusi pengiriman WA dengan memanggil fungsi dari kirim_wa.php
                kirimWhatsApp($no_hp_tujuan, $pesan);
            }
        }
        // -------------------------------

        echo "Berhasil diupdate";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
} else {
    echo "Data tidak lengkap";
}
?>