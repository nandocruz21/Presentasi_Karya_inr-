function filterSantri() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let cards = document.getElementsByClassName('santri-item');
        let emptyState = document.getElementById('emptyState');
        let foundCount = 0;

        // Jika kotak pencarian kosong, sembunyikan semua kartu
        if(input === "") {
            for (let i = 0; i < cards.length; i++) { cards[i].style.display = "none"; }
            emptyState.style.display = "none";
            return; // Berhenti di sini
        }

        // Jika ada ketikan, mulai cari
        for (let i = 0; i < cards.length; i++) {
          let nameElement = cards[i].querySelector('.santri-name');
          let nameText = nameElement.innerText.toLowerCase();

          if (nameText.includes(input)) {
            cards[i].style.display = "flex"; // Tampilkan jika cocok
            foundCount++;
          } else {
            cards[i].style.display = "none"; // Sembunyikan jika tidak cocok
          }
        }

        // Munculkan peringatan "Tidak ditemukan" jika foundCount = 0
        if (foundCount === 0) {
          emptyState.style.display = "block";
        } else {
          emptyState.style.display = "none";
        }
      }