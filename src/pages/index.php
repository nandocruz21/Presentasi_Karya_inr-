<?php
include '../admin/koneksi.php';

// Ambil Total Santri Aktif
$q_santri = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM santri");
$dt_santri = mysqli_fetch_assoc($q_santri);
$total_santri = $dt_santri['total'];

// Ambil Pengumuman Terbaru (1 Paling Atas)
$q_info = mysqli_query($koneksi, "SELECT * FROM informasi ORDER BY tanggal_posting DESC, id_info DESC LIMIT 1");
$dt_info = mysqli_fetch_assoc($q_info);

// Ambil Pengaturan Jadwal, Slogan & Peta
$q_atur = mysqli_query($koneksi, "SELECT * FROM pengaturan LIMIT 1");
$dt_atur = mysqli_fetch_assoc($q_atur);

// --- BAGIAN BARU: Ambil Data Galeri dari Database ---
$q_galeri = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id_galeri DESC");
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MSANTRI - TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?></title>

    <!-- Ikon & Font -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../style/style.css" />
  </head>
  <body>
    <!-- NAVIGASI & HERO -->
    <section id="beranda">
      <header>
        <nav>
          <div class="logo">
            <img src="../../images/logo.png" alt="logo" width="50px">
            MSANTRI
          </div>
          
          <button class="btn-hamburger-user" onclick="toggleNav()">
            <i class="fa-solid fa-bars"></i>
          </button>

          <!-- Daftar Menu Navigasi -->
          <ul id="navMenu">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#sambutan">Sambutan</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#galeri">Galeri</a></li>
            <li><a href="#informasi">Informasi</a></li>
            <li><a href="#jadwal">Jadwal</a></li>
          </ul>
        </nav>
      </header>

      <div class="hero">
        <span class="hero-subtitle"><?= htmlspecialchars($dt_atur['slogan']) ?></span>
        <h1>
          Tempat Pengajian Qur'an<br />
          <span class="highlight"><?= htmlspecialchars($dt_atur['nama_tpq']) ?></span>
        </h1>
        <p>
          Saat ini terdapat <strong><?= $total_santri ?></strong> Santri yang terdaftar pada
          tempat pengajian ini.
        </p>
        <div class="cta">
          <a href="databasekdr.php" class="btn-primary">Cek Progres Santri</a>
          <a href="https://wa.link/0jox26" class="btn-outline">Daftar</a>
        </div>
      </div>
    </section>

    <!-- TEMPAT SAMBUTAN -->
    <section class="sambutan" id="sambutan">
      <div class="container-sambutan">
        <div class="sambutan-main">
          <h1>SAMBUTAN KEPALA TPQ</h1>
          <p class="sambutan-sbt">TPQ <?=htmlspecialchars($dt_atur['nama_tpq'])?></p>

          <div class="text-sambutan">
           <img src="../../images/profil.jpeg" alt="demn" class="foto-kepala">
            <p><strong>Assalamu'alaikum Warahmatullahi Wabarakatuh</strong></p>
              <p>Segala puji bagi Allah SWT, Tuhan semesta alam. Shalawat dan salam semoga senantiasa menyertai Rasulullah Muhammad SAW, keluarga, serta para sahabatnya.</p>
              <p>Ayah dan Bunda yang kami hormati, mendidik anak-anak menjadi generasi pecinta Al-Qur'an adalah cita-cita kita bersama. Oleh karena itu, TPQ <?= htmlspecialchars($dt_atur['nama_tpq'])?> terus berkomitmen memberikan dedikasi terbaik untuk membimbing putra-putri tercinta.</p>
              

              <p>Di era digital ini, kami sangat antusias menghadirkan website resmi TPQ Miftahul Jannah. Kami memahami betapa pentingnya peran orang tua dalam memantau perkembangan belajar anak. Melalui layanan ini, Ayah dan Bunda kini dapat dengan mudah melihat catatan hafalan dan kehadiran Ananda secara real-time dari mana saja.</p>
              
              <p>Kami berharap fasilitas ini dapat menjadi jembatan komunikasi yang hangat antara TPQ dan rumah. Mari bersama-sama kita wujudkan generasi masa depan yang cerdas dengan cahaya Al-Qur'an. Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
          </div>
        </div>
    
        <div class="sambutan-sidebar">
              <h3>TENTANG KAMI</h3>
              <ul>
                <li><a href="#sambutan"><i class="fa-solid fa-angle-right"></i> Sambutan Ketua</a></li>
                <li><a href="#tentang"><i class="fa-solid fa-angle-right"></i> Tentang</a></li>
                <li><a href="#jadwal"><i class="fa-solid fa-angle-right"></i> Waktu & Jadwal</a></li>
                <li><a href="#informasi"><i class="fa-solid fa-angle-right"></i> Papan Informasi</a></li>
                <li><a href="databasekdr.php"><i class="fa-solid fa-angle-right"></i> Cek Progres Santri</a></li>
              </ul>
        </div>
      </div>
    </section>

    <!-- SECTION TENTANG (GRID) -->
    <section class="kedua" id="tentang">
      <div class="utama1">
        <div class="container">
          <!-- Sisi Kiri: Deskripsi Utama -->
          <div class="grid-item utama">
            <h2 class="section-title">
              Membangun Generasi <br />
              Cinta Al-Quran
            </h2>
            <p class="section-description">
              Kami berdedikasi untuk menyediakan tempat pendidikan Al-Quran yang
              nyaman dan bersahabat bagi anak-anak hingga dewasa. Tujuan utama
              kami adalah menanamkan kecintaan membaca dan menghafal Al-Quran
              sejak dini.
            </p>
            <p class="section-description">
              Metode pembelajaran yang kami terapkan dirancang agar santri tidak
              merasa terbebani, melainkan rindu untuk terus datang mengaji.
            </p>
          </div>

          <!-- Sisi Kanan: Keunggulan -->
          <div class="grid-item grid-item-small">
            <h3>
              <div class="info-kedua">
                <i class="fa-solid fa-user-group"></i>
              </div>
              Pengajar Sabar
            </h3>
            <p>
              Dibimbing langsung oleh ustadz dan ustadzah yang telaten dalam
              mengajar ngaji.
            </p>
          </div>

          <div class="grid-item grid-item-small">
            <h3>
              <div class="info-kedua">
                <i class="fa-solid fa-book-open"></i>
              </div>
              Metode Terstruktur
            </h3>
            <p>
              Menggunakan panduan jilid yang jelas sehingga progres anak mudah
              dipantau.
            </p>
          </div>

          <div class="grid-item grid-item-small">
            <h3>
              <div class="info-kedua"><i class="fa-solid fa-star"></i></div>
              Fasilitas Nyaman
            </h3>
            <p>
              Ruang mengaji yang bersih, sirkulasi udara baik, dan lingkungan
              yang kondusif.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section class="galeri-siswa" id="galeri">
      <div class="judul-galeri">
        <h1>Dokumentasi Kegiatan</h1>
        <p>Potret momen indah para santri saat belajar dan menghafal.</p>
      </div>
      
      <div class="gambar-utama">
        <?php 
        // Melakukan perulangan untuk setiap data foto di database
        if (mysqli_num_rows($q_galeri) > 0) {
          while($dt_galeri = mysqli_fetch_assoc($q_galeri)) { 
        ?>
          <div class="gambarnya">
            <!-- Path gambar disesuaikan dengan folder upload admin Anda -->
            <img src="../../img/<?= $dt_galeri['nama_file'] ?>" alt="<?= htmlspecialchars($dt_galeri['keterangan']) ?>">
            <p><?= htmlspecialchars($dt_galeri['keterangan']) ?></p>
          </div>
        <?php 
          } 
        } else {
          echo "<p style='width:100%; color:#94a3b8;'>Belum ada foto dokumentasi.</p>";
        }
        ?>
      </div>
    </section>

    <!-- SECTION PAPAN INFORMASI -->
    <section class="ketiga" id="informasi">
      <div class="Utama2">
        <h1>Papan Informasi</h1>
        <p>Pengumuman dan informasi terbaru di TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?>.</p>
      </div>
      <div class="bungkus">
        
        <!-- Mengecek apakah ada pengumuman di database -->
        <?php if ($dt_info): ?>
          <div class="box"> 
            <div class="dalam1">
              <div class="info-pengumuman">
                <i class="fa-solid fa-bullhorn"></i>
              </div>
              <p class="category"><?= htmlspecialchars($dt_info['kategori']) ?></p>
              <h3><?= htmlspecialchars($dt_info['judul_info']) ?></h3>
            </div>
            <div class="dalam2">
              <p>
                <!-- nl2br agar enter (baris baru) dari admin tetap terbaca di HTML -->
                <?= nl2br(htmlspecialchars($dt_info['isi_info'])) ?>
              </p>
            </div>
            <div style="margin-top: 25px; font-size: 13px; color: #94a3b8; font-weight: 500;">
              <i class="fa-regular fa-calendar-check" style="margin-right: 5px;"></i> 
              Diposting pada: <?= date('d M Y', strtotime($dt_info['tanggal_posting'])) ?>
            </div>
          </div>
        <?php else: ?>
          <!-- Jika tabel pengumuman kosong -->
          <div class="box" style="border-color: #e2e8f0; box-shadow: none;">
            <div class="dalam1">
              <div class="info-pengumuman" style="background: #e2e8f0; color: #94a3b8; box-shadow: none;">
                <i class="fa-solid fa-inbox"></i>
              </div>
              <h3 style="color: #94a3b8; margin-top: 20px;">Belum Ada Informasi</h3>
            </div>
            <div class="dalam2">
              <p style="font-size: 16px; color: #94a3b8;">Saat ini belum ada pengumuman baru dari pengelola TPQ.</p>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </section>

    <!-- SECTION JADWAL (CARD FLEX) -->
    <section class="keempat" id="jadwal">
      <div class="Utama3">
        <h1>Waktu dan Jadwal Pengajian</h1>
        <p>
          Kegiatan belajar mengajar dilaksanakan secara rutin sesuai jadwal
          berikut.
        </p>
      </div>

      <!-- senin - kamis -->
      <div class="bungkus-jadwal">
        <div class="bungkus-jadwal-isi">
          <div class="box-jadwal1">
            <p>HARI MENGAJI</p>
            <h2>Senin - Kamis</h2>
          </div>
          <div class="box-jadwal2">
            Kegiatan pengajian rutin sore hari sepulang sekolah.
          </div>
          <div class="box-jadwal3">
            <span><i class="fa-regular fa-clock"></i> <?= htmlspecialchars($dt_atur['jadwal_seninkamis']) ?></span>
          </div>
        </div>

        <!-- jumat -->
        <div class="bungkus-jadwal-isi libur">
          <div class="box-jadwal1">
            <p>HARI ISTIRAHAT</p>
            <h2>Jumat</h2>
          </div>
          <div class="box-jadwal2">
            Kegiatan pengajian TPQ diliburkan. Santri dianjurkan murojaah
            mandiri.
          </div>
          <div class="box-jadwal3">
            <span><i class="fa-solid fa-door-closed"></i> <?= htmlspecialchars($dt_atur['jadwal_jumat']) ?></span>
          </div>
        </div>

        <!-- sabtu -->
        <div class="bungkus-jadwal-isi">
          <div class="box-jadwal1">
            <p>HARI MENGAJI</p>
            <h2>Sabtu</h2>
          </div>
          <div class="box-jadwal2">
            Kegiatan pengajian reguler berlanjut di waktu sore.
          </div>
          <div class="box-jadwal3">
            <span><i class="fa-regular fa-clock"></i> <?= htmlspecialchars($dt_atur['jadwal_sabtu']) ?></span>
          </div>
        </div>

        <!-- minggu -->
        <div class="bungkus-jadwal-isi">
          <div class="box-jadwal1">
            <p>HARI MENGAJI</p>
            <h2>Minggu</h2>
          </div>
          <div class="box-jadwal2">
            Pengajian sesi pagi hari agar santri bisa istirahat di siang
            harinya.
          </div>
          <div class="box-jadwal3">
            <span><i class="fa-regular fa-clock"></i> <?= htmlspecialchars($dt_atur['jadwal_minggu']) ?></span>
          </div>
        </div>
      </div>
    </section>

    <!-- SECTION LOKASI -->
    <section class="kelima">
      <div class="Utama4">
        <h1>LOKASI</h1>
      </div>
      <div class="map-wrapper">
        <iframe
          src="<?= htmlspecialchars($dt_atur['link_maps']) ?>"
          width="800"
          height="600"
          style="border: 0"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
        ></iframe>
      </div>
    </section>

    <!-- FOOTER MODERN BARU (KALLA STYLE) -->
    <footer class="footer-modern">
      <div class="footer-container">
        
        <!-- Kolom Kiri: Profil & Lokasi -->
        <div class="footer-col">
          <h2 class="footer-brand"><?= htmlspecialchars($dt_atur['nama_tpq']) ?></h2>
          <p class="footer-slogan">"<?= htmlspecialchars($dt_atur['slogan']) ?>"</p>
          <div class="footer-address">
            <h4>Alamat Lengkap</h4>
            <p>
              Jl. Sultan Hasanuddin, Kel. Letwaru, RT.009 <br />
              Kec. Kota Masohi, Kabupaten Maluku Tengah <br />
              Kode Pos 97511
            </p>
          </div>
        </div>

        <!-- Kolom Tengah: Tautan Cepat -->
        <div class="footer-col">
          <h4>Tautan Cepat</h4>
          <ul class="footer-links">
            <li><a href="#beranda">Beranda Utama</a></li>
            <li><a href="databasekdr.php">Cek Progres Santri</a></li>
            <li><a href="#jadwal">Jadwal Pengajian</a></li>
            <li><a href="#informasi">Papan Informasi</a></li>
          </ul>
        </div>

        <!-- Kolom Kanan: Sosmed & Kontak -->
        <div class="footer-col">
          <h4>Kontak & Informasi</h4>
          <p class="footer-contact-text">
            Hubungi kami via WhatsApp untuk pendaftaran santri baru atau jika ada pertanyaan seputar kegiatan TPQ.
          </p>
          <div class="footer-socials">
            <a href="https://wa.link/op9mny" title="WhatsApp" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/syahrularsydd._/" title="Instagram" target="_blank"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.facebook.com/jonk.ambonk" title="Facebook" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=syahruddin.arsyad21@gmail.com" title="Kirim via Gmail" target="_blank"><i class="fa-solid fa-envelope"></i></a>

          </div>
        </div>

      </div>

      <!-- Copyright Bar -->
      <div class="footer-bottom">
        <p>Copyright &copy; <?= date('Y') ?> TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?>. All Rights Reserved.</p>
      </div>
    </footer>

    <!-- Script Navbar Scroll -->
    <script src="../script/script.js"></script>

    <!-- SCRIPT HAMBURGER MENU -->
    <script>
      function toggleNav() {
        document.getElementById('navMenu').classList.toggle('show');
        // Jika header belum punya class scrolled tapi menu dibuka, beri background putih
        const header = document.querySelector('header');
        if(!header.classList.contains('scrolled')){
          header.style.backgroundColor = 'rgba(255, 255, 255, 1)';
        }
      }

      // Menutup menu drop-down ketika salah satu link diklik
      document.querySelectorAll('#navMenu a').forEach(link => {
        link.addEventListener('click', () => {
          document.getElementById('navMenu').classList.remove('show');
        });
      });
    </script>

    <!-- SCRIPT AUTO SCROLL GALERI (MELINGKAR TANPA JEDA) -->
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const galeriContainer = document.querySelector('.gambar-utama');

        // Pastikan galerinya ada dan memiliki isi foto
        if(galeriContainer && galeriContainer.children.length > 0) {
          
          // Override CSS bawaan yang bisa membuat scroll tersendat
          galeriContainer.style.scrollBehavior = 'auto';
          galeriContainer.style.scrollSnapType = 'none';
          
          // Menggandakan (duplikat) isi galeri dan memasukkannya ke container
          // Ini trik utama agar fotonya bisa terus menyambung ke foto pertama secara mulus
          const galeriIsiAwal = galeriContainer.innerHTML;
          galeriContainer.innerHTML += galeriIsiAwal; 
          
          let scrollSpeed = 1; // Kecepatan scroll (1 pixel per frame)
          let isHovered = false;
          let animationId;

          // Fungsi yang dipanggil terus-menerus oleh browser sehalus 60 frame per detik
          function autoScrollLoop() {
            if (!isHovered) {
              galeriContainer.scrollLeft += scrollSpeed;
              
              // Jika scroll sudah mencapai setengah dari lebar keseluruhan (yaitu panjang galeri awal),
              // reset posisinya kembali ke 0 dengan seketika. Karena gambarnya duplikat, tidak akan ada lompatan visual.
              if (galeriContainer.scrollLeft >= galeriContainer.scrollWidth / 2) {
                galeriContainer.scrollLeft = 0;
              }
            }
            // Ulangi animasi di frame berikutnya
            animationId = requestAnimationFrame(autoScrollLoop);
          }

          // Memulai animasi melingkar
          autoScrollLoop();

          // Memberhentikan scroll sementara jika pengunjung mengarahkan mouse (PC)
          galeriContainer.addEventListener('mouseenter', () => isHovered = true);
          galeriContainer.addEventListener('mouseleave', () => isHovered = false);

          // Memberhentikan scroll sementara jika pengunjung menahan/menyentuh layar (HP)
          galeriContainer.addEventListener('touchstart', () => isHovered = true);
          galeriContainer.addEventListener('touchend', () => isHovered = false);
        }
      });
    </script>
  </body>
</html>