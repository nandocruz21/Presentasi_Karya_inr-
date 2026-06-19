     window.addEventListener("scroll", function () {
        const header = document.querySelector("header");
        header.classList.toggle("scrolled", window.scrollY > 50);
      });

        // 2. Animasi Scroll Buatan Sendiri (Super Mulus seperti memutar roda mouse)
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault(); // Mencegah loncat kasar bawaan HTML

          const targetId = this.getAttribute('href');
          if(targetId === '#') return;

          const targetElement = document.querySelector(targetId);

          if (targetElement) {
            const headerHeight = document.querySelector('header').offsetHeight;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
            const startPosition = window.pageYOffset;
            const distance = targetPosition - startPosition;
            
            // Waktu tempuh meluncur (800ms = 0.8 detik). Bisa kamu ubah kalau mau lebih lambat/cepat
            const duration = 800; 
            let start = null;

            // Fungsi penggerak animasi (Easing EaseInOutCubic)
            function step(timestamp) {
              if (!start) start = timestamp;
              const progress = timestamp - start;
              
              // Rumus matematika agar awalnya pelan, tengahnya cepat, akhirnya pelan (sangat natural)
              const easeInOutCubic = progress < duration / 2 
                ? 4 * Math.pow(progress / duration, 3) 
                : 1 - Math.pow(-2 * (progress / duration) + 2, 3) / 2;
              
              window.scrollTo(0, startPosition + distance * easeInOutCubic);
              
              if (progress < duration) {
                window.requestAnimationFrame(step);
              } else {
                // Memastikan posisi akhir benar-benar pas saat animasi selesai
                window.scrollTo(0, targetPosition);
              }
            }
            
            // Mulai animasi
            window.requestAnimationFrame(step);
          }
        });
      });

       // SCRIPT HAMBURGER MENU 

      function toggleNav() {
        document.getElementById('navMenu').classList.toggle('show');
        // Jika header belum punya class scrolled tapi menu dibuka, beri background putih
        const header = document.querySelector('header');
        if(!header.classList.contains('scrolled')){
          header.style.backgroundColor = 'rgba(255, 255, 255, 1)';
        }
      }

      // Menutup menu drop-down ketika salah satu link diklik
      document.querySelectorAll('#navMenu a').forEach(link => {
        link.addEventListener('click', () => {
          document.getElementById('navMenu').classList.remove('show');
        });
      });

    //SCRIPT AUTO SCROLL GALERI (MELINGKAR TANPA JEDA) 
    
      document.addEventListener("DOMContentLoaded", function() {
        const galeriContainer = document.querySelector('.gambar-utama');

        // Pastikan galerinya ada dan memiliki isi foto
        if(galeriContainer && galeriContainer.children.length > 0) {
          
          // Override CSS bawaan yang bisa membuat scroll tersendat
          galeriContainer.style.scrollBehavior = 'auto';
          galeriContainer.style.scrollSnapType = 'none';
          
          // Menggandakan (duplikat) isi galeri dan memasukkannya ke container
          // Ini trik utama agar fotonya bisa terus menyambung ke foto pertama secara mulus
          const galeriIsiAwal = galeriContainer.innerHTML;
          galeriContainer.innerHTML += galeriIsiAwal; 
          
          let scrollSpeed = 1; // Kecepatan scroll (1 pixel per frame)
          let isHovered = false;
          let animationId;

          // Fungsi yang dipanggil terus-menerus oleh browser sehalus 60 frame per detik
          function autoScrollLoop() {
            if (!isHovered) {
              galeriContainer.scrollLeft += scrollSpeed;
              
              // Jika scroll sudah mencapai setengah dari lebar keseluruhan (yaitu panjang galeri awal),
              // reset posisinya kembali ke 0 dengan seketika. Karena gambarnya duplikat, tidak akan ada lompatan visual.
              if (galeriContainer.scrollLeft >= galeriContainer.scrollWidth / 2) {
                galeriContainer.scrollLeft = 0;
              }
            }
            // Ulangi animasi di frame berikutnya
            animationId = requestAnimationFrame(autoScrollLoop);
          }

          // Memulai animasi melingkar
          autoScrollLoop();

          // Memberhentikan scroll sementara jika pengunjung mengarahkan mouse (PC)
          galeriContainer.addEventListener('mouseenter', () => isHovered = true);
          galeriContainer.addEventListener('mouseleave', () => isHovered = false);

          // Memberhentikan scroll sementara jika pengunjung menahan/menyentuh layar (HP)
          galeriContainer.addEventListener('touchstart', () => isHovered = true);
          galeriContainer.addEventListener('touchend', () => isHovered = false);
        }
      });

      document.addEventListener("DOMContentLoaded", function() {
       const observerOptions = {
        threshold: 0.2 // Animasi jalan jika 20% elemen sudah masuk layar
       };

      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add("muncul");
          } else {
            // Baris ini akan menghapus class saat elemen keluar dari layar
            entry.target.classList.remove("muncul"); 
          }
        });
      }, observerOptions);

      // Cari semua elemen yang ingin diberi animasi
      const elements = document.querySelectorAll(".scroll-animasi");
        elements.forEach((el) => observer.observe(el));
      });
    