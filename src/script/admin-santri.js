function bukaModalTambah() {
  document.getElementById('judulModal').innerText = "Tambah Santri Baru";
  document.getElementById('teksTombol').innerText = "Simpan Data";
  document.getElementById('inputIdSantri').value = ""; // Kosongkan ID agar dianggap data baru
  document.getElementById('inputNama').value = "";
  document.getElementById('inputTempatLahir').value = "";
  document.getElementById('inputTanggalLahir').value = "";
  document.getElementById('inputAlamat').value = "";
  document.getElementById('inputNamaOrtu').value = "";
  document.getElementById('inputCapaian').value = "";
  document.getElementById('inputCatatan').value = "";
  document.getElementById('modalTambahSantri').classList.add('show');
}

// Menyiapkan modal untuk mode Edit (Mengisi data lama ke dalam form)
function bukaModalEdit(id, nama, tempat, tgl, alamat, ortu, capaian, catatan) {
  document.getElementById('judulModal').innerText = "Edit Data Santri";
  document.getElementById('teksTombol').innerText = "Simpan Perubahan";
  document.getElementById('inputIdSantri').value = id; // Isi ID agar simpan_santri melakukan UPDATE
  document.getElementById('inputNama').value = nama;
  document.getElementById('inputTempatLahir').value = tempat || "";
  document.getElementById('inputTanggalLahir').value = tgl || "";
  document.getElementById('inputAlamat').value = alamat || "";
  document.getElementById('inputNamaOrtu').value = ortu || "";
  document.getElementById('inputCapaian').value = capaian;
  document.getElementById('inputCatatan').value = catatan;
  document.getElementById('modalTambahSantri').classList.add('show');
}

function tutupModalForm() {
  document.getElementById('modalTambahSantri').classList.remove('show');
}

function ubahStatus(selectElement) {
  // 1. Ubah warna kotak secara visual
  selectElement.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpha');
  
  let statusBaru = selectElement.value;
  selectElement.classList.add('status-' + statusBaru);

  // 2. Ambil ID KTP dan Status yang baru dipilih
  let idSantri = selectElement.getAttribute('data-id');

  // 3. Kirim kurir (AJAX) ke update_status.php secara diam-diam
  fetch('update_status.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'id=' + idSantri + '&status=' + statusBaru
  })
  .then(response => response.text())
  .then(data => {
      // Cek balasan dari PHP
      if (data.trim() === "Berhasil diupdate") {
          console.log("Mantap: Status berhasil disimpan ke database!");
      } else {
          console.error("Gagal: " + data);
      }
  })
  .catch(error => {
      console.error("Kesalahan koneksi:", error);
      alert("Terjadi kesalahan jaringan, status gagal disimpan!");
  });
}

// Fungsi pencarian nama santri di tabel (Frontend Only)
function cariSantri() {
  const input = document.getElementById("inputCari").value.toUpperCase();
  const table = document.getElementById("tabelSantri");
  const tr = table.getElementsByTagName("tr");

  for (let i = 1; i < tr.length; i++) {
    let td = tr[i].getElementsByTagName("td")[1]; // Kolom Nama
    if (td) {
      let txtValue = td.textContent || td.innerText;
      tr[i].style.display = txtValue.toUpperCase().indexOf(input) > -1 ? "" : "none";
    }
  }
}

function toggleSidebar() {
  document.querySelector('.sidebar').classList.toggle('show');
}

// Fungsi agar sidebar otomatis menghilang setelah menu (navigasi) diklik di HP
document.querySelectorAll('.ini-nav').forEach(link => {
  link.addEventListener('click', () => {
      if (window.innerWidth <= 768) {
          document.querySelector('.sidebar').classList.remove('show');
      }
  });
});

// --- FUNGSI BARU UNTUK BIODATA ---

// Fungsi memunculkan modal Lihat Biodata
function lihatBiodata(nama, tempat, tgl, alamat, ortu) {
  document.getElementById('viewNama').innerText = nama || '-';
  
  let tglLahir = (tgl && tgl !== '0000-00-00') ? tgl : '-';
  let tmptLahir = tempat || '-';
  document.getElementById('viewLahir').innerText = tmptLahir + ', ' + tglLahir;
  
  document.getElementById('viewAlamat').innerText = alamat || '-';
  document.getElementById('viewOrtu').innerText = ortu || '-';
  
  // Tampilkan modal
  document.getElementById('modalBiodata').classList.add('show');
}

// Fungsi tutup modal Biodata
function tutupModalBiodata() {
  document.getElementById('modalBiodata').classList.remove('show');
}

// Jika user klik di luar area kotak putih modal, modal akan tertutup
window.onclick = function(event) {
  let modals = document.querySelectorAll('.modal-overlay');
  modals.forEach(function(modal) {
      if (event.target === modal) {
          modal.classList.remove('show');
      }
  });
}