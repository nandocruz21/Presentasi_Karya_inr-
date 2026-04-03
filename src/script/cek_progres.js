        // --- JS MODAL & PENCARIAN YANG SUDAH DISEDERHANAKAN (ANTI BENTROK) ---

        const modal = document.getElementById('modalBiodata');

        // Fungsi membuka modal (Membaca dari atribut kartu yang diklik)
        function lihatBiodataUser(card) {
            document.getElementById('bioNama').innerText = card.getAttribute('data-nama') || '-';
            document.getElementById('bioTTL').innerText = card.getAttribute('data-ttl') || '-';
            document.getElementById('bioAlamat').innerText = card.getAttribute('data-alamat') || '-';
            document.getElementById('bioOrtu').innerText = card.getAttribute('data-ortu') || '-';
            document.getElementById('bioWA').innerText = card.getAttribute('data-wa') || '-';
            
            modal.style.display = 'flex';
        }

        // Fungsi menutup modal
        function tutupBiodata() {
            modal.style.display = 'none';
        }

        // Tutup modal jika klik di luar area konten
        window.onclick = function(e) { 
            if (e.target === modal) {
                tutupBiodata();
            }
        };

        // Fungsi Filter Pencarian
        function filterSantri() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const items = document.querySelectorAll('.santri-item');
            let hasResults = false;

            items.forEach(item => {
                const name = item.querySelector('.santri-name').innerText.toLowerCase();
                const isMatch = name.includes(input);
                
                item.style.display = isMatch ? 'flex' : 'none';
                if (isMatch) hasResults = true;
            });

            document.getElementById('emptyState').style.display = hasResults ? 'none' : 'block';
        }
