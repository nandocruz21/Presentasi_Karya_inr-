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
    
    <link rel="stylesheet" href="../style/admin_santri.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
<body>
    <aside class="sidebar">
      <div class="sidebar-header">
        <img src="../../gambar/logo.png" alt="Logo">
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
                                <th style="width: 20%">Nama Lengkap</th>
                                <th style="width: 20%">Capaian Terakhir</th>
                                <th style="width: 25%">Catatan Pengajar</th>
                                <th style="width: 10%">Kehadiran</th>
                                <th style="text-align: center; width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query_santri && mysqli_num_rows($query_santri) > 0): ?>
                                <?php $no = 1; ?>
                                <?php while ($data = mysqli_fetch_assoc($query_santri)): ?>
                                    <tr>
                                        <td><strong style="color: #0f172a"><?= $no++ ?></strong></td>
                                        <td><strong style="color: #0f172a"><?= htmlspecialchars($data["nama_lengkap"]) ?></strong></td>
                                        <td><?= htmlspecialchars($data["capaian_hafalan"]) ?></td>
                                        
                                        <td class="catatan-teks">
                                            <?php if (!empty($data["catatan_pengajar"]) && $data["catatan_pengajar"] != "- Belum ada catatan -"): ?>
                                                <i class="fa-regular fa-comment-dots" style="color: #10b981; margin-right: 5px"></i>
                                                <?= htmlspecialchars($data["catatan_pengajar"]) ?>
                                            <?php else: ?>
                                                <em style="color: #94a3b8;">- Belum ada catatan -</em>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <div class="select-wrapper">
                                                <select class="status-select status-<?= $data['kehadiran'] ?>" 
                                                        data-id="<?= $data['id_santri'] ?>" 
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
                                                <button type="button" class="aksi-info" title="Lihat Biodata Lengkap" 
                                                        onclick='lihatBiodata(<?= json_encode($data["nama_lengkap"] ?? "") ?>, <?= json_encode($data["tempat_lahir"] ?? "") ?>, <?= json_encode($data["tanggal_lahir"] ?? "") ?>, <?= json_encode($data["alamat"] ?? "") ?>, <?= json_encode($data["nama_ortu"] ?? "") ?>)'>
                                                    <i class="fa-solid fa-address-card"></i>
                                                </button>

                                                <button class="aksi-edit" title="Edit Data" 
                                                        onclick="bukaModalEdit('<?= $data['id_santri'] ?>', '<?= addslashes($data['nama_lengkap']) ?>', '<?= addslashes($data['tempat_lahir'] ?? '') ?>', '<?= addslashes($data['tanggal_lahir'] ?? '') ?>', '<?= addslashes($data['alamat'] ?? '') ?>', '<?= addslashes($data['nama_ortu'] ?? '') ?>', '<?= addslashes($data['capaian_hafalan']) ?>', '<?= addslashes($data['catatan_pengajar']) ?>')">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                
                                                <a href="hapus_santri.php?id=<?= $data['id_santri'] ?>" class="aksi-hapus" title="Hapus Data" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= addslashes($data['nama_lengkap']) ?>?')" style="display:flex;">
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

    <div id="modalBiodata" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="font-family: 'Amiri', serif; font-size: 24px; color: #0f172a;">Biodata Santri</h2>
                <button class="close-btn" onclick="tutupModalBiodata()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
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
        </div>
    </div>
    
    <div id="modalTambahSantri" class="modal-overlay">
        <div class="modal-content" style="max-height: 90vh; overflow-y: auto;">
            <div class="modal-header">
                <h2 id="judulModal">Tambah Santri Baru</h2>
                <button class="close-btn" onclick="tutupModalForm()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="simpan_santri.php" method="POST">
                <input type="hidden" name="id_santri" id="inputIdSantri">
                
                <div class="grup-form-modal">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="inputNama" placeholder="Masukkan nama santri..." required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="grup-form-modal">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="inputTempatLahir" placeholder="Contoh: Makassar">
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
                <div class="grup-form-modal">
                    <label>Nama Orang Tua / Wali</label>
                    <input type="text" name="nama_ortu" id="inputNamaOrtu" placeholder="Masukkan nama orang tua...">
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