// ==========================================================
// FUNGSI 1: MODAL TAMBAH & EDIT DATA SANTRI
// ==========================================================

function bukaModalTambah() {
    document.getElementById('judulModal').innerText = "Tambah Santri Baru";
    document.getElementById('teksTombol').innerText = "Simpan Data";
    
    // Kosongkan form agar siap untuk data baru
    document.getElementById('inputIdSantri').value = ""; 
    document.getElementById('inputNama').value = "";
    document.getElementById('inputTempatLahir').value = "";
    document.getElementById('inputTanggalLahir').value = "";
    document.getElementById('inputAlamat').value = "";
    document.getElementById('inputNamaOrtu').value = "";
    
    // Cek ketersediaan elemen WA dan Foto agar tidak error jika tidak ada di HTML
    let inputWa = document.getElementById('inputWaOrtu') || document.getElementById('inputWa');
    if(inputWa) inputWa.value = "";
    
    let inputFoto = document.getElementById('inputFoto');
    if(inputFoto) inputFoto.value = ""; 

    document.getElementById('inputCapaian').value = "";
    document.getElementById('inputCatatan').value = "";
    
    document.getElementById('modalTambahSantri').classList.add('show');
}

function bukaModalEdit(btn) {
    document.getElementById('judulModal').innerText = "Edit Data Santri";
    document.getElementById('teksTombol').innerText = "Simpan Perubahan";
    
    // Ambil data dari atribut tombol (data-id, data-nama, dll)
    document.getElementById('inputIdSantri').value = btn.getAttribute('data-id');
    document.getElementById('inputNama').value = btn.getAttribute('data-nama');
    document.getElementById('inputTempatLahir').value = btn.getAttribute('data-tempat');
    document.getElementById('inputTanggalLahir').value = btn.getAttribute('data-tgl');
    document.getElementById('inputAlamat').value = btn.getAttribute('data-alamat');
    document.getElementById('inputNamaOrtu').value = btn.getAttribute('data-ortu');
    
    let inputWa = document.getElementById('inputWaOrtu') || document.getElementById('inputWa');
    if(inputWa) inputWa.value = btn.getAttribute('data-wa');
    
    document.getElementById('inputCapaian').value = btn.getAttribute('data-capaian');
    document.getElementById('inputCatatan').value = btn.getAttribute('data-catatan');
    
    let inputFoto = document.getElementById('inputFoto');
    if(inputFoto) inputFoto.value = ""; // Foto tidak wajib diisi saat edit
    
    document.getElementById('modalTambahSantri').classList.add('show');
}

function tutupModalForm() {
    document.getElementById('modalTambahSantri').classList.remove('show');
}


// ==========================================================
// FUNGSI 2: MODAL BIODATA LENGKAP
// ==========================================================

function lihatBiodata(btn) {
    let viewFoto = document.getElementById('viewFoto');
    if(viewFoto) {
        viewFoto.src = btn.getAttribute('data-foto') || '../public/uploads/default.png';
    }

    document.getElementById('viewNama').innerText = btn.getAttribute('data-nama') || '-';
    
    let tempat = btn.getAttribute('data-tempat');
    let tgl = btn.getAttribute('data-tgl');
    let tglLahir = (tgl && tgl !== '0000-00-00') ? tgl : '-';
    let tmptLahir = tempat || '-';
    
    document.getElementById('viewLahir').innerText = tmptLahir + ', ' + tglLahir;
    document.getElementById('viewAlamat').innerText = btn.getAttribute('data-alamat') || '-';
    document.getElementById('viewOrtu').innerText = btn.getAttribute('data-ortu') || '-';
    
    let viewWa = document.getElementById('viewWaOrtu') || document.getElementById('viewWa');
    if(viewWa) viewWa.innerText = btn.getAttribute('data-wa') || '-';
    
    document.getElementById('modalBiodata').classList.add('show');
}

function tutupModalBiodata() {
    document.getElementById('modalBiodata').classList.remove('show');
}


// ==========================================================
// FUNGSI 3: AJAX UPDATE STATUS KEHADIRAN
// ==========================================

function ubahStatus(selectElement) {
    // Ubah warna tampilan select (dropdown)
    selectElement.classList.remove('status-hadir', 'status-izin', 'status-sakit', 'status-alpha');
    let statusBaru = selectElement.value;
    selectElement.classList.add('status-' + statusBaru);

    // Ambil ID
    let idSantri = selectElement.getAttribute('data-id');

    // Kirim data ke PHP secara background
    fetch('../backend/update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + idSantri + '&status=' + statusBaru
    })
    .then(response => response.text())
    .then(data => {
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


// ==========================================================
// FUNGSI 4: PENCARIAN NAMA SANTRI
// ==========================================================

function cariSantri() {
    const input = document.getElementById("inputCari");
    const table = document.getElementById("tabelSantri");
    
    // Mencegah error jika id input/tabel tidak ada di halaman
    if(!input || !table) return; 

    const filter = input.value.toUpperCase();
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1]; // Asumsi kolom nama ada di index 1 (Kolom ke-2)
        if (td) {
            let txtValue = td.textContent || td.innerText;
            tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}


// ==========================================================
// FUNGSI 5: SIDEBAR HAMBURGER MENU & EVENT KLIK LUAR
// ==========================================================

// Fungsi membuka/menutup Sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('show');
    } else {
        console.error("Peringatan: Elemen '.sidebar' tidak ditemukan di halaman ini!");
    }
}

// Tutup modal jika user klik area hitam/gelap (overlay) di luar modal
window.onclick = function(event) {
    let modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(function(modal) {
        if (event.target === modal) {
            modal.classList.remove('show');
        }
    });
}

// Tutup sidebar otomatis di HP jika menu diklik
document.querySelectorAll('.ini-nav, .nav-item').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            const sidebar = document.querySelector('.sidebar');
            if(sidebar) sidebar.classList.remove('show');
        }
    });
});