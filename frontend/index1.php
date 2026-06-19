<?php
include '../config/koneksi.php';

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

    <!-- CSS DIGABUNGKAN DI SINI -->
    <style>
      * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
      }

      section[id] {
        scroll-margin-top: 90px;
      }

      body {
        font-family: "Poppins", sans-serif;
        color: #475569;
        line-height: 1.6;
        background-color: #ffffff;
        overflow-x: hidden;
        scroll-behavior: smooth;
      }

      .logo {
        font-weight: 700;
        font-size: 26px;
        color: #0f172a;
        letter-spacing: 1px;
      }

      header {
        top: 0;
        z-index: 1000;
        position: fixed;
        width: 100%;
        padding: 40px 50px;
        background: transparent;
        display: flex;
        align-items: center;
        transition:
          background-color 0.4s ease-in-out,
          box-shadow 0.4s ease-in-out,
          padding 0.4s ease-in-out;
      }

      header.scrolled {
        background-color: rgba(255, 255, 255, 1);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2);
        padding: 20px 50px;
      }

      nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        position: relative;
      }

      /* Tombol hamburger user disembunyikan di Laptop */
      .btn-hamburger-user {
        display: none;
        font-size: 26px;
        background: none;
        border: none;
        color: #0f172a;
        cursor: pointer;
        transition: 0.3s;
      }

      .btn-hamburger-user:hover {
        color: #10b981;
      }

      nav a {
        position: relative;
        padding: 8px 15px;
        text-decoration: none;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s ease;
      }

      nav ul {
        list-style: none;
        display: flex;
        align-items: center;
        gap: 10px;
      }

      ul li a:not(.btn-nav):hover {
        color: #059669;
      }

      ul li a:not(.btn-nav)::after {
        content: "";
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background-color: #10b981;
        transition: all 0.3s ease;
        transform: translateX(-50%);
      }

      ul li a:not(.btn-nav):hover::after {
        width: 80%;
      }

      .hero {
        text-align: center;
        padding: 20px;
        background-image:
          linear-gradient(rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.9)),
          url("../public/img/3.png"); /* Pastikan path gambar ini benar sesuai struktur folder Anda */
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }

      .hero-subtitle {
        font-family: "Amiri", serif;
        font-size: 28px;
        color: #059669;
        margin-bottom: 10px;
      }

      .hero h1 {
        font-family: "Amiri", serif;
        font-size: clamp(38px, 8vw, 65px);
        line-height: 1.1;
        color: #0f172a;
        margin-bottom: 20px;
      }

      .hero .highlight {
        color: #059669;
      }

      .hero p {
        font-size: 18px;
        color: #475569;
        margin-bottom: 35px;
        max-width: 650px;
      }

      /* Tombol cek progres */
      .cta {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
      }

      .btn-primary {
        background: #059669;
        color: white;
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 10px 20px rgba(5, 150, 105, 0.3);
        transition: 0.3s ease;
      }

      .btn-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(5, 150, 105, 0.4);
      }

      .btn-outline {
        border: 2px solid #10b981;
        color: #059669;
        padding: 18px 40px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s ease;
      }

      .btn-outline:hover {
        background: #059669;
        color: white;
        transform: translateY(-5px);
      }

      /* =========================================================
         SECTION SAMBUTAN (DESAIN BARU)
         ========================================================= */
      .sambutan-section {
        padding: 100px 50px;
        background-color: #ffffff;
      }

      .container-sambutan {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2.2fr 1fr;
        gap: 50px;
        align-items: start;
      }

      .sambutan-title {
        font-family: "Amiri", serif;
        font-size: 32px;
        color: #059669;
        text-transform: uppercase;
        margin-bottom: 5px;
      }

      .sambutan-subtitle {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
      }

      .foto-kepala {
        float: left;
        width: 180px;
        height: auto;
        margin: 5px 25px 15px 0;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 4px solid #f8fafc;
      }

      .teks-sambutan p {
        font-size: 15px;
        color: #475569;
        line-height: 1.9;
        margin-bottom: 15px;
        text-align: justify;
      }

      /* Kotak Hijau Sidebar "Tentang Kami" */
      .sambutan-sidebar .sidebar-box {
        background: linear-gradient(135deg, #10b981, #059669);
        padding: 30px;
        border-radius: 20px;
        color: #ffffff;
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.2);
        position: sticky;
        top: 120px;
      }

      .sidebar-box h3 {
        font-family: "Amiri", serif;
        font-size: 20px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        padding-bottom: 15px;
        margin-bottom: 20px;
        letter-spacing: 1px;
      }

      .sidebar-box ul {
        list-style: none;
      }

      .sidebar-box li a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #d1fae5;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-decoration: none;
        font-size: 15px;
        transition: 0.3s;
      }

      .sidebar-box li a:hover, 
      .sidebar-box li a.aktif {
        font-weight: 600;
        color: #ffffff;
        padding-left: 8px;
      }

      .sidebar-box li a i {
        font-size: 12px;
      }

      /* SECTION TENTANG */
      .kedua {
        padding: 100px 50px;
        background-color: #ceffe6;
        background-image:
          radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
          radial-gradient(at 100% 100%, rgba(6, 78, 59, 0.04) 0px, transparent 50%);
      }

      .utama1 {
        max-width: 1200px;
        margin: 0 auto;
      }

      .container {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 30px;
        align-items: stretch;
      }

      .utama {
        grid-row: span 3;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .section-title {
        font-family: "Amiri", serif;
        font-size: clamp(32px, 5vw, 55px);
        color: #0f172a;
        margin-bottom: 25px;
        line-height: 1.2;
      }

      .grid-item {
        padding: 40px;
        border-radius: 20px;
        background-color: #ffffff;
        border: 1px solid rgba(209, 250, 229, 0.6);
        transition: 0.4s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      }

      .grid-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(5, 150, 105, 0.08);
        border-color: #10b981;
      }

      .info-kedua {
        background: #059669;
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3);
      }

      .grid-item-small {
        border-left: 4px solid #10b981;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }

      .grid-item-small h3 {
        font-family: "Amiri", serif;
        font-size: 22px;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 15px;
      }

      .grid-item-small p {
        font-size: 15px;
        color: #64748b;
      }

      /* =========================================================
         SECTION GALERI (BARU)
         ========================================================= */
      .galeri-section {
        padding: 100px 20px;
        background-color: #f8fafc;
      }

      .Utama-galeri {
        text-align: center;
        margin-bottom: 50px;
      }

      .Utama-galeri h1 {
        font-family: "Amiri", serif;
        font-size: clamp(32px, 5vw, 50px);
        color: #0f172a;
        margin-bottom: 10px;
      }

      .Utama-galeri p {
        font-size: 18px;
        color: #64748b;
        max-width: 600px;
        margin: 0 auto;
      }

      .galeri-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
      }

      .galeri-item {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        position: relative;
        cursor: pointer;
      }

      .galeri-item img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
      }

      .galeri-item:hover img {
        transform: scale(1.1); /* Efek zoom-in saat di-hover */
      }

      /* Lapisan gradient gelap saat di-hover untuk memunculkan teks (opsional) */
      .galeri-item::after {
        content: '';
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .galeri-item:hover::after {
        opacity: 1;
      }

      /* SECTION PAPAN INFORMASI */
      .ketiga {
        padding: 100px 20px;
        text-align: center;
        background-color: #ffffff;
      }

      .Utama2 h1 {
        font-family: "Amiri", serif;
        font-size: clamp(32px, 5vw, 50px);
        color: #0f172a;
        margin-bottom: 10px;
      }
      
      .Utama2 p {
        font-size: 18px;
      }

      .bungkus {
        display: flex;
        justify-content: center;
        padding-top: 50px;
      }

      .box {
        border: 4px solid #d1fae5;
        padding: 50px 40px;
        width: 100%;
        max-width: 850px;
        border-radius: 20px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: 0.4s ease;
      }
      
      .box:hover{
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(5, 150, 105, 0.08);
      }
      
      .dalam1 {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
      }

      .info-pengumuman {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        width: 65px;
        height: 65px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.15);
      }

      .info-pengumuman i {
        line-height: 0;
      }

      .dalam1 p {
        font-size: clamp(20px, 6vw, 45px);
        font-weight: 800;
        color: #059669;
        letter-spacing: 2px;
        text-transform: uppercase;
      }

      .dalam1 h3 {
        font-family: "Amiri", serif;
        font-size: clamp(18px, 4vw, 32px);
        color: #1e293b;
      }

      /* SECTION JADWAL */
      .keempat {
        padding: 100px 5px;
        background-color: #f0fdf4;
      }

      .Utama3 h1 {
        font-family: "Amiri", serif;
        font-size: 45px;
        color: #0f172a;
        text-align: center;
        margin-bottom: 15px;
      }

      .Utama3 p {
        text-align: center;
        font-size: 18px;
        color: #64748b;
        margin-bottom: 60px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
      }

      .bungkus-jadwal {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: stretch;
        gap: 30px;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
      }

      .bungkus-jadwal-isi {
        background-color: white;
        border: 1px solid rgba(209, 250, 229, 0.8);
        padding: 35px 35px;
        width: 300px;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        flex: 1 1 250px;
        text-align: center;
        transition: 0.4s ease;
        box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.3);
      }

      .bungkus-jadwal-isi:hover {
        transform: translateY(-10px);
        border-color: #10b981;
        box-shadow: 0 20px 40px rgba(5, 150, 105, 0.25);
      }

      .box-jadwal1 p {
        font-size: 15px;
        font-weight: 800;
        color: #10b981;
        letter-spacing: 3px;
      }

      .box-jadwal1 h2 {
        font-family: "Amiri", serif;
        font-size: 32px;
        color: #1e293b;
        margin: 20px 0 25px;
      }
        
      .box-jadwal2 {
        font-size: 15px;
        color: #1e293b;
        font-weight: 500;
        flex-grow: 1;
        margin-bottom: 30px;
      }

      .box-jadwal3 {
        border-top: 1px solid #f0fdf4;
        padding-top: 25px;
      }

      .box-jadwal3 span {
        font-weight: 700;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        color: #1e293b;
      }

      .box-jadwal3 i {
        color: #10b981;
      }

      .bungkus-jadwal-isi.libur {
        background-color: #fff5f5;
        border-color: #fee2e2;
      }

      .bungkus-jadwal-isi.libur .box-jadwal1 p,
      .bungkus-jadwal-isi.libur .box-jadwal3 i {
        color: #ef4444;
      }

      .bungkus-jadwal-isi.libur .box-jadwal3 span {
        letter-spacing: 4px;
      }

      /* SECTION LOKASI */
      .kelima {
        padding: 100px 20px;
        background-color: #ffffff;
      }

      .Utama4 h1 {
        text-align: center;
        font-family: Amiri;
        font-size: 50px;
        color: #0f172a;
        margin-bottom: 30px;
      }

      .map-wrapper {
        width: 95%;
        height: 550px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 5px solid #fff;
      }
      
      .map-wrapper iframe {
        width: 100%;
        height: 100%;
        border: 0;
        display: block;
        border-radius: 10px;
      }

      /* FOOTER */
      .footer-modern {
        background: linear-gradient(135deg, #064e3b, #022c22);
        color: #ecfdf5;
        padding: 80px 50px 20px 50px;
        font-family: "Poppins", sans-serif;
      }

      .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1.5fr;
        gap: 50px;
        margin-bottom: 60px;
      }

      .footer-brand {
        font-family: "Amiri", serif;
        font-size: 36px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 10px;
        letter-spacing: 1px;
      }
      
      .footer-slogan {
        font-size: 15px;
        color: #f1fff7;
        margin-bottom: 30px;
        font-style: italic;
        max-width: 400px;
      }
      
      .footer-address h4 {
        font-size: 18px;
        color: #ffffff;
        margin-bottom: 10px;
        font-weight: 600;
      }
      
      .footer-address p {
        font-size: 14px;
        color: #d1fae5;
        line-height: 1.8;
      }

      .footer-col h4 {
        font-size: 18px;
        color: #ffffff;
        margin-bottom: 25px;
        font-weight: 600;
        position: relative;
        display: inline-block;
      }
      
      .footer-col h4::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 80px;
        height: 3px;
        background-color: #10b981;
        border-radius: 5px;
      }
      
      .footer-links {
        list-style: none;
        padding: 0;
      }
      
      .footer-links li {
        margin-bottom: 15px;
      }
      
      .footer-links a {
        color: #f1fff7;
        text-decoration: none;
        font-size: 14px;
        transition: 0.3s ease;
        display: flex;
        align-items: center;
      }
      
      .footer-links a::before {
        content: "\f105";
        font-family: "Font Awesome 6 Free"; 
        font-weight: 900;
        margin-right: 10px;
        color: #10b981;
        font-size: 12px;
        transition: 0.3s ease;
      }
      
      .footer-links a:hover {
        color: #ffffff;
        padding-left: 5px;
      }

      .footer-contact-text {
        font-size: 14px;
        color: #f1fff7;
        margin-bottom: 25px;
        line-height: 1.8;
      }
      
      .footer-socials {
        display: flex;
        gap: 15px;
      }
      
      .footer-socials a {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ffffff;
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
      }
      
      .footer-socials a:hover {
        background: #10b981;
        border-color: #10b981;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.4);
      }

      .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 25px;
        text-align: center;
      }
      
      .footer-bottom p {
        font-size: 13px;
        color: #a7f3d0;
      }

      /* =========================================================
         RESPONSIVE LAYAR HP (DI BAWAH 768PX)
         ========================================================= */
      @media (max-width: 768px) {
        header {
          padding: 15px 25px;
        }
        
        header.scrolled {
          padding: 15px 25px;
        }
        
        .logo {
          font-size: 22px;
        }

        /* Memunculkan Tombol Hamburger */
        .btn-hamburger-user {
          display: block; 
        }

        /* Mengubah Navigasi menjadi Dropdown Ke Bawah */
        nav ul {
          display: none;
          flex-direction: column;
          position: absolute;
          top: 100%;
          left: -25px;
          width: calc(100% + 50px);
          background-color: #ffffff;
          box-shadow: 0 15px 20px rgba(0, 0, 0, 0.1);
          padding: 10px 0 20px 0;
          gap: 0;
        }
        
        nav ul.show {
          display: flex;
        }
        
        nav a {
          font-size: 16px;
          padding: 15px 20px;
          justify-content: center;
          width: 100%;
        }

        .sambutan-section {
          padding: 60px 20px;
        }
        .container-sambutan {
          grid-template-columns: 1fr;
          gap: 40px;
        }
        .foto-kepala {
          width: 140px;
        }

        .container {
          grid-template-columns: 1fr;
        }
        .utama {
          grid-row: auto;
        }
        .box {
          padding: 30px 20px;
        }

        /* Responsif Galeri di HP */
        .galeri-section {
          padding: 60px 20px;
        }
        .galeri-grid {
          grid-template-columns: 1fr; /* 1 kolom penuh di HP */
        }
        .galeri-item img {
          height: 220px; /* Sedikit lebih pendek di HP */
        }

        .bungkus-jadwal-isi {
          width: 100%;
          max-width: 400px;
        }
        .footer-container {
          grid-template-columns: 1fr;
          gap: 30px;
        }
      }
    </style>
  </head>
  <body>
    <!-- NAVIGASI & HERO -->
    <section id="beranda">
      <header>
        <nav>
          <div class="logo">MSANTRI</div>
          
          <!-- Tombol Hamburger khusus HP -->
          <button class="btn-hamburger-user" onclick="toggleNav()">
            <i class="fa-solid fa-bars"></i>
          </button>

          <!-- Daftar Menu Navigasi -->
          <ul id="navMenu">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#sambutan">Sambutan</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#galeri">Galeri</a></li> <!-- TAMBAHAN MENU GALERI -->
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
          <a href="cek_rapor.php" class="btn-primary">Cek Progres Santri</a>
          <a href="https://wa.link/0jox26" class="btn-outline">Daftar</a>
        </div>
      </div>
    </section>

    <!-- SECTION SAMBUTAN KETUA -->
    <section class="sambutan-section" id="sambutan">
      <div class="container-sambutan">
        
        <!-- Bagian Kiri: Foto dan Teks -->
        <div class="sambutan-content">
          <h2 class="sambutan-title">SAMBUTAN KEPALA TPQ</h2>
          <p class="sambutan-subtitle">TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?></p>
          
          <div class="teks-sambutan">
            <img src="https://ui-avatars.com/api/?name=Kepala+TPQ&background=10b981&color=fff&size=300" alt="Foto Kepala TPQ" class="foto-kepala">
            
            <p><strong>Assalamu'alaikum Warahmatullahi Wabarakatuh</strong></p>
            <p>Alhamdulillah segala puji bagi Allah swt, salawat dan salam semoga selalu dilimpahkan Allah kepada Rasul-Nya Nabi Muhammad beserta keluarga dan para sahabat dan orang-orang yang konsisten berpegang dengan ajaran dan sunnahnya pada setiap masa hingga datangnya hari pembalasan kelak.</p>
            <p>Mengupayakan institusi pendidikan Islam yang dapat dan mampu melahirkan kader-kader pecinta Al-Quran merupakan amal usaha yang sangat mulia. Oleh karena itu, kehadiran TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?> ini merupakan sebuah dedikasi kami untuk ummat.</p>
            <p>Kami menyambut baik kehadiran website resmi ini. Tujuan utamanya adalah sebagai sarana informasi dan komunikasi antar pengurus TPQ, wali santri, dan masyarakat luas. Melalui website ini, wali santri dapat dengan mudah memantau perkembangan hafalan putra-putrinya secara online dan real-time.</p>
            <p>Akhir kata, kami berharap inovasi ini dapat meningkatkan sinergi antara TPQ dan orang tua di rumah. Wassalamu'alaikum Warahmatullahi Wabarakatuh.</p>
          </div>
        </div>

        <!-- Bagian Kanan: Sidebar Menu -->
        <div class="sambutan-sidebar">
          <div class="sidebar-box">
            <h3>TENTANG KAMI</h3>
            <ul>
              <li><a href="#sambutan" class="aktif"><i class="fa-solid fa-angle-right"></i> Sambutan Ketua</a></li>
              <li><a href="#tentang"><i class="fa-solid fa-angle-right"></i> Visi & Misi</a></li>
              <li><a href="#galeri"><i class="fa-solid fa-angle-right"></i> Galeri Kegiatan</a></li>
              <li><a href="#jadwal"><i class="fa-solid fa-angle-right"></i> Program & Jadwal</a></li>
              <li><a href="#informasi"><i class="fa-solid fa-angle-right"></i> Papan Informasi</a></li>
            </ul>
          </div>
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

    <!-- =========================================================
         SECTION GALERI KEGIATAN (BARU DITAMBAHKAN)
         ========================================================= -->
    <section class="galeri-section" id="galeri">
      <div class="Utama-galeri">
        <h1>Dokumentasi Kegiatan</h1>
        <p>Potret momen-momen indah saat para santri belajar, menghafal, dan berinteraksi di lingkungan TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?>.</p>
      </div>

      <div class="galeri-grid">
        <!-- Foto 1 -->
        <div class="galeri-item">
          <!-- Ganti URL ini dengan foto dokumentasi asli Anda (contoh: ../gambar/kegiatan1.jpg) -->
          <img src="https://images.unsplash.com/photo-1609599006353-e629aaab31f7?auto=format&fit=crop&w=800&q=80" alt="Santri Mengaji">
        </div>
        
        <!-- Foto 2 -->
        <div class="galeri-item">
          <img src="https://images.unsplash.com/photo-1584553421349-355dc37900ee?auto=format&fit=crop&w=800&q=80" alt="Kegiatan TPQ">
        </div>

        <!-- Foto 3 -->
        <div class="galeri-item">
          <img src="https://images.unsplash.com/photo-1590076215667-87ebce35ef65?auto=format&fit=crop&w=800&q=80" alt="Suasana Mengaji">
        </div>
        
        <!-- Jika Anda ingin menambah foto lagi, cukup copy paste <div class="galeri-item">...</div> ke bawah sini -->
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

    <!-- FOOTER MODERN BARU -->
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
            <li><a href="cek_rapor.php">Cek Progres Santri</a></li>
            <li><a href="#galeri">Galeri Kegiatan</a></li>
            <li><a href="#jadwal">Jadwal Pengajian</a></li>
            <li><a href="#informasi">Papan Informasi</a></li>
            <li><a href="../admin/login.php">Masuk (Login Admin)</a></li>
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
          </div>
        </div>

      </div>

      <!-- Copyright Bar -->
      <div class="footer-bottom">
        <p>Copyright &copy; <?= date('Y') ?> TPQ <?= htmlspecialchars($dt_atur['nama_tpq']) ?>. All Rights Reserved.</p>
      </div>
    </footer>

    <!-- SCRIPT HAMBURGER MENU & SCROLL NAV -->
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

      // Menutup menu dropdown saat klik di luar menu navigasi
      document.addEventListener('click', function(event) {
        const navMenu = document.getElementById('navMenu');
        const hamburgerBtn = document.querySelector('.btn-hamburger-user');
        
        if (navMenu && hamburgerBtn && !navMenu.contains(event.target) && !hamburgerBtn.contains(event.target)) {
            if (navMenu.classList.contains('show')) {
                navMenu.classList.remove('show');
            }
        }
      });
      
      // Logika efek scroll untuk header
      window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
            // Jika menu tertutup saat discroll ke paling atas, backgroundnya transparan lagi
            if (!document.getElementById('navMenu').classList.contains('show')) {
              header.style.backgroundColor = 'transparent';
            }
        }
      });
    </script>
  </body>
</html>