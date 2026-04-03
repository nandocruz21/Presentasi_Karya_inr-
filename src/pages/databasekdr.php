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
    <style>
        /* Memastikan area hasil terlihat dengan baik */
        .results-area {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            width: 100%;
            max-width: 800px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
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

              // 3. PENGAMANAN BIODATA (Termasuk WA Ortu)
              $tempat_lahir = !empty($data['tempat_lahir']) ? $data['tempat_lahir'] : '-';
              $tanggal_lahir = !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : '-';
              $ttl_gabung = $tempat_lahir . ', ' . $tanggal_lahir;
              
              $alamat = !empty($data['alamat']) ? (string)$data['alamat'] : '-';
              $nama_ortu = !empty($data['nama_ortu']) ? (string)$data['nama_ortu'] : '-';
              $no_wa_ortu = !empty($data['no_wa_ortu']) ? (string)$data['no_wa_ortu'] : '-';
              
              $catatan = !empty($data['catatan_pengajar']) ? (string)$data['catatan_pengajar'] : '';
              $capaian = !empty($data['capaian_hafalan']) ? (string)$data['capaian_hafalan'] : 'Iqra/Juz Amma';
            ?>

            <!-- Kartu Santri Dinamis (Menggunakan Data Attribute agar Anti Error) -->
            <div class="student-card santri-item" style="display: flex; cursor: pointer;" 
                 data-nama="<?= htmlspecialchars($nama_lengkap, ENT_QUOTES) ?>"
                 data-ttl="<?= htmlspecialchars($ttl_gabung, ENT_QUOTES) ?>"
                 data-alamat="<?= htmlspecialchars($alamat, ENT_QUOTES) ?>"
                 data-ortu="<?= htmlspecialchars($nama_ortu, ENT_QUOTES) ?>"
                 data-wa="<?= htmlspecialchars($no_wa_ortu, ENT_QUOTES) ?>"
                 onclick="lihatBiodataUser(this)">
                 
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

    <!-- MODAL BIODATA -->
    <div id="modalBiodata" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Biodata Santri</h2>
                <button onclick="tutupBiodata()" class="btn-close"><i class="fa-solid fa-xmark"></i></button>
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
                <!-- Form Nomor WA -->
                <div class="bio-grup">
                    <p class="bio-label">No. WhatsApp Orang Tua</p>
                    <h4 id="bioWA" style="color: #000000;">-</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Kita hapus pemanggilan cek_progres.js agar tidak bentrok dengan script di bawah -->
    <script src="../script/cek_progres.js"></script>


</body>
</html>