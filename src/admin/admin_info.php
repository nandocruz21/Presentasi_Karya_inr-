<?php
include 'koneksi.php';
session_start();
if(!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"]!="login"){
  header("Location:login.php");
  exit;
}
$query_info = mysqli_query($koneksi, "SELECT * FROM informasi ORDER BY tanggal_posting DESC, id_info DESC");
?>

<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Informasi - MSANTRI</title>
    
    <!-- IMPORT GOOGLE FONTS: POPPINS & AMIRI -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../style/admin_info.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
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
        <!-- Class active dipindah ke halaman Informasi -->
        <a href="admin_info.php" class="ini-nav active">
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
          <div class="profile-img">AD</div>
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
        <h1>Papan Informasi</h1>
        <p>Buat pengumuman baru dan lihat pratinjaunya tampil di halaman utama.</p>
      </div>

      <div class="dashboard">
        <div class="baris-flex">
          
          <!-- Kotak Kiri (Form Input) -->
          <div class="card card-kiri">
            <div class="kepala-kotak">
              <h3 class="judul-kotak">Posting Baru</h3>
            </div>

            <form action="simpan_info.php" method="POST">
              <input type="hidden" name="id_info" id="inputIdInfo">

              <div class="grup-form">
                <label>Judul Informasi</label>
                <input
                  type="text"
                  name="judul"
                  id="inputJudul"
                  placeholder="Contoh: Libur Sampai Tanggal..."
                  required
                />
              </div>

              <div class="grup-form">
                <label>Isi Pesan / Keterangan</label>
                <textarea
                  rows="4"
                  name="isi"
                  id="inputIsi"
                  placeholder="Tetap membaca dan menghafal di rumah..."
                  required
                ></textarea>
              </div>

              <button type="submit" class="tombol-tambah" id="btnSimpan">
                <i class="fa-solid fa-paper-plane"></i> Terbitkan Sekarang
              </button>
              
              <button type="button" class="tombol-tambah" id="btnBatalEdit" style="display:none; background: #94a3b8; margin-top: 10px;" onclick="batalEdit()">
                <i class="fa-solid fa-xmark"></i> Batal Edit
              </button>
            </form>
          </div>

          <!-- Kotak Kanan (Daftar Pengumuman) -->
          <div class="kolom-kanan">
            <div class="daftar-kartu">

              <?php if(mysqli_num_rows($query_info) > 0): ?>
                <?php while ($data = mysqli_fetch_assoc($query_info)) : ?>
                  <div class="kartu-info">
                    <div class="tanggal-posting">
                      <i class="fa-regular fa-calendar" style="margin-right: 5px"></i>
                      <?= date('d M Y ', strtotime($data['tanggal_posting'])); ?>
                    </div>

                    <div class="aksi-pojok">
                      <button class="btn-aksi btn-edit" title="Edit Info" onclick="editInfo('<?=$data['id_info']?>', '<?= addslashes($data['judul_info']) ?>', '<?= addslashes($data['isi_info']) ?>')">
                        <i class="fa-solid fa-pen"></i>
                      </button>
                      <a href="hapus_info.php?id=<?= $data['id_info'] ?>" class="btn-aksi btn-hapus" title="Hapus Info" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')" style="display: flex; text-decoration: none;">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </div>

                    <!-- Isi Kartu -->
                    <div class="ikon-kartu"><i class="fa-solid fa-bullhorn"></i></div>
                    <p class="teks-kategori"><?= htmlspecialchars($data['kategori'] ?? 'PENGUMUMAN') ?> </p>
                    <h4 class="teks-judul">
                      <?= htmlspecialchars($data['judul_info']) ?>
                    </h4>
                    <p class="teks-deskripsi">
                      <?= nl2br(htmlspecialchars($data['isi_info'])) ?>
                    </p>
                  </div>
                <?php endwhile; ?>
              <?php else: ?>  
                <div class="kartu-info" style="border-color: #e2e8f0; background: #ffffff; box-shadow: none;">
                  <p class="teks-deskripsi" style="text-align: center; color: #94a3b8;">Belum ada pengumuman yang diterbitkan.</p>
                </div>
              <?php endif; ?>

            </div>
          </div>

        </div>
      </div>
    </main>

    <script src="../script/admin-info.js"></script>
  </body>
</html>