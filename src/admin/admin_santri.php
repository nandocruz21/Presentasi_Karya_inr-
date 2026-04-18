<?php
session_start();

// 1. Proteksi Halaman
if (!isset($_SESSION["isLoggin"]) || $_SESSION["isLoggin"] != "login") {
    header("Location: login.php");
    exit;
}

// 2. Hubungkan ke Database
include 'koneksi.php';

// 3. Query untuk mengambil data dari tabel santri
$query_santri = mysqli_query($koneksi, "SELECT * FROM santri ORDER BY id_santri DESC");
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Progres Santri - MSANTRI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Asumsi struktur file: Sesuaikan path CSS dan Image -->
    <link rel="icon" type="image/png" href="../../images/lg.jpeg">    
    <link rel="stylesheet" href="../style/admin_santri.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .modal-overlay {
            display: none;
        }
        .modal-overlay.show {
            display: flex !important;
        }
        /* Style tambahan untuk foto profil di Modal */
        .foto-profil-santri {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #10b981;
            margin: 0 auto 15px auto;
            display: block;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="../../images/logo.png" alt="Logo" width="40px">
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
        <a href="admin_santri.php" class="ini-nav active">
          <i class="fa-solid fa-book-open-reader"></i>
          <span>Progres Santri</span>
        </a>
        <a href="admin_info.php" class="ini-nav">
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
            <div><img src="../../images/profil.jpeg" alt="" width="40px" style="border-radius: 50%;"></div>
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
            <h1>Data & Progres Santri</h1>
            <p>Kelola capaian hafalan dan berikan catatan khusus untuk wali santri di rumah.</p>
        </div>

        <div class="dashboard">
            <div class="card card-full">
                <div class="kepala-kotak">
                    <div class="cari-grup">
                        <i class="fa-solid fa-search"></i>
                        <input type="text" id="inputCari" onkeyup="cariSantri()" placeholder="Ketik nama santri..." />
                    </div>
                    <button class="tombol-tambah" onclick="bukaModalTambah()">
                        <i class="fa-solid fa-plus"></i> Tambah Data Santri
                    </button>
                </div>

                <div class="tabel-responsif">
                    <table id="tabelSantri">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 25%">Nama Lengkap</th>
                                <th style="width: 20%">Capaian Terakhir</th>
                                <th style="width: 20%">Catatan Pengajar</th>
                                <th style="width: 10%">Kehadiran</th>
                                <th style="text-align: center; width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query_santri && mysqli_num_rows($query_santri) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($data = mysqli_fetch_assoc($query_santri)): ?>
                                    <?php
                                        // PENGAMANAN & PENGAMBILAN DATA
                                        $id_aman = htmlspecialchars($data['id_santri'] ?? '', ENT_QUOTES);
                                        $nama_aman = htmlspecialchars($data['nama_lengkap'] ?? '', ENT_QUOTES);
                                        $tempat_aman = htmlspecialchars($data['tempat_lahir'] ?? '', ENT_QUOTES);
                                        $tgl_aman = htmlspecialchars($data['tanggal_lahir'] ?? '', ENT_QUOTES);
                                        $alamat_aman = htmlspecialchars($data['alamat'] ?? '', ENT_QUOTES);
                                        $ortu_aman = htmlspecialchars($data['nama_ortu'] ?? '', ENT_QUOTES);
                                        $wa_aman = htmlspecialchars($data['no_wa_ortu'] ?? '', ENT_QUOTES);
                                        $capaian_aman = htmlspecialchars($data['capaian_hafalan'] ?? '', ENT_QUOTES);
                                        $catatan_aman = htmlspecialchars($data['catatan_pengajar'] ?? '', ENT_QUOTES);
                                        
                                        // Variabel foto (Jika kosong, gunakan default.png)
                                        $foto_db = !empty($data['foto']) ? htmlspecialchars($data['foto'], ENT_QUOTES) : 'default.png';
                                        $path_foto = "../../uploads/" . $foto_db; // Sesuaikan path folder uploadmu
                                    ?>
                                    <tr>
                                        <td><strong style="color: #0f172a"><?= $no++ ?></strong></td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <img src="<?= $path_foto ?>" alt="Foto" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                                                <strong style="color: #0f172a"><?= $nama_aman ?></strong>
                                            </div>
                                        </td>
                                        <td><?= $capaian_aman ?></td>
                                        
                                        <td class="catatan-teks">
                                            <?php if (!empty($data["catatan_pengajar"]) && $data["catatan_pengajar"] != "- Belum ada catatan -"): ?>
                                                <i class="fa-regular fa-comment-dots" style="color: #10b981; margin-right: 5px"></i>
                                                <?= $catatan_aman ?>
                                            <?php else: ?>
                                                <em style="color: #94a3b8;">- Belum ada catatan -</em>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="select-wrapper">
                                                <select class="status-select status-<?= $data['kehadiran'] ?>" 
                                                        data-id="<?= $id_aman ?>" 
                                                        onchange="ubahStatus(this)">
                                                    <option value="hadir" <?= $data['kehadiran'] == 'hadir' ? 'selected' : '' ?>>HADIR</option>
                                                    <option value="izin" <?= $data['kehadiran'] == 'izin' ? 'selected' : '' ?>>IZIN</option>
                                                    <option value="sakit" <?= $data['kehadiran'] == 'sakit' ? 'selected' : '' ?>>SAKIT</option>
                                                    <option value="alpha" <?= $data['kehadiran'] == 'alpha' ? 'selected' : '' ?>>ALPHA</option>
                                                </select>
                                            </div>
                                        </td>
                                        
                                        <td align="center">
                                            <div class="grup-aksi">
                                                <!-- Tombol Info (Tambah Data Foto) -->
                                                <button type="button" class="aksi-info" title="Lihat Biodata Lengkap" 
                                                        data-nama="<?= $nama_aman ?>"
                                                        data-tempat="<?= $tempat_aman ?>"
                                                        data-tgl="<?= $tgl_aman ?>"
                                                        data-alamat="<?= $alamat_aman ?>"
                                                        data-ortu="<?= $ortu_aman ?>"
                                                        data-wa="<?= $wa_aman ?>"
                                                        data-foto="<?= $path_foto ?>"
                                                        onclick="lihatBiodata(this)">
                                                    <i class="fa-solid fa-address-card"></i>
                                                </button>

                                                <!-- Tombol Edit -->
                                                <button type="button" class="aksi-edit" title="Edit Data" 
                                                        data-id="<?= $id_aman ?>"
                                                        data-nama="<?= $nama_aman ?>"
                                                        data-tempat="<?= $tempat_aman ?>"
                                                        data-tgl="<?= $tgl_aman ?>"
                                                        data-alamat="<?= $alamat_aman ?>"
                                                        data-ortu="<?= $ortu_aman ?>"
                                                        data-wa="<?= $wa_aman ?>"
                                                        data-capaian="<?= $capaian_aman ?>"
                                                        data-catatan="<?= $catatan_aman ?>"
                                                        onclick="bukaModalEdit(this)">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                
                                                <a href="hapus_santri.php?id=<?= $id_aman ?>" class="aksi-hapus" title="Hapus Data" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= addslashes($nama_aman) ?>?')" style="display:flex;">
                                                    <i class="fa-solid fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 30px; color: #94a3b8;">Data santri belum tersedia.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL BIODATA -->
    <div id="modalBiodata" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="font-family: 'Amiri', serif; font-size: 24px; color: #0f172a;">Biodata Santri</h2>
                <button class="close-btn" onclick="tutupModalBiodata()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- Tambahan Area Foto di Modal -->
            <img id="viewFoto" src="../../uploads/default.png" alt="Foto Santri" class="foto-profil-santri">
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                <label style="display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Nama Lengkap</label>
                <p id="viewNama" style="font-size: 15px; font-weight: 600; color: #1e293b;">-</p>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                <label style="display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Tempat, Tanggal Lahir</label>
                <p id="viewLahir" style="font-size: 15px; font-weight: 600; color: #1e293b;">-</p>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                <label style="display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Alamat Lengkap</label>
                <p id="viewAlamat" style="font-size: 15px; font-weight: 600; color: #1e293b;">-</p>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                <label style="display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Nama Orang Tua / Wali</label>
                <p id="viewOrtu" style="font-size: 15px; font-weight: 600; color: #1e293b;">-</p>
            </div>
            
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 12px; border: 1px solid #f1f5f9;">
                <label style="display: block; color: #94a3b8; font-size: 11px; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">No. WhatsApp Orang Tua</label>
                <p id="viewWaOrtu" style="font-size: 15px; font-weight: 600; color: #000000;">-</p>
            </div>
        </div>
    </div>
    
    <!-- MODAL TAMBAH/EDIT SANTRI (Ditambah enctype multipart/form-data) -->
    <div id="modalTambahSantri" class="modal-overlay">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2 id="judulModal">Tambah Santri Baru</h2>
                <button class="close-btn" onclick="tutupModalForm()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <!-- TAMBAH ENCTYPE UNTUK UPLOAD FILE -->
            <form action="simpan_santri.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_santri" id="inputIdSantri">
                
                <div class="grup-form-modal">
                    <label>Foto Profil (Opsional)</label>
                    <input type="file" name="foto" id="inputFoto" accept="image/png, image/jpeg, image/jpg" style="padding: 10px; border: 1px solid #ccc; width: 100%; border-radius: 8px;">
                    <small style="color: #64748b;">Abaikan jika tidak ingin mengubah foto. Maks 2MB.</small>
                </div>

                <div class="grup-form-modal">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="inputNama" placeholder="Masukkan nama santri..." required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="grup-form-modal">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="inputTempatLahir" placeholder="Contoh: Masohi">
                    </div>
                    <div class="grup-form-modal">
                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="inputTanggalLahir">
                    </div>
                </div>

                <div class="grup-form-modal">
                    <label>Alamat Lengkap</label>
                    <textarea name="alamat" id="inputAlamat" rows="2" placeholder="Masukkan alamat lengkap..."></textarea>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="grup-form-modal">
                        <label>Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_ortu" id="inputNamaOrtu" placeholder="Masukkan nama orang tua...">
                    </div>
                    
                    <div class="grup-form-modal">
                        <label>No. WhatsApp Orang Tua</label>
                        <input type="text" name="no_wa_ortu" id="inputWaOrtu" placeholder="Contoh: 081234567890">
                    </div>
                </div>

                <div class="grup-form-modal">
                    <label>Capaian Hafalan / Jilid</label>
                    <input type="text" name="capaian" id="inputCapaian" placeholder="Contoh: Jilid 3 - Hal 15" required>
                </div>
                <div class="grup-form-modal">
                    <label>Catatan Pengajar (Opsional)</label>
                    <textarea name="catatan" id="inputCatatan" rows="3" placeholder="Tambahkan pesan untuk wali santri di rumah..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-batal" onclick="tutupModalForm()">Batal</button>
                    <button type="submit" class="btn-simpan"><i class="fa-solid fa-floppy-disk"></i> <span id="teksTombol">Simpan Data</span></button>
                </div>
            </form> 
        </div>
    </div>

    <script src="../script/admin-santri.js"></script>
    <!-- Sweet Alert -->
       <?php if (isset($_GET['status'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if ($_GET['status'] == 'success'): ?>
                Swal.fire({
                    title: 'Berhasil!',
                    text: '<?= isset($_SESSION['alert_message']) ? $_SESSION['alert_message'] : "Data santri berhasil disimpan" ?>',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    showConfirmButton: true
                });
                <?php unset($_SESSION['alert_message']); ?>
                <?php endif; ?>
            });
        </script> 
    <?php endif; ?>
</body>
</html>