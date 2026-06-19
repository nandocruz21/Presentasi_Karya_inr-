<?php
include '../config/koneksi.php';

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
    <link rel="icon" type="image/png" href="../public/img/lg.jpeg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/kdr.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
  </head>
  <body>

    <header>
      <div class="logo">
        <img src="../public/img/logo.png" alt="logo">
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
              $path_foto = "../public/uploads/" . htmlspecialchars($foto_db, ENT_QUOTES);
              
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
      
      // DATA SANTRI AKTIF (untuk PDF)
      
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
      const logoBase64 = '../public/img/logo.png';

      
      // FILTER PENCARIAN
      
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

      
      // LIHAT RIWAYAT
      
      function lihatRiwayat(idSantri, namaSantri, tempatLahir, tglLahir, namaOrtu) {
          dataSantriAktif = { nama: namaSantri, tempatLahir: tempatLahir, tglLahir: tglLahir, namaOrtu: namaOrtu, riwayat: [] };

          document.getElementById('judulNamaRiwayat').innerText = namaSantri;
          document.getElementById('modalRiwayat').classList.add('show');
          document.getElementById('tempatRiwayat').innerHTML = '<div class="timeline"><p style="text-align:center;color:#94A3B8;margin-top:20px;"><i class="fa-solid fa-circle-notch fa-spin"></i> Memuat data...</p></div>';

          // Fetch ke endpoint API yang sudah dipindah ke /api/
          fetch('../api/get_riwayat.php?id=' + idSantri)
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

      
      // FUNGSI DOWNLOAD RAPOR PDF (menggunakan jsPDF + autoTable)
      
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

              // ---------- KOP SURAT DENGAN LOGO ----------
              const logoUkuran = 35; // mm
              const kopTinggi  = 34;

              // Gambar logo di sisi kiri
              const logoX = marginKiri + 10;
              const logoY = posY + (kopTinggi - logoUkuran);
              doc.addImage(logoBase64, 'PNG', logoX, logoY, logoUkuran, logoUkuran);

              // Teks kop rata tengah di sisi kanan logo
              const teksKopX = lebarKertas / 2 + 8;
              const tengahKop = posY + kopTinggi / 2;

              doc.setFont('times', 'bold');
              doc.setFontSize(20);
              doc.setTextColor(6, 78, 59);
              doc.text('TPQ ' + namaTpq, teksKopX, tengahKop - 7, { align: 'center' });

              doc.setFont('times', 'normal');
              doc.setFontSize(11);
              doc.setTextColor(0, 0, 0);
              doc.text('Pembelajaran Al-Quran dan Ilmu Agama Islam', teksKopX, tengahKop + 1, { align: 'center' });

              doc.setFontSize(9);
              doc.setTextColor(100, 100, 100);
              doc.text('Cetak Dokumen Otomatis Sistem MSANTRI', teksKopX, tengahKop + 8, { align: 'center' });

              // Garis bawah kop
              posY += kopTinggi + 3;
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
                  const nilaiLines = doc.splitTextToSize(nilai, lebarIsi - kolLabel - kolTitik - 5);
                  doc.text(nilaiLines, marginKiri + kolLabel + kolTitik, posY);
                  posY += (nilaiLines.length * 6);
              }

              tulisBaraBiodata('Nama Lengkap', dataSantriAktif.nama);
              tulisBaraBiodata('Tempat, Tgl Lahir', dataSantriAktif.tempatLahir + ', ' + dataSantriAktif.tglLahir);
              tulisBaraBiodata('Nama Orang Tua / Wali', dataSantriAktif.namaOrtu);
              posY += 4;

              // ---------- TABEL RIWAYAT (autoTable) ----------
              const colWidths = [
                  lebarIsi * 0.07,
                  lebarIsi * 0.18,
                  lebarIsi * 0.14,
                  lebarIsi * 0.27,
                  lebarIsi * 0.34
              ];

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
                      3: { cellWidth: colWidths[3], valign: 'center', valign: 'middle' },
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
                  rowPageBreak: 'avoid',
                  showHead: 'everyPage',
                  didDrawPage: function(data) {
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
              const xKanan = lebarKertas - marginKanan - 60;
              const lebar  = 60;

              // Kolom kiri: Orang Tua
              doc.text('Mengetahui,', xKiri + lebar / 2, finalY, { align: 'center' });
              doc.text('Orang Tua / Wali Santri', xKiri + lebar / 2, finalY + 6, { align: 'center' });
              doc.line(xKiri + 5, finalY + 38, xKiri + lebar - 5, finalY + 38);
              doc.text('( ' + dataSantriAktif.namaOrtu + ' )', xKiri + lebar / 2, finalY + 43, { align: 'center' });

              // Kolom kanan: Pengajar
              doc.text('Diperiksa pada: ' + tanggalCetak, xKanan + lebar / 2, finalY, { align: 'center' });
              doc.text('Pengajar / Ustadzah', xKanan + lebar / 2, finalY + 6, { align: 'center' });
              doc.line(xKanan + 5, finalY + 38, xKanan + lebar - 5, finalY + 38);
              doc.text('(Nurjannah)', xKanan + lebar / 2, finalY + 43, { align: 'center' });

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
  </body>
</html>
