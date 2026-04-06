<?php
include '../admin/koneksi.php';

// Ambil Semua Data Santri dari Database
$query_santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY nama_lengkap ASC");

// Ambil data pengaturan dengan sangat aman (hindari error jika tabel pengaturan kosong)
$query_atur = mysqli_query($koneksi, "SELECT nama_tpq FROM pengaturan LIMIT 1");
$dt_atur = ($query_atur) ? mysqli_fetch_assoc($query_atur) : false;
$nama_tpq = (is_array($dt_atur) && !empty($dt_atur['nama_tpq'])) ? $dt_atur['nama_tpq'] : 'MSANTRI';
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
      <a href="index.php" class="btn-back">Kembali<i class="fa-solid fa-arrow-right"></i></a>
    </header>

    <!-- Konten Utama -->
    <div class="search-container">
      <div class="search-header">
        <h1>Pencarian Data Santri</h1>
        <p>Ketik nama lengkap anak Anda untuk melihat catatan hafalan dan presensi hari ini.</p>
      </div>

      <!-- Kotak Input Pencarian -->
      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchInput" placeholder="Ketik nama santri (contoh: Ahmad)..." onkeyup="filterSantri()" autocomplete="off">
      </div>

      <!-- AREA KARTU HASIL -->
      <div class="results-area" id="resultsArea">
        
        <?php if ($query_santri && mysqli_num_rows($query_santri) > 0): ?>
          <?php while ($data = mysqli_fetch_assoc($query_santri)): ?>
            
            <?php 
              // 1. PENGAMANAN DATA: Pastikan data tidak NULL agar PHP tidak Crash
              $nama_lengkap = !empty($data['nama_lengkap']) ? (string)$data['nama_lengkap'] : 'Santri Tanpa Nama';
              $inisial = strtoupper(substr($nama_lengkap, 0, 1));
              $status = !empty($data['kehadiran']) ? $data['kehadiran'] : 'alpha';
              
              // 2. Konfigurasi Badge Berdasarkan Status
              $konfigurasi_badge = [
                  'hadir' => ['teks' => 'HADIR HARI INI', 'ikon' => 'fa-check', 'kelas' => 'hadir'],
                  'izin'  => ['teks' => 'IZIN', 'ikon' => 'fa-envelope', 'kelas' => 'izin'],
                  'sakit' => ['teks' => 'SAKIT', 'ikon' => 'fa-bed-pulse', 'kelas' => 'izin'],
                  'alpha' => ['teks' => 'ALPHA (TIDAK HADIR)', 'ikon' => 'fa-xmark', 'kelas' => 'alpha']
              ];
              // Pastikan status yang masuk ada di array, jika tidak, pakaikan alpha
              $badge = isset($konfigurasi_badge[$status]) ? $konfigurasi_badge[$status] : $konfigurasi_badge['alpha'];

              // Variabel catatan & capaian untuk ditampilkan langsung di kartu
              $catatan = !empty($data['catatan_pengajar']) ? (string)$data['catatan_pengajar'] : '';
              $capaian = !empty($data['capaian_hafalan']) ? (string)$data['capaian_hafalan'] : 'Iqra/Juz Amma';
            ?>

            <!-- Kartu Santri Dinamis (Data Pribadi Dihapus, Onclick Dihapus) -->
            <div class="student-card santri-item" style="display: flex;">
                 
              <div class="student-info">
                <!-- Avatar sesuai status -->
                <div class="student-avatar avatar-<?= $status ?>"><?= $inisial ?></div>
                
                <div class="student-details">
                  <!-- Bagian Menampilkan Nama Lengkap -->
                  <h3 class="santri-name"><?= htmlspecialchars($nama_lengkap) ?></h3>
                  <p><i class="fa-solid fa-location-dot" style="color:#10B981;"></i> TPQ <?= htmlspecialchars($nama_tpq) ?></p>
                  
                  <?php if ($catatan !== "" && $catatan !== "- Belum ada catatan -"): ?>
                    <div class="catatan-guru catatan-<?= $status ?>">
                      <strong>Catatan Pengajar:</strong> <?= htmlspecialchars($catatan) ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <div class="student-progress">
                <div class="progress-label">Capaian Terakhir</div>
                <div class="progress-value"><?= htmlspecialchars($capaian) ?></div>
                <span class="badge <?= $badge['kelas'] ?>"><i class="fa-solid <?= $badge['ikon'] ?>"></i> <?= $badge['teks'] ?></span>
              </div>
            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <!-- Tampilan jika database kosong atau query gagal -->
          <div class="no-data">
             <i class="fa-solid fa-database" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
             <p style="color: #64748b;">Belum ada data santri di database atau koneksi terputus.</p>
          </div>
        <?php endif; ?>

        <!-- Info Jika Tidak Ditemukan saat pencarian -->
        <div class="empty-state" id="emptyState" style="display: none;">
          <i class="fa-solid fa-file-circle-xmark"></i>
          <h3 style="color:#0F172A; margin-bottom:5px;">Data Tidak Ditemukan</h3>
          <p>Pastikan ejaan nama sudah benar, atau hubungi pengelola TPQ.</p>
        </div>

      </div>
    </div>

    <!-- Script JavaScript Internal untuk Filter Pencarian -->
    <script>
        // Fungsi Filter Pencarian (Tidak dihapus karena penting untuk mencari nama)
        function filterSantri() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.santri-item');
            let hasResults = false;

            items.forEach(item => {
                const name = item.querySelector('.santri-name').innerText.toLowerCase();
                const isMatch = name.includes(input);
                
                item.style.display = isMatch ? 'flex' : 'none';
                if (isMatch) hasResults = true;
            });

            document.getElementById('emptyState').style.display = hasResults ? 'none' : 'block';
        }
    </script>

</body>
</html>