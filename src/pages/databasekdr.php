<?php
include '../admin/koneksi.php';

// Ambil Semua Data Santri dari Database
$query_santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY nama_lengkap ASC");

// Ambil data pengaturan (untuk nama TPQ)
$query_atur = mysqli_query($koneksi, "SELECT nama_tpq FROM pengaturan LIMIT 1");
$dt_atur = mysqli_fetch_assoc($query_atur);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Progres Santri - MSANTRI</title>
    <link rel="stylesheet" href="../style/kdr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <!-- Header & Tombol Kembali -->
    <header>
      <div class="logo">      
      <img src="../../images/logo.png" alt="logo" width="50px">
      MSANTRI</div>
      <a href="index.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
    </header>

    <!-- Konten Utama -->
    <div class="search-container">
      <div class="search-header">
        <h1>Pencarian Data Santri</h1>
        <p>Ketik nama lengkap anak Anda untuk melihat catatan hafalan dan presensi hari ini.</p>
      </div>

      <!-- Kotak Input -->
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchInput" placeholder="Ketik nama santri (contoh: Ahmad)..." onkeyup="filterSantri()" autocomplete="off">
      </div>

      <!-- AREA KARTU HASIL -->
      <div class="results-area" id="resultsArea">
        
        <?php if (mysqli_num_rows($query_santri) > 0): ?>
          <?php while ($data = mysqli_fetch_assoc($query_santri)): ?>
            
            <?php 
              // Ambil inisial nama (huruf pertama)
              $inisial = strtoupper(substr($data['nama_lengkap'], 0, 1));
              $status = $data['kehadiran'];
              
              // (Pakai Array Mapping, tanpa if-else panjang)
              $konfigurasi_badge = [
                  'hadir' => ['teks' => 'HADIR HARI INI', 'ikon' => 'fa-check', 'kelas' => 'hadir'],
                  'izin'  => ['teks' => 'IZIN', 'ikon' => 'fa-envelope', 'kelas' => 'izin'],
                  'sakit' => ['teks' => 'SAKIT', 'ikon' => 'fa-bed-pulse', 'kelas' => 'izin'],
                  'alpha' => ['teks' => 'ALPHA (TIDAK HADIR)', 'ikon' => 'fa-xmark', 'kelas' => 'alpha']
              ];
              // Ambil data sesuai status, jika status tidak valid, default ke 'alpha'
              $badge = $konfigurasi_badge[$status] ?? $konfigurasi_badge['alpha'];

              // Variabel untuk Biodata (Cek jika null maka isi dengan strip '-')
              $tempat_lahir = !empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-';
              $tanggal_lahir = !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : '-';
              $ttl_gabung = $tempat_lahir . ', ' . $tanggal_lahir;
              $alamat = !empty($data['alamat']) ? $data['alamat'] : '-';
              $nama_ortu = !empty($data['nama_ortu']) ? $data['nama_ortu'] : '-';
            ?>

            <!-- Kartu Santri Dinamis (Ditambahkan onclick untuk Pop-up Biodata) -->
            <div class="student-card santri-item" style="display: none;" 
                 onclick="bukaBiodata(
                    '<?= htmlspecialchars($data['nama_lengkap'], ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($ttl_gabung, ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($alamat, ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($nama_ortu, ENT_QUOTES) ?>'
                 )">
                 
              <div class="student-info">
                
                <!-- Avatar yang class CSS-nya menyesuaikan status -->
                <div class="student-avatar avatar-<?= $status ?>"><?= $inisial ?></div>
                
                <div class="student-details">
                  <h3 class="santri-name"><?= htmlspecialchars($data['nama_lengkap']) ?></h3>
                  <p><i class="fa-solid fa-location-dot" style="color:#10B981;"></i> TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?></p>
                  
                  <!-- Menampilkan catatan guru jika ada -->
                  <?php if (!empty($data['catatan_pengajar']) && $data['catatan_pengajar'] != "- Belum ada catatan -"): ?>
                    <div class="catatan-guru catatan-<?= $status ?>">
                      <strong>Catatan Pengajar:</strong> <?= htmlspecialchars($data['catatan_pengajar']) ?>
                    </div>
                  <?php endif; ?>
                  
                </div>
              </div>
              
              <div class="student-progress">
                <div class="progress-label">Capaian Terakhir</div>
                <div class="progress-value"><?= htmlspecialchars($data['capaian_hafalan']) ?></div>
                <!-- Memanggil isi array tadi -->
                <span class="badge <?= $badge['kelas'] ?>"><i class="fa-solid <?= $badge['ikon'] ?>"></i> <?= $badge['teks'] ?></span>
              </div>
            </div>

          <?php endwhile; ?>
        <?php endif; ?>

        <!-- Info Jika Tidak Ditemukan -->
        <div class="empty-state" id="emptyState" style="display: none;">
          <i class="fa-solid fa-file-circle-xmark"></i>
          <h3 style="color:#0F172A; margin-bottom:5px;">Data Tidak Ditemukan</h3>
          <p>Pastikan ejaan nama sudah benar, atau hubungi pengelola TPQ.</p>
        </div>

      </div>
    </div>

    <!-- MODAL (POP-UP) BIODATA SANTRI-->
    <div id="modalBiodata" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Biodata Santri</h2>
                <button onclick="tutupBiodata()" class="btn-close" title="Tutup"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="bio-grup">
                    <p class="bio-label">Nama Lengkap</p>
                    <h4 id="bioNama">-</h4>
                </div>
                <div class="bio-grup">
                    <p class="bio-label">Tempat, Tanggal Lahir</p>
                    <h4 id="bioTTL">-</h4>
                </div>
                <div class="bio-grup">
                    <p class="bio-label">Alamat</p>
                    <h4 id="bioAlamat">-</h4>
                </div>
                <div class="bio-grup">
                    <p class="bio-label">Nama Orang Tua/Wali</p>
                    <h4 id="bioOrtu">-</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JS Eksternal -->
    <script src="../script/cek_progres.js"></script>

    <!-- Script Khusus Modal Biodata -->
    <script>
        function bukaBiodata(nama, ttl, alamat, ortu) {
            // Mengisi data ke dalam teks Modal
            document.getElementById('bioNama').innerText = nama;
            document.getElementById('bioTTL').innerText = ttl;
            document.getElementById('bioAlamat').innerText = alamat;
            document.getElementById('bioOrtu').innerText = ortu;
            
            // Menampilkan Modal
            document.getElementById('modalBiodata').style.display = 'flex';
        }

        function tutupBiodata() {
            // Menyembunyikan Modal
            document.getElementById('modalBiodata').style.display = 'none';
        }

        // Menutup modal jika pengguna mengklik area luar kotak modal (gelap)
        window.addEventListener('click', function(event) {
            let modal = document.getElementById('modalBiodata');
            if (event.target === modal) {
                tutupBiodata();
            }
        });
    </script>
</body>
</html>