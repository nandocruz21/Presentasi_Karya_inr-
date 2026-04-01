function editInfo(id, judul, isi) {
        // Isi form dengan data yang mau diedit
        document.getElementById('inputIdInfo').value = id;
        document.getElementById('inputJudul').value = judul;
        document.getElementById('inputIsi').value = isi;
        
        // Ubah teks tombol dan tampilkan tombol batal
        document.getElementById('btnSimpan').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan';
        document.getElementById('btnBatalEdit').style.display = 'inline-flex';
        
        // Scroll layar ke form
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      function batalEdit() {
        // Kosongkan form kembali ke mode Tambah Baru
        document.getElementById('inputIdInfo').value = '';
        document.getElementById('inputJudul').value = '';
        document.getElementById('inputIsi').value = '';
        
        // Kembalikan tombol seperti semula
        document.getElementById('btnSimpan').innerHTML = '<i class="fa-solid fa-paper-plane"></i> Terbitkan Sekarang';
        document.getElementById('btnBatalEdit').style.display = 'none';
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

        // Fungsi agar sidebar tertutup jika klik di luar sidebar (di HP)
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const hamburger = document.querySelector('.btn-hamburger');

            // Hanya proses jika layar sedang dalam mode HP
            if (window.innerWidth <= 768) {
                // Pastikan yang diklik bukan sidebar dan bukan tombol hamburger
                if (sidebar && hamburger && !sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                    if (sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            }
        });