<?php
session_start();

// Hubungkan ke database
include 'koneksi.php';

// Cek apakah ada request POST dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Tangkap input hidden 'jenis_form' untuk tahu form mana yang disubmit
    $jenis_form = $_POST['jenis_form'];

    if ($jenis_form == 'jadwal') {
        // --- PROSES JIKA TOMBOL SIMPAN JADWAL DITEKAN ---
        $seninkamis = mysqli_real_escape_string($koneksi, $_POST['seninkamis']);
        $jumat      = mysqli_real_escape_string($koneksi, $_POST['jumat']);
        $sabtu      = mysqli_real_escape_string($koneksi, $_POST['sabtu']);
        $minggu     = mysqli_real_escape_string($koneksi, $_POST['minggu']);

        // Update khusus kolom jadwal saja
        $query = "UPDATE pengaturan SET 
                  jadwal_seninkamis = '$seninkamis',
                  jadwal_jumat = '$jumat',
                  jadwal_sabtu = '$sabtu',
                  jadwal_minggu = '$minggu'";
                  
        mysqli_query($koneksi, $query);

    } else if ($jenis_form == 'peta') {
        // --- PROSES JIKA TOMBOL PERBARUI PETA DITEKAN ---
        $link_maps = mysqli_real_escape_string($koneksi, $_POST['link_maps']);

        // Update khusus kolom peta saja
        $query = "UPDATE pengaturan SET link_maps = '$link_maps'";
        mysqli_query($koneksi, $query);
    }

    // Jika sudah selesai, kembalikan ke halaman Jadwal
    header("Location: admin_jadwal.php");
    exit;

} else {
    // Jika diakses manual lewat URL, lempar kembali
    header("Location: admin_jadwal.php");
    exit;
}
?>