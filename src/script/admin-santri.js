function bukaModalTambah() {
  document.getElementById('judulModal').innerText = "Tambah Santri Baru";
  document.getElementById('teksTombol').innerText = "Simpan Data";
  document.getElementById('inputIdSantri').value = ""; // Kosongkan ID agar dianggap data baru
  document.getElementById('inputNama').value = "";
  document.getElementById('inputTempatLahir').value = "";
  document.getElementById('inputTanggalLahir').value = "";
  document.getElementById('inputAlamat').value = "";
  document.getElementById('inputNamaOrtu').value = "";
  document.getElementById('inputWa').value = ""; // Tambahan WA
  document.getElementById('inputCapaian').value = "";
  document.getElementById('inputCatatan').value = "";
  document.getElementById('modalTambahSantri').classList.add('show');
  // Jika sebelumnya error tidak mau muncul, pastikan CSS Anda menggunakan class .show { display: flex !important; }
}

// Menyiapkan modal untuk mode Edit (Menggunakan data-attribute agar ANTI-ERROR)
function bukaModalEdit(btn) {
  document.getElementById('judulModal').innerText = "Edit Data Santri";
  document.getElementById('teksTombol').innerText = "Simpan Perubahan";
  
  document.getElementById('inputIdSantri').value = btn.getAttribute('data-id');
  document.getElementById('inputNama').value = btn.getAttribute('data-nama');
  document.getElementById('inputTempatLahir').value = btn.getAttribute('data-tempat');
  document.getElementById('inputTanggalLahir').value = btn.getAttribute('data-tgl');
  document.getElementById('inputAlamat').value = btn.getAttribute('data-alamat');
  document.getElementById('inputNamaOrtu').value = btn.getAttribute('data-ortu');
  document.getElementById('inputWa').value = btn.getAttribute('data-wa'); // Tambahan WA
  document.getElementById('inputCapaian').value = btn.getAttribute('data-capaian');
  document.getElementById('inputCatatan').value = btn.getAttribute('data-catatan');
  
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
          console.error("Respon dari server: " + data);
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

// --- FUNGSI BIODATA (MENGGUNAKAN DATA-ATTRIBUTE AGAR ANTI-ERROR) ---

function lihatBiodata(btn) {
  document.getElementById('viewNama').innerText = btn.getAttribute('data-nama') || '-';
  
  let tempat = btn.getAttribute('data-tempat');
  let tgl = btn.getAttribute('data-tgl');
  let tglLahir = (tgl && tgl !== '0000-00-00') ? tgl : '-';
  let tmptLahir = tempat || '-';
  document.getElementById('viewLahir').innerText = tmptLahir + ', ' + tglLahir;
  
  document.getElementById('viewAlamat').innerText = btn.getAttribute('data-alamat') || '-';
  document.getElementById('viewOrtu').innerText = btn.getAttribute('data-ortu') || '-';
  document.getElementById('viewWaOrtu').innerText = btn.getAttribute('data-wa') || '-'; // Tambahan WA
  
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