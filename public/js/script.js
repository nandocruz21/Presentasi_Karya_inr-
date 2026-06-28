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
    
      // SCRIPT AUTO SCROLL GALERI (DIHAPUS)
      // Karena Galeri sekarang menggunakan model CSS Grid Wall statis, script auto-scroll JS dimatikan agar tidak menduplikasi data foto.

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
      
// MODAL ULASAN FRONTEND
function bukaModalUlasan() {
  const modal = document.getElementById('modalUlasan');
  if(modal) modal.classList.add('show');
}
function tutupModalUlasan() {
  const modal = document.getElementById('modalUlasan');
  if(modal) modal.classList.remove('show');
}
// Tutup jika klik di luar modal
window.addEventListener('click', function(e) {
  const modal = document.getElementById('modalUlasan');
  if(e.target === modal) {
    tutupModalUlasan();
  }
});