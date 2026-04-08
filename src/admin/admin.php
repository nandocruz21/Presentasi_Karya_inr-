<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"] != "login"){
    header("Location: login.php");
    exit;
}

// AMBIL DATA TOTAL SANTRI (Untuk di kotak Dashboard)
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM santri");
$data_total = mysqli_fetch_assoc($query_total);
$total_santri = $data_total['total'];

// LOGIKA CCTV PUSAT (Riwayat 3 Aktifitas Terakhir)
// Menggunakan teknik SQL UNION untuk menggabungkan 3 tabel
$query_aktifitas = "
    SELECT waktu_update, CONCAT('Pembaruan data santri atas nama <strong>', nama_lengkap, '</strong>.') AS teks
    FROM santri WHERE waktu_update IS NOT NULL
    
    UNION
    
    SELECT waktu_update, CONCAT('Pembaruan papan informasi: <strong>', judul_info, '</strong>.') AS teks
    FROM informasi WHERE waktu_update IS NOT NULL
    
    UNION
    
    SELECT waktu_update, 'Pembaruan data <strong>Jadwal & Lokasi (Peta) TPQ</strong>.' AS teks
    FROM pengaturan WHERE waktu_update IS NOT NULL

    UNION 
    SELECT waktu_update, 'Penambahan / pembaruan <strong>gambar</strong>.' AS teks
    FROM galeri WHERE waktu_update IS NOT NULL
    
    ORDER BY waktu_update DESC
    LIMIT 3
";
$result_aktifitas = mysqli_query($koneksi, $query_aktifitas);
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MSANTRI Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../images/lg.jpeg">

    <link rel="stylesheet" href="../style/admin.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
  <body>
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="../../images/logo.png" alt="Logo" width="40px"/>
        <p>MSANTRI</p>

        <button class="btn-close-sidebar" onclick="toggleSidebar()">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="sidebar-menu">
        <p>Menu Utama</p>
        <a href="admin.php" class="ini-nav active">
          <i class="fa-solid fa-border-all"></i>
          <span>Dashboard</span>
        </a>
        <a href="admin_santri.php" class="ini-nav">
          <i class="fa-solid fa-book-open-reader"></i>
          <span>Progres Santri</span>
        </a>
        <a href="admin_info.php" class="ini-nav">
          <i class="fa-solid fa-bullhorn"></i>
          <span>Informasi</span>
        </a>
        <a href="admin_galeri.php" class="ini-nav">
          <i class="fa-regular fa-image"></i>
          <span>Galeri</span>
        </a>
        <a href="admin_jadwal.php" class="ini-nav">
          <i class="fa-regular fa-calendar-check"></i>
          <span>Jadwal & Lokasi</span>
        </a>
      </div>

      <div class="sidebar-footer">
        <div class="profile-card">
          <div><img src="../../images/profil.jpeg" alt="" width="40px" style="border-radius: 50%;"></div>
          <div class="profile-info">
            <h4>Administrator</h4>
          </div>
          <a href="logout.php" style="color: inherit; text-decoration: none;">
            <i class="fa-solid fa-power-off btn-logout" title="Logout"></i>
          </a>
        </div>
      </div>
    </aside>

    <main class="main-content">
      <button class="btn-hamburger" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
       </button>
      <div class="header-content">
        <h1>Ikhtisar TPQ</h1>
        <p>Ringkasan cepat data operasional Miftahul Jannah hari ini.</p>
      </div>

      <div class="dashboard">
        <!-- Hero Card -->
        <div class="card hero-card">
          <h2>Ahlan Wa Sahlan, Admin!</h2>
          <p>
            Pantau terus perkembangan hafalan santri agar orang tua di rumah
            bisa melihat progres anak-anaknya secara langsung melalui website.
          </p>
          <div>
            <a href="../pages/index.php" class="btn-primary">
              <i class="fa-solid fa-globe" style="margin-right: 8px"></i> Lihat Web Publik
            </a>
          </div>
        </div>

        <!-- Stats Card -->
        <div class="card stat-card">
          <div class="stat-icon">
            <i class="fa-solid fa-users"></i>
          </div>
          <div class="stat-value"><?= $total_santri; ?></div>
          <div class="stat-label">Total Santri</div>
        </div>

        <div class="card activity-section" style="grid-column: 1 / -1;">
          <h3 style="margin-bottom: 1.5rem; color: #1e293b; font-size: 30px;">
            Riwayat Aktifitas
          </h3>

          <?php if(mysqli_num_rows($result_aktifitas) > 0): ?>
            
            <?php while($row = mysqli_fetch_assoc($result_aktifitas)): ?>
              <div class="activity-item">
                <div class="activity-date">
                  <?= date('d M Y, H:i', strtotime($row['waktu_update'])); ?> WIB
                </div>
                <div class="activity-text">
                  <?= $row['teks']; ?>
                </div>
              </div>
            <?php endwhile; ?>

          <?php else: ?>
            <div class="activity-item" style="border: none">
              <div class="activity-text" style="color: #94a3b8;">
                Belum ada aktifitas yang tercatat di sistem.
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </main>

    <script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
  }

  // Fungsi agar sidebar otomatis menghilang setelah menu (navigasi) diklik di HP
  document.querySelectorAll('.ini-nav').forEach(link => {
    link.addEventListener('click', () => {
      // Hanya jalankan jika lebar layar ukuran HP (<= 768px)
      if (window.innerWidth <= 768) {
        document.querySelector('.sidebar').classList.remove('show');
      }
    });
  });

  // Menutup sidebar jika mengklik di luar area sidebar
document.addEventListener('click', function(event) {
  const sidebar = document.querySelector('.sidebar');
  const hamburger = document.querySelector('.btn-hamburger');

  // Mengecek apakah klik terjadi BUKAN di dalam sidebar dan BUKAN di tombol hamburger
  if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
    // Jika sidebar sedang memiliki class 'show' (sedang terbuka), maka tutup
    if (sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
    }
  }
});
    </script>
  </body>
</html>