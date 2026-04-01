<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"] != "login"){
    header("Location: login.php");
    exit;
}

// Inisialisasi variabel agar tidak muncul warning "Undefined variable"
$msg = "";
$msg_type = "";

// Ambil pesan dari URL jika ada (setelah hapus)
if(isset($_GET['status']) && $_GET['status'] == 'deleted') {
    $msg = "Foto berhasil dihapus!";
    $msg_type = "success";
}

// 1. LOGIKA UNGGAH FOTO
if (isset($_POST['simpan'])) {
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $foto = $_FILES['foto'];

    if ($foto['error'] === 4) {
        $msg = "Pilih gambar terlebih dahulu!";
        $msg_type = "error";
    } else {
        $ekstensiValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ekstensiGambar, $ekstensiValid)) {
            $msg = "Format file harus JPG, JPEG, atau PNG!";
            $msg_type = "error";
        } elseif ($foto['size'] > 10000000) {
            $msg = "Ukuran file terlalu besar (Maks 10MB)!";
            $msg_type = "error";
        } else {
            $namaBaru = uniqid() . '.' . $ekstensiGambar;
            $targetPath = '../../img/' . $namaBaru;

            if (move_uploaded_file($foto['tmp_name'], $targetPath)) {
                mysqli_query($koneksi, "INSERT INTO galeri (nama_file, keterangan) VALUES ('$namaBaru', '$keterangan')");
                $msg = "Foto berhasil diterbitkan!";
                $msg_type = "success";
            } else {
                $msg = "Gagal mengunggah file. Pastikan folder ../../img/ tersedia.";
                $msg_type = "error";
            }
        }
    }
}

// 2. LOGIKA HAPUS FOTO
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $cek = mysqli_query($koneksi, "SELECT nama_file FROM galeri WHERE id_galeri = '$id'");
    $data = mysqli_fetch_assoc($cek);
    
    if ($data) {
        if(file_exists('../../img/' . $data['nama_file'])) {
            unlink('../../img/' . $data['nama_file']);
        }
        mysqli_query($koneksi, "DELETE FROM galeri WHERE id_galeri = '$id'");
        header("Location: admin_galeri.php?status=deleted");
        exit;
    }
}

// 3. AMBIL DATA GALERI (PENTING: Harus sebelum HTML agar tidak error)
$query_galeri = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY id_galeri DESC");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Galeri - MSANTRI Admin</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Menggunakan CSS Dashboard yang sama agar seragam -->
    <link rel="stylesheet" href="../style/admin.css" />
    <link rel="stylesheet" href="../style/admin_galeri.css" />
</head>
<body>

    <!-- SIDEBAR (Identik dengan Dashboard) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="../../gambar/logo.png" alt="Logo" />
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
            <!-- Nav Galeri Active -->
            <a href="admin_galeri.php" class="ini-nav active">
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

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <button class="btn-hamburger" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="header-content">
            <h1>Galeri Dokumentasi</h1>
            <p>Unggah momen kegiatan santri untuk ditampilkan di halaman utama.</p>
        </div>

        <!-- Notifikasi -->
        <?php if($msg != ""): ?>
            <div class="alert alert-<?= $msg_type ?>"><?= $msg ?></div>
        <?php endif; ?>

        <div class="baris-flex">
            <!-- Form Unggah -->
            <div class="card card-kiri">
                <h3 style="margin-bottom:20px; font-family: Amiri; font-size:24px; color:#1e293b;">Unggah Foto</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="grup-form">
                        <label>Pilih File Foto</label>
                        <input type="file" name="foto" required>
                    </div>
                    <div class="grup-form">
                        <label>Keterangan / Judul Foto</label>
                        <textarea name="keterangan" rows="3" placeholder="Contoh: Belajar Tajwid Bersama" required></textarea>
                    </div>
                    <button type="submit" name="simpan" class="btn-submit">
                        <i class="fa-solid fa-paper-plane"></i> Terbitkan Foto
                    </button>
                </form>
            </div>

            <!-- Daftar Galeri -->
            <div class="kolom-kanan">
                <div class="grid-galeri">
                    <?php if(mysqli_num_rows($query_galeri) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($query_galeri)): ?>
                            <div class="kartu-galeri">
                                <a href="?hapus=<?= $row['id_galeri'] ?>" class="btn-hapus-foto" onclick="return confirm('Hapus foto ini dari galeri?')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                                <div class="img-container">
                                    <img src="../../img/<?= $row['nama_file'] ?>" alt="Dokumentasi">
                                </div>
                                <div class="info-galeri">
                                    <p><?= htmlspecialchars($row['keterangan']) ?></p>
                                    <small style="color:#94a3b8; font-size:10px;"><?= date('d/m/Y', strtotime($row['tanggal_upload'])) ?></small>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div style="grid-column: 1/-1; text-align:center; padding: 50px; background:#fff; border-radius:20px; color:#94a3b8; border: 1px dashed #cbd5e1;">
                            <i class="fa-regular fa-images" style="font-size: 40px; margin-bottom:10px;"></i>
                            <p>Belum ada koleksi foto dokumentasi.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Tutup sidebar saat klik di luar (khusus HP)
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const hamburger = document.querySelector('.btn-hamburger');
            if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                if (sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    </script>
</body>
</html>