<?php
include '../config/koneksi.php';

// Ambil Pengaturan Jadwal, Slogan & Peta
$q_atur = mysqli_query($koneksi, "SELECT * FROM pengaturan LIMIT 1");
$dt_atur = mysqli_fetch_assoc($q_atur);

$q_testimoni = mysqli_query($koneksi, "SELECT * FROM testimoni ORDER BY id_testi DESC");
?>
<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Semua Ulasan - TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?></title>
    <link rel="icon" type="image/png" href="../public/img/lg.jpeg">

    <!-- Ikon & Font -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- CUSTOM CSS --> <link rel="stylesheet" href="../public/css/style.css" />
    <style>
      body { background-color: #f8fafc; }
      nav { background: white !important; position: sticky; top: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05); z-index: 100; padding: 15px 5%; display: flex; justify-content: space-between; align-items: center;}
      nav .logo { color: #1e293b !important; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 20px;}
      nav ul { list-style: none; margin: 0; padding: 0; }
      nav ul li a { color: #1e293b !important; text-decoration: none; font-weight: 500; transition: color 0.3s; }
      nav ul li a:hover { color: #10b981 !important; }
      .page-header { text-align: center; padding: 60px 20px 20px; }
      .page-header h1 { font-size: 32px; color: #1e293b; margin-bottom: 10px; }
      .page-header p { color: #64748b; }
      .btn-back-home {
        color: #10b981; 
        text-decoration: none; 
        font-weight: 600; 
        font-size: 15px; 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        padding: 10px 20px; 
        border-radius: 12px; 
        background: #ecfdf5; 
        transition: all 0.3s;
        white-space: nowrap;
      }
      .btn-back-home:hover {
        background: #d1fae5;
      }
      @media (max-width: 768px) {
        .page-header { padding: 40px 20px 10px; }
        .page-header h1 { font-size: 24px; }
        .btn-back-home span { display: none; }
        .btn-back-home {
          padding: 0;
          width: 40px;
          height: 40px;
          justify-content: center;
          border-radius: 50%;
        }
      }
    </style>
  </head>
  <body>
    <!-- NAVIGASI -->
    <header style="position: relative; padding: 0; background: transparent; width: 100%; box-shadow: none;">
      <nav style="display: flex; justify-content: space-between; align-items: center; width: 100%; background: #ffffff !important; padding: 15px 5%; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-radius: 0 0 20px 20px;">
        <div class="logo" style="color: #1e293b; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 22px;">
          <img src="../public/img/logo.png" alt="logo" width="40px">
          MSANTRI
        </div>
        <a href="index.php" class="btn-back-home"><i class="fa-solid fa-arrow-left"></i> <span>Kembali ke Beranda</span></a>
      </nav>
    </header>

    <div class="page-header">
      <h1>Semua Ulasan Wali Santri</h1>
      <p>Terima kasih atas kepercayaan Anda kepada TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?>.</p>
    </div>

    <!-- SECTION TESTIMONI -->
    <section class="testimoni" id="testimoni" style="padding-top: 20px; background: transparent; min-height: 60vh;">
      <div class="testimoni-container">
        <?php if($q_testimoni && mysqli_num_rows($q_testimoni) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($q_testimoni)) : ?>
            <div class="testi-modern-card animate__animated animate__fadeInUp">
              <div class="testi-modern-header">
                <div class="testi-user">
                  <div class="testi-avatar"><?= htmlspecialchars(strtoupper($row['inisial'])) ?></div>
                  <div class="testi-info">
                    <h4><?= htmlspecialchars($row['nama_wali']) ?></h4>
                    <span>Wali Santri</span>
                  </div>
                </div>
                <div class="testi-quote-icon">
                  <i class="fa-solid fa-quote-right"></i>
                </div>
              </div>
              <div class="testi-modern-stars">
                <?php 
                for($i = 1; $i <= $row['rating']; $i++){
                    echo '<i class="fa-solid fa-star"></i>';
                }
                ?>
              </div>
              <div class="testi-modern-body">
                <p>"<?= htmlspecialchars($row['isi_testimoni']) ?>"</p>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: #64748b; font-style: italic; width: 100%; text-align: center;">Belum ada testimoni.</p>
        <?php endif; ?>
      </div>
    </section>

  </body>
</html>
