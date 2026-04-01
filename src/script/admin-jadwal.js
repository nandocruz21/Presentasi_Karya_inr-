
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
