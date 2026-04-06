<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"] != "login"){
    header("Location: login.php");
    exit;
}

// 2. Hubungkan ke Database
include 'koneksi.php';

// 3. Ambil data pengaturan saat ini dari database (Cuma 1 baris)
$query_atur = mysqli_query($koneksi, "SELECT * FROM pengaturan LIMIT 1");
$data_atur = mysqli_fetch_assoc($query_atur);
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jadwal & Lokasi - MSANTRI</title>
    
    <!-- IMPORT GOOGLE FONTS: POPPINS & AMIRI -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link CSS dan FontAwesome Baru -->
    <link rel="stylesheet" href="../style/admin_jadwal.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  </head>
  <body>
    <!-- SIDEBAR BARU -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="../../images/logo.png" alt="Logo" width="40px" />
        <p>MSANTRI</p>
        <!-- Tombol Close Sidebar (Khusus HP) -->
        <button class="btn-close-sidebar" onclick="toggleSidebar()">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="sidebar-menu">
        <p>Menu Utama</p>
        <a href="admin.php" class="ini-nav">
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
        <a href="admin_jadwal.php" class="ini-nav active">
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

    <!-- KONTEN UTAMA -->
    <main class="main-content">
      <!-- Tombol Hamburger (Khusus HP) -->
      <button class="btn-hamburger" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="header-content">
        <h1>Jadwal & Lokasi TPQ</h1>
        <p>Atur waktu kegiatan belajar mengajar dan perbarui titik lokasi Google Maps.</p>
      </div>

      <div class="dashboard">
        <div class="baris-flex">
          
          <!-- Kotak Kiri (Form Jadwal) -->
          <div class="card card-kiri">
            <div class="kepala-kotak">
              <h3 class="judul-kotak"><i class="fa-regular fa-clock" style="color: #10b981; margin-right: 10px;"></i> Waktu Pengajian</h3>
            </div>
            
            <form action="simpan_jadwal.php" method="POST">
              <input type="hidden" name="jenis_form" value="jadwal">

              <div class="grid-form">
                <div class="grup-form">
                  <label>Senin - Kamis (Hari Kerja)</label>
                  <input type="text" name="seninkamis" value="<?= htmlspecialchars($data_atur['jadwal_seninkamis']) ?>" required />
                </div>
                
                <div class="grup-form">
                  <label>Jumat (Hari Istirahat)</label>
                  <input type="text" name="jumat" value="<?= htmlspecialchars($data_atur['jadwal_jumat']) ?>" style="color: #ef4444; font-weight: bold;" required />
                </div>
                
                <div class="grup-form">
                  <label>Sabtu (Akhir Pekan)</label>
                  <input type="text" name="sabtu" value="<?= htmlspecialchars($data_atur['jadwal_sabtu']) ?>" required />
                </div>
                
                <div class="grup-form">
                  <label>Minggu (Akhir Pekan)</label>
                  <input type="text" name="minggu" value="<?= htmlspecialchars($data_atur['jadwal_minggu']) ?>" required />
                </div>
              </div>
              
              <div class="footer-form">
                <button type="submit" class="tombol-simpan">
                  <i class="fa-solid fa-floppy-disk"></i> Simpan Jadwal
                </button>
              </div>
            </form>
          </div>

          <!-- Kotak Kanan (Form Lokasi Maps) -->
          <div class="card card-kanan">
            <div class="kepala-kotak">
              <h3 class="judul-kotak"><i class="fa-solid fa-map-location-dot" style="color: #10b981; margin-right: 10px;"></i> Peta Lokasi</h3>
            </div>
            
            <form action="simpan_jadwal.php" method="POST">
              <input type="hidden" name="jenis_form" value="peta">

              <div class="grup-form">
                <label>Link Embed (src) Google Maps</label>
                <p class="teks-bantuan">
                  *Salin link dari atribut <code>src="..."</code> pada kode embed Google Maps dan tempel di bawah ini.
                </p>
                <textarea name="link_maps" rows="3" required><?= htmlspecialchars($data_atur['link_maps']) ?></textarea>
              </div>

              <!-- Preview Peta langsung mengambil src dari database -->
              <div class="kotak-peta">
                <?php if(!empty($data_atur['link_maps'])): ?>
                  <iframe src="<?= htmlspecialchars($data_atur['link_maps']) ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php else: ?>
                  <p style="color: #94a3b8; font-size: 14px;">Belum ada peta yang disematkan.</p>
                <?php endif; ?>
              </div>
              
              <div class="footer-form">
                <button type="submit" class="tombol-simpan tombol-penuh">
                  <i class="fa-solid fa-map-pin"></i> Perbarui Peta
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </main>

    <script src="../script/admin-info.js"></script> 
  </body>
</html>