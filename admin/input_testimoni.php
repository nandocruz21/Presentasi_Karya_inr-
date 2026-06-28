<?php
include '../config/koneksi.php';
session_start();
if(!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"]!="login"){
  header("Location:login.php");
  exit;
}
$query_testi = mysqli_query($koneksi, "SELECT * FROM testimoni ORDER BY id_testi DESC");
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Testimoni - MSANTRI</title>
    
    <!-- IMPORT GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../public/img/lg.jpeg">
    <link rel="stylesheet" href="../public/css/admin_info.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
  </head>
  <body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="../public/img/logo.png" alt="Logo" width="40px" />
        <p>MSANTRI</p>
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
        <a href="admin_jadwal.php" class="ini-nav">
          <i class="fa-regular fa-calendar-check"></i>
          <span>Jadwal &amp; Lokasi</span>
        </a>
        <a href="input_testimoni.php" class="ini-nav active">
          <i class="fa-solid fa-comments"></i>
          <span>Testimoni</span>
        </a>
      </div>

      <div class="sidebar-footer">
        <div class="profile-card">
          <div><img src="../public/img/profil.jpeg" alt="" width="40px" style="border-radius: 50%;"></div>
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
      <!-- MOBILE HEADER -->
      <div class="mobile-header">
          <button class="btn-hamburger" onclick="toggleSidebar()">
              <i class="fa-solid fa-bars"></i>
          </button>
          <div class="mobile-logo">
              <img src="../public/img/logo.png" alt="Logo">
              <span>MSANTRI</span>
          </div>
      </div>

      <div class="header-content">
        <h1>Kelola Testimoni</h1>
        <p>Tambahkan ulasan dari orang tua santri agar tampil di halaman depan.</p>
      </div>

      <div class="dashboard">
        <div class="baris-flex">
          
          <!-- Kotak Kiri (Form Input) -->
          <div class="card card-kiri">
            <div class="kepala-kotak">
              <h3 class="judul-kotak">Tambah Testimoni</h3>
            </div>

            <form action="proses_tambah_testimoni.php" method="POST">
              <div class="grup-form">
                <label>Nama Wali Santri</label>
                <input type="text" name="nama_wali" placeholder="Contoh: Bunda Aisyah" required />
              </div>

              <div class="grup-form">
                <label>Inisial Avatar</label>
                <input type="text" name="inisial" placeholder="Contoh: B" maxlength="2" required />
              </div>

              <div class="grup-form">
                <label>Rating (Bintang)</label>
                <select name="rating" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <option value="5">5 Bintang</option>
                    <option value="4">4 Bintang</option>
                    <option value="3">3 Bintang</option>
                </select>
              </div>

              <div class="grup-form" style="margin-top: 15px;">
                <label>Isi Ulasan</label>
                <textarea rows="4" name="isi_testimoni" placeholder="Tuliskan ulasan..." required></textarea>
              </div>

              <button type="submit" name="simpan" class="tombol-tambah">
                <i class="fa-solid fa-paper-plane"></i> Simpan Testimoni
              </button>
            </form>
          </div>

          <!-- Kotak Kanan (Daftar Testimoni) -->
          <div class="kolom-kanan">
            <div class="daftar-kartu">

              <?php if($query_testi && mysqli_num_rows($query_testi) > 0): ?>
                <?php while ($data = mysqli_fetch_assoc($query_testi)) : ?>
                  <div class="kartu-info" style="border-left: 4px solid #10b981;">
                    <div class="tanggal-posting">
                      <i class="fa-solid fa-star" style="color: #fbbf24; margin-right: 5px"></i>
                      Rating: <?= $data['rating'] ?>
                    </div>

                    <div class="aksi-pojok">
                      <a href="hapus_testimoni.php?id=<?= $data['id_testi'] ?>" class="btn-aksi btn-hapus" title="Hapus" onclick="return confirm('Yakin ingin menghapus testimoni ini?')" style="display: flex; text-decoration: none;">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </div>

                    <div class="ikon-kartu" style="background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem;"><?= htmlspecialchars(strtoupper($data['inisial'])) ?></div>
                    <p class="teks-kategori">Wali Santri</p>
                    <h4 class="teks-judul">
                      <?= htmlspecialchars($data['nama_wali']) ?>
                    </h4>
                    <p class="teks-deskripsi">
                      "<?= nl2br(htmlspecialchars($data['isi_testimoni'])) ?>"
                    </p>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>  
                <div class="kartu-info" style="border-color: #e2e8f0; background: #ffffff; box-shadow: none;">
                  <p class="teks-deskripsi" style="text-align: center; color: #94a3b8;">Belum ada testimoni.</p>
                </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </main>

    <script src="../public/js/admin-info.js"></script>
  </body>
</html>
