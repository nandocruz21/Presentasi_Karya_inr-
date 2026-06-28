<?php
include '../config/koneksi.php';

if(isset($_POST['kirim_ulasan'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_wali']);
    $kelas = 'Wali Santri'; // Default string karena kolom kelas dihilangkan dari form
    
    // Ambil inisial dari huruf pertama nama
    $inisial = strtoupper(substr(trim($nama), 0, 1));
    
    $rating = (int) $_POST['rating'];
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi_testimoni']);

    $query = "INSERT INTO testimoni (nama_wali, kelas_santri, inisial, rating, isi_testimoni) 
              VALUES ('$nama', '$kelas', '$inisial', '$rating', '$isi')";

    if(mysqli_query($koneksi, $query)){
        echo "<script>alert('Terima kasih! Ulasan Anda berhasil dikirim dan ditambahkan.'); window.location='../frontend/index.php#testimoni';</script>";
    } else {
        echo "<script>alert('Mohon maaf, sistem gagal menyimpan ulasan Anda. Silakan coba lagi.'); window.history.back();</script>";
    }
} else {
    header("Location: ../frontend/index.php");
}
?>
