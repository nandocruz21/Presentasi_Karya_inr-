<?php
include '../admin/koneksi.php';

// Ambil Semua Data Santri dari Database
$query_santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY nama_lengkap ASC");

// Ambil data pengaturan
$query_atur = mysqli_query($koneksi, "SELECT nama_tpq FROM pengaturan LIMIT 1");
$dt_atur = ($query_atur) ? mysqli_fetch_assoc($query_atur) : false;
$nama_tpq = (is_array($dt_atur) && !empty($dt_atur['nama_tpq'])) ? $dt_atur['nama_tpq'] : 'MSANTRI';
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cek Progres Santri - MSANTRI</title>
    <link rel="icon" type="image/png" href="../../images/lg.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/kdr.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
  </head>
  <body>

    <header>
      <div class="logo">
        <img src="../../images/logo.png" alt="logo">
        <span>MSANTRI</span>
      </div>
      <a href="index.php" class="btn-back">
         <span>Kembali ke Beranda</span><i class="fa-solid fa-arrow-right"></i>
      </a>
    </header>

    <div class="search-container">
      <div class="search-header">
        <h1>Pencarian Data Santri</h1>
        <p>Ketik nama lengkap anak Anda untuk memantau catatan perkembangan hafalan dan presensi hari ini secara real-time.</p>
      </div>

      <div class="search-box">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" id="searchInput" placeholder="Ketik nama santri (contoh: Ahmad)..." autocomplete="off" onkeyup="filterSantri()">
      </div>

      <div class="results-area" id="resultsArea">
        
        <?php if ($query_santri && mysqli_num_rows($query_santri) > 0): ?>
          <?php while ($data = mysqli_fetch_assoc($query_santri)): ?>
            
            <?php 
              $id_santri_aman = htmlspecialchars($data['id_santri'], ENT_QUOTES);
              $nama_lengkap = !empty($data['nama_lengkap']) ? (string)$data['nama_lengkap'] : 'Santri Tanpa Nama';
              $status = !empty($data['kehadiran']) ? $data['kehadiran'] : 'alpha';
              
              $tempat_lahir = !empty($data['tempat_lahir']) ? htmlspecialchars($data['tempat_lahir'], ENT_QUOTES) : '-';
              $tgl_lahir = (!empty($data['tanggal_lahir']) && $data['tanggal_lahir'] != '0000-00-00') ? date('d F Y', strtotime($data['tanggal_lahir'])) : '-';
              $nama_ortu = !empty($data['nama_ortu']) ? htmlspecialchars($data['nama_ortu'], ENT_QUOTES) : '-';

              $foto_db = !empty($data['foto']) ? $data['foto'] : 'default.png';
              $path_foto = "../../uploads/" . htmlspecialchars($foto_db, ENT_QUOTES);
              
              $konfigurasi_badge = [
                  'hadir' => ['teks' => 'STATUS: HADIR', 'ikon' => 'fa-check', 'kelas' => 'hadir'],
                  'izin'  => ['teks' => 'STATUS: IZIN', 'ikon' => 'fa-envelope', 'kelas' => 'izin'],
                  'sakit' => ['teks' => 'STATUS: SAKIT', 'ikon' => 'fa-bed-pulse', 'kelas' => 'sakit'],
                  'alpha' => ['teks' => 'STATUS: ALPHA', 'ikon' => 'fa-xmark', 'kelas' => 'alpha']
              ];
              $badge = isset($konfigurasi_badge[$status]) ? $konfigurasi_badge[$status] : $konfigurasi_badge['alpha'];

              $catatan = !empty($data['catatan_pengajar']) ? (string)$data['catatan_pengajar'] : '';
              $capaian = !empty($data['capaian_hafalan']) ? (string)$data['capaian_hafalan'] : 'Iqra/Juz Amma';

              $waktu_db = !empty($data['waktu_update']) ? $data['waktu_update'] : '';
              $waktu_tampil = ($waktu_db !== '') ? date('d/m/Y, H:i', strtotime($waktu_db)) : 'Belum diupdate';
            ?>

            <div class="student-card santri-item">
                 
              <div class="student-info">
                <div class="student-avatar avatar-<?= $status ?>">
                    <img src="<?= $path_foto ?>" alt="Foto <?= htmlspecialchars($nama_lengkap) ?>">
                </div>
                <div class="student-details">
                  <h3 class="santri-name"><?= htmlspecialchars($nama_lengkap) ?></h3>
                  <p><i class="fa-solid fa-location-dot" style="color:#10B981;"></i> TPQ <?= htmlspecialchars($nama_tpq) ?></p>
                  
                  <?php if ($catatan !== "" && $catatan !== "- Belum ada catatan -"): ?>
                    <div class="catatan-guru">
                      <strong>Catatan Pengajar:</strong> <?= htmlspecialchars($catatan) ?>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              
              <div class="student-progress">
                <div class="progress-label">Capaian Terakhir</div>
                <div class="progress-value"><?= htmlspecialchars($capaian) ?></div>
                <div class="waktu-update" title="Waktu terakhir Ustadz/Ustadzah menyimpan data">
                    <i class="fa-regular fa-clock"></i> Update: <?= $waktu_tampil ?>
                </div>
                
                <div class="badge-group">
                    <button class="btn-riwayat" onclick="lihatRiwayat('<?= $id_santri_aman ?>', '<?= htmlspecialchars($nama_lengkap, ENT_QUOTES) ?>', '<?= $tempat_lahir ?>', '<?= $tgl_lahir ?>', '<?= $nama_ortu ?>')">
                        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat
                    </button>
                    <span class="badge <?= $badge['kelas'] ?>"><i class="fa-solid <?= $badge['ikon'] ?>"></i> <?= $badge['teks'] ?></span>
                </div>
              </div>

            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <div class="empty-state" style="display: block;">
             <i class="fa-solid fa-database"></i>
             <h3>Database Kosong</h3>
             <p>Belum ada data santri di database atau koneksi terputus.</p>
          </div>
        <?php endif; ?>

        <div class="empty-state" id="emptyState" style="display: none;">
          <i class="fa-solid fa-file-circle-xmark"></i>
          <h3>Data Tidak Ditemukan</h3>
          <p>Pastikan ejaan nama sudah benar, atau hubungi pengelola TPQ jika data belum diperbarui.</p>
        </div>

      </div>
    </div>

    <!-- MODAL RIWAYAT -->
    <div id="modalRiwayat" class="modal-overlay">
      <div class="modal-content">
        <div class="modal-header">
          <div>
            <p>Riwayat Progres Belajar</p>
            <h2 id="judulNamaRiwayat">Nama Santri</h2>
          </div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <button class="btn-unduh-pdf" id="btnUnduhPDF" onclick="unduhRaporPDF()">
              <i class="fa-solid fa-file-pdf"></i> Unduh Rapor PDF
            </button>
            <button class="btn-close" onclick="tutupModalRiwayat()"><i class="fa-solid fa-xmark"></i></button>
          </div>
        </div>
        <div class="modal-body" id="tempatRiwayat">
            <p style="text-align: center; color: #94A3B8; margin-top: 20px;">Memuat data riwayat...</p>
        </div>
      </div>
    </div>

    <script>
      // =========================================================
      // DATA SANTRI AKTIF (untuk PDF)
      // =========================================================
      let dataSantriAktif = {
        nama: '',
        tempatLahir: '',
        tglLahir: '',
        namaOrtu: '',
        riwayat: []
      };

      // Nama TPQ dari PHP
      const namaTpq = <?= json_encode(strtoupper(htmlspecialchars($nama_tpq))) ?>;
      const tanggalCetak = <?= json_encode(date('d F Y')) ?>;

      // =========================================================
      // FILTER PENCARIAN
      // =========================================================
      function filterSantri() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('santri-item');
        let emptyState = document.getElementById('emptyState');
        let foundCount = 0;

        if(input === "") {
            for (let i = 0; i < cards.length; i++) { cards[i].style.display = "flex"; }
            emptyState.style.display = "none";
            return; 
        }

        for (let i = 0; i < cards.length; i++) {
          let nameElement = cards[i].querySelector('.santri-name');
          let nameText = nameElement.innerText.toLowerCase();
          if (nameText.includes(input)) {
            cards[i].style.display = "flex"; 
            foundCount++;
          } else {
            cards[i].style.display = "none";
          }
        }
        emptyState.style.display = (foundCount === 0) ? "block" : "none";
      }

      function formatTanggalWeb(tglStr) {
          const opsi = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
          return new Date(tglStr).toLocaleDateString('id-ID', opsi);
      }

      function formatTanggalPDF(tglStr) {
          const d = new Date(tglStr);
          const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
          return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
      }

      // =========================================================
      // LIHAT RIWAYAT
      // =========================================================
      function lihatRiwayat(idSantri, namaSantri, tempatLahir, tglLahir, namaOrtu) {
          dataSantriAktif = { nama: namaSantri, tempatLahir: tempatLahir, tglLahir: tglLahir, namaOrtu: namaOrtu, riwayat: [] };

          document.getElementById('judulNamaRiwayat').innerText = namaSantri;
          document.getElementById('modalRiwayat').classList.add('show');
          document.getElementById('tempatRiwayat').innerHTML = '<div class="timeline"><p style="text-align:center;color:#94A3B8;margin-top:20px;"><i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data...</p></div>';

          fetch('get_riwayat.php?id=' + idSantri)
            .then(response => response.json())
            .then(data => {
                dataSantriAktif.riwayat = data;
                let htmlWeb = '<div class="timeline">';

                if(data.length === 0) {
                    htmlWeb += '<p style="text-align:center;color:#EF4444;margin-top:20px;"><i class="fa-solid fa-folder-open"></i> Belum ada riwayat tercatat untuk santri ini.</p>';
                } else {
                    data.forEach(item => {
                        let catatan = (item.catatan_pengajar && item.catatan_pengajar !== '- Belum ada catatan -') ? item.catatan_pengajar : '-';
                        let kelasStatus = 's-' + item.kehadiran;
                        htmlWeb += `
                        <div class="timeline-item">
                            <div class="time-date">
                                <i class="fa-regular fa-clock"></i> ${formatTanggalWeb(item.tanggal_riwayat)} WIB
                            </div>
                            <div class="time-box">
                                <span class="status-label ${kelasStatus}">${item.kehadiran.toUpperCase()}</span>
                                <h4>${item.capaian_hafalan}</h4>
                                <p>${catatan}</p>
                            </div>
                        </div>`;
                    });
                }
                htmlWeb += '</div>';
                document.getElementById('tempatRiwayat').innerHTML = htmlWeb;
            })
            .catch(error => {
                document.getElementById('tempatRiwayat').innerHTML = '<p style="text-align:center;color:#EF4444;">Gagal mengambil data jaringan.</p>';
            });
      }

      function tutupModalRiwayat() {
          document.getElementById('modalRiwayat').classList.remove('show');
      }

      window.onclick = function(event) {
          let modals = document.querySelectorAll('.modal-overlay');
          modals.forEach(function(modal) {
              if (event.target === modal) { modal.classList.remove('show'); }
          });
      }

      // =========================================================
      // FUNGSI DOWNLOAD RAPOR PDF (menggunakan jsPDF + autoTable)
      // Solusi ini 100% bebas terpotong karena dikerjakan di level
      // JavaScript murni — tidak bergantung pada render HTML ke canvas
      // =========================================================
      function unduhRaporPDF() {
          const btn = document.getElementById('btnUnduhPDF');
          btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
          btn.disabled = true;

          try {
              const { jsPDF } = window.jspdf;
              const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });

              const marginKiri   = 15;
              const marginKanan  = 15;
              const marginAtas   = 15;
              const lebarKertas  = 210;
              const lebarIsi     = lebarKertas - marginKiri - marginKanan; // 180mm

              let posY = marginAtas;

              // ---------- KOP SURAT ----------
              doc.setFont('times', 'bold');
              doc.setFontSize(18);
              doc.setTextColor(6, 78, 59); // hijau tua
              doc.text('TPQ ' + namaTpq, lebarKertas / 2, posY + 8, { align: 'center' });

              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.setTextColor(50, 50, 50);
              doc.text('Pusat Pembelajaran Al-Quran dan Ilmu Agama Islam', lebarKertas / 2, posY + 16, { align: 'center' });
              doc.setFontSize(9);
              doc.setTextColor(100, 100, 100);
              doc.text('Cetak Dokumen Otomatis Sistem MSANTRI', lebarKertas / 2, posY + 22, { align: 'center' });

              // Garis bawah kop
              posY += 28;
              doc.setDrawColor(0, 0, 0);
              doc.setLineWidth(0.8);
              doc.line(marginKiri, posY, lebarKertas - marginKanan, posY);
              doc.setLineWidth(0.3);
              doc.line(marginKiri, posY + 1.2, lebarKertas - marginKanan, posY + 1.2);
              posY += 9;

              // ---------- JUDUL ----------
              doc.setFont('times', 'bold');
              doc.setFontSize(13);
              doc.setTextColor(0, 0, 0);
              const judulTeks = 'LAPORAN HASIL PERKEMBANGAN SANTRI';
              doc.text(judulTeks, lebarKertas / 2, posY, { align: 'center' });
              // Garis bawah judul (underline manual)
              const judulLebar = doc.getTextWidth(judulTeks);
              doc.line((lebarKertas - judulLebar) / 2, posY + 0.8, (lebarKertas + judulLebar) / 2, posY + 0.8);
              posY += 10;

              // ---------- BIODATA ----------
              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.setTextColor(0, 0, 0);

              const kolLabel = 50;
              const kolTitik = 5;

              function tulisBaraBiodata(label, nilai) {
                  doc.setFont('times', 'bold');
                  doc.text(label, marginKiri, posY);
                  doc.setFont('times', 'normal');
                  doc.text(':', marginKiri + kolLabel, posY);
                  // Nilai bisa panjang, bungkus teks
                  const nilaiLines = doc.splitTextToSize(nilai, lebarIsi - kolLabel - kolTitik - 5);
                  doc.text(nilaiLines, marginKiri + kolLabel + kolTitik, posY);
                  posY += (nilaiLines.length * 6);
              }

              tulisBaraBiodata('Nama Lengkap', dataSantriAktif.nama);
              tulisBaraBiodata('Tempat, Tgl Lahir', dataSantriAktif.tempatLahir + ', ' + dataSantriAktif.tglLahir);
              tulisBaraBiodata('Nama Orang Tua / Wali', dataSantriAktif.namaOrtu);
              posY += 4;

              // ---------- TABEL RIWAYAT (autoTable) ----------
              // Persentase lebar kolom dari 180mm
              const colWidths = [
                  lebarIsi * 0.07,  // No       = ~12.6mm
                  lebarIsi * 0.18,  // Tanggal  = ~32.4mm
                  lebarIsi * 0.14,  // Kehadiran= ~25.2mm
                  lebarIsi * 0.27,  // Capaian  = ~48.6mm
                  lebarIsi * 0.34   // Catatan  = ~61.2mm
              ];

              // Siapkan body data
              let bodyData = [];
              if (dataSantriAktif.riwayat.length === 0) {
                  bodyData.push([{ content: 'Belum ada data riwayat belajar.', colSpan: 5, styles: { halign: 'center', fontStyle: 'italic', textColor: [150,150,150] } }]);
              } else {
                  dataSantriAktif.riwayat.forEach((item, idx) => {
                      let catatan = (item.catatan_pengajar && item.catatan_pengajar !== '- Belum ada catatan -') ? item.catatan_pengajar : '-';
                      bodyData.push([
                          idx + 1,
                          formatTanggalPDF(item.tanggal_riwayat),
                          item.kehadiran.toUpperCase(),
                          item.capaian_hafalan,
                          catatan
                      ]);
                  });
              }

              doc.autoTable({
                  startY: posY,
                  head: [['No', 'Tanggal Update', 'Kehadiran', 'Capaian Hafalan', 'Catatan Pengajar']],
                  body: bodyData,
                  margin: { left: marginKiri, right: marginKanan },
                  tableWidth: lebarIsi,
                  columnStyles: {
                      0: { cellWidth: colWidths[0], halign: 'center', valign: 'middle' },
                      1: { cellWidth: colWidths[1], halign: 'center', valign: 'middle' },
                      2: { cellWidth: colWidths[2], halign: 'center', valign: 'middle' },
                      3: { cellWidth: colWidths[3], valign: 'top' },
                      4: { cellWidth: colWidths[4], valign: 'top' }
                  },
                  headStyles: {
                      fillColor: [241, 245, 249],
                      textColor: [0, 0, 0],
                      fontStyle: 'bold',
                      lineWidth: 0.3,
                      lineColor: [0, 0, 0],
                      halign: 'center',
                      font: 'times',
                      fontSize: 10
                  },
                  bodyStyles: {
                      font: 'times',
                      fontSize: 10,
                      textColor: [0, 0, 0],
                      lineWidth: 0.3,
                      lineColor: [0, 0, 0],
                      minCellHeight: 8,
                      valign: 'top'
                  },
                  alternateRowStyles: {
                      fillColor: [255, 255, 255]
                  },
                  // Baris tidak terpotong antar halaman
                  rowPageBreak: 'avoid',
                  // Kepala tabel muncul di setiap halaman
                  showHead: 'everyPage',
                  // Margin atas untuk halaman ke-2 dan seterusnya
                  didDrawPage: function(data) {
                      // Nomor halaman di bawah
                      const totalHalaman = '{total_pages_count_string}';
                      doc.setFont('times', 'normal');
                      doc.setFontSize(9);
                      doc.setTextColor(120, 120, 120);
                      doc.text(
                          'Halaman ' + data.pageNumber,
                          lebarKertas / 2,
                          297 - 8,
                          { align: 'center' }
                      );
                  }
              });

              // Ambil posisi Y setelah tabel selesai
              let finalY = doc.lastAutoTable.finalY + 12;

              // Cek apakah area TTD masih muat di halaman ini (butuh ~45mm)
              const sisaHalaman = 297 - 20 - finalY; // 20 = margin bawah
              if (sisaHalaman < 48) {
                  doc.addPage();
                  finalY = marginAtas + 5;
              }

              // ---------- AREA TANDA TANGAN ----------
              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.setTextColor(0, 0, 0);

              const xKiri  = marginKiri;
              const xKanan = lebarKertas - marginKanan - 60; // kanan mulai dari sini
              const lebar  = 60;

              // Kolom kiri: Orang Tua
              doc.text('Mengetahui,', xKiri + lebar / 2, finalY, { align: 'center' });
              doc.text('Orang Tua / Wali Santri', xKiri + lebar / 2, finalY + 6, { align: 'center' });
              // Garis tanda tangan kiri
              doc.line(xKiri + 5, finalY + 38, xKiri + lebar - 5, finalY + 38);
              doc.text('( ' + dataSantriAktif.namaOrtu + ' )', xKiri + lebar / 2, finalY + 43, { align: 'center' });

              // Kolom kanan: Pengajar
              doc.text('Diperiksa pada: ' + tanggalCetak, xKanan + lebar / 2, finalY, { align: 'center' });
              doc.text('Pengajar / Ustadzah', xKanan + lebar / 2, finalY + 6, { align: 'center' });
              // Garis tanda tangan kanan
              doc.line(xKanan + 5, finalY + 38, xKanan + lebar - 5, finalY + 38);
              doc.text('(                              )', xKanan + lebar / 2, finalY + 43, { align: 'center' });

              // ---------- SIMPAN PDF ----------
              const namaFile = 'Rapor_Santri_' + dataSantriAktif.nama.replace(/\s+/g, '_') + '.pdf';
              doc.save(namaFile);

          } catch(err) {
              console.error(err);
              alert('Gagal membuat PDF: ' + err.message);
          }

          btn.innerHTML = '<i class="fa-solid fa-file-pdf"></i> Unduh Rapor PDF';
          btn.disabled = false;
      }
    </script>
    
    <style>
    /* ================= CSS DESAIN WEB UTAMA ================= */
    * { padding: 0; margin: 0; box-sizing: border-box; }
    body { font-family: "Poppins", Helvetica, sans-serif; color: #475569; background-color: #f4fbf9; min-height: 100vh; display: flex; flex-direction: column; background-image: radial-gradient( circle at 15% 50%, rgba(14, 165, 233, 0.08), transparent 30% ); }
    header { padding: 20px 50px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); position: sticky; top: 0; z-index: 100; border-bottom: 1px solid rgba(226, 232, 240, 0.8); }
    .logo { font-weight: 800; font-size: 26px; color: #064e3b; letter-spacing: 1.5px; display: flex; align-items: center; gap: 10px; }
    .logo img { width: 40px; }
    .logo span { color: #000000; }
    .btn-back { display: flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; transition: 0.3s; padding: 10px 20px; border-radius: 50px; }
    .btn-back:hover { color: #059669; transform: translateY(-3px) }
    .search-container { flex-grow: 1; display: flex; flex-direction: column; align-items: center; padding: 60px 20px; max-width: 850px; margin: 0 auto; width: 100%; }
    .search-header { text-align: center; margin-bottom: 40px; }
    .search-header h1 { font-family: "Amiri", serif; font-size: 46px; margin-bottom: 15px; color: #000000; }
    .search-header p { color: #64748b; font-size: 17px; max-width: 600px; margin: 0 auto; line-height: 1.6; }
    .search-box { width: 100%; position: relative; margin-bottom: 50px; }
    .search-box input { width: 100%; padding: 22px 30px 22px 70px; border-radius: 50px; border: 2px solid #ffffff; font-family: "Poppins", sans-serif; font-size: 18px; color: #064e3b; outline: none; background: #ffffff; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.04); transition: 0.4s; }
    .search-box input:focus { border-color: #10b981; box-shadow: 0 20px 50px rgba(16, 185, 129, 0.15); transform: translateY(-2px); }
    .search-box i { position: absolute; left: 30px; top: 50%; transform: translateY(-50%); font-size: 22px; color: #10b981; }
    .results-area { width: 100%; display: flex; flex-direction: column; gap: 20px; }
    
    .student-card { background: #ffffff; border-radius: 20px; padding: 25px 30px; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); display: flex; flex-direction: row; justify-content: space-between; align-items: center; transition: 0.3s; animation: slideUp 0.5s ease-out; position: relative; overflow: hidden; gap: 20px; }
    .student-card::before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, #10b981, #059669); opacity: 0; transition: 0.4s; }
    .student-card:hover { transform: translateY(-5px); border-color: #a7f3d0; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08); }
    .student-card:hover::before { opacity: 1; }
    .student-info { display: flex; align-items: center; gap: 20px; flex: 1; min-width: 0; }
    .student-avatar { width: 65px; height: 65px; border-radius: 50%; background: #f1f5f9; display: flex; justify-content: center; align-items: center; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05); flex-shrink: 0; overflow: hidden; text-align: center; }
    .student-avatar img { width: 100%; height: 100%; object-fit: cover; font-size: 10px; color: #94a3b8; }
    .student-details { display: flex; flex-direction: column; justify-content: center; flex: 1; min-width: 0; }
    .student-details h3 { font-size: 20px; color: #064e3b; margin-bottom: 2px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .student-details p { font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 6px; font-weight: 500; margin-bottom: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .catatan-guru { font-size: 13px; color: #475569; background: #f0fdf4; padding: 8px 12px; border-radius: 8px; border-left: 3px solid #10b981; display: inline-block; word-wrap: break-word; white-space: normal; line-height: 1.5; }
    .student-progress { display: flex; flex-direction: column; align-items: flex-end; min-width: 180px; flex-shrink: 0; }
    .progress-label { font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 2px; }
    .progress-value { font-family: "Amiri", serif; font-size: 22px; font-weight: 700; color: #059669; margin-bottom: 8px; text-align: right; }
    .progress-label, .progress-value, .badge, .waktu-update { white-space: nowrap; }
    .waktu-update { font-size: 11px; color: #64748b; font-weight: 600; margin-bottom: 8px; background: #f1f5f9; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; }
    .waktu-update i { color: #10b981; margin-right: 4px; }
    .badge-group { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
    .btn-riwayat { background: #f1f5f9; color: #0284c7; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 5px; font-family: "Poppins"; outline: none; }
    .btn-riwayat:hover { background: #e0f2fe; border-color: #bae6fd; }
    .badge { padding: 6px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 5px; }
    .badge.hadir { background: #d1fae5; color: #059669; border: 1px solid #a7f3d0; }
    .badge.izin { background: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
    .badge.alpha { background: #fee2e2; color: #e11d48; border: 1px solid #fecaca; }
    .badge.sakit { background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; }
    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; display: none; animation: slideUp 0.4s; }
    .empty-state i { font-size: 60px; margin-bottom: 20px; color: #cbd5e1; }
    .empty-state h3 { color: #064e3b; margin-bottom: 8px; font-size: 22px; }

    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 768px) {
      header { padding: 15px 20px; }
      .logo { font-size: 20px; }
      .btn-back span { display: none; }
      .btn-back { padding: 10px; border-radius: 50%; }
      .search-header h1 { font-size: 32px; }
      .search-box input { font-size: 16px; padding-left: 60px; }
      .student-card { flex-direction: column; align-items: flex-start; padding: 20px; gap: 20px; }
      .student-info { width: 100%; align-items: flex-start; }
      .student-progress { width: 100%; align-items: flex-start; border-top: 1px solid #f1f5f9; padding-top: 15px; }
      .badge-group { flex-direction: row-reverse; width: 100%; justify-content: flex-end; }
    }

    /* ================= MODAL TIMELINE RIWAYAT ================= */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 2000; justify-content: center; align-items: center; opacity: 0; transition: 0.3s; }
    .modal-overlay.show { display: flex; opacity: 1; }
    .modal-content { background: #ffffff; width: 90%; max-width: 550px; max-height: 85vh; border-radius: 24px; padding: 30px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15); transform: translateY(-30px); transition: 0.3s; display: flex; flex-direction: column; }
    .modal-overlay.show .modal-content { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 20px; }
    .modal-header h2 { font-family: "Amiri", serif; font-size: 24px; color: #0f172a; }
    .modal-header p { font-size: 13px; color: #10b981; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .btn-unduh-pdf { background: #ef4444; color: white; border: none; padding: 8px 15px; border-radius: 8px; font-family: "Poppins", sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: 0.3s; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }
    .btn-unduh-pdf:hover:not(:disabled) { background: #dc2626; box-shadow: 0 6px 15px rgba(239, 68, 68, 0.3); transform: translateY(-2px); }
    .btn-unduh-pdf:disabled { opacity: 0.7; cursor: not-allowed; }
    .btn-close { background: none; border: none; font-size: 20px; color: #94a3b8; cursor: pointer; transition: 0.3s; margin-left: 10px; }
    .btn-close:hover { color: #0f172a; }
    .modal-body { overflow-y: auto; padding-right: 10px; flex: 1; }
    .modal-body::-webkit-scrollbar { width: 6px; }
    .modal-body::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    
    .timeline { border-left: 3px solid #e2e8f0; margin: 10px 0 10px 15px; padding-left: 25px; position: relative; }
    .timeline-item { margin-bottom: 25px; position: relative; }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-item::before { content: ""; position: absolute; left: -34px; top: 0; width: 14px; height: 14px; border-radius: 50%; background: #10b981; border: 3px solid #ffffff; box-shadow: 0 0 0 2px #e2e8f0; }
    .time-date { font-size: 12px; color: #64748b; font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 5px; }
    .time-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; border-radius: 16px; }
    .time-box h4 { font-size: 16px; color: #0f172a; margin-bottom: 5px; font-family: "Amiri", serif; }
    .time-box p { font-size: 13px; color: #475569; }
    .status-label { font-size: 10px; padding: 3px 8px; border-radius: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px; }
    .s-hadir { background: #d1fae5; color: #059669; }
    .s-izin { background: #fef3c7; color: #d97706; }
    .s-sakit { background: #e0f2fe; color: #0284c7; }
    .s-alpha { background: #fee2e2; color: #e11d48; }
    </style>
  </body>
</html>