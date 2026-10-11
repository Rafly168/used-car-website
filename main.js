document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // 1. EFEK SCROLL HEADER & MOBILE MENU TOGGLE
    // ==========================================
    const header = document.querySelector('header');
    const menu = document.querySelector('#menu-icon');
    const navbar = document.querySelector('.navbar');

    // Toggle menu navigasi di tampilan HP/Mobile
    if (menu) {
        menu.onclick = () => {
            menu.classList.toggle('bx-x');
            if (navbar) navbar.classList.toggle('active');
        };
    }

    // Menambahkan kelas .shadow saat halaman di-scroll
    window.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('shadow', window.scrollY > 0);
        }
        if (menu && navbar) {
            menu.classList.remove('bx-x');
            navbar.classList.remove('active');
        }
    });

    // ==========================================
    // 2. LOGIKA PENCARIAN AJAX (NO-REFRESH)
    // ==========================================
    const searchForm = document.getElementById('ajax-search-form');
    const searchInput = document.getElementById('search-input');
    const btnReset = document.getElementById('btn-reset-search');
    const partsContainer = document.getElementById('parts-container');

    // Jalankan fungsi AJAX hanya jika elemen form pencarian ditemukan di halaman
    if (searchForm && partsContainer) {

        // Fungsi mengambil data dari search_ajax.php tanpa re-load halaman
        function fetchCars(keyword = '') {
            fetch('search_ajax.php?search=' + encodeURIComponent(keyword))
                .then(response => response.text())
                .then(data => {
                    partsContainer.innerHTML = data;

                    // Tampilkan atau sembunyikan tombol Reset
                    if (btnReset) {
                        btnReset.style.display = keyword.trim() !== '' ? 'inline-flex' : 'none';
                    }

                    // Tambahkan event listener pada tombol "Tampilkan Semua" jika kata kunci tidak ditemukan
                    const btnShowAll = document.getElementById('btn-show-all');
                    if (btnShowAll) {
                        btnShowAll.addEventListener('click', resetSearch);
                    }
                })
                .catch(error => console.error('Error saat memuat data pencarian:', error));
        }

        // Fungsi untuk mengosongkan pencarian (Reset)
        function resetSearch() {
            if (searchInput) searchInput.value = '';
            fetchCars('');
        }

        // Event saat form pencarian di-submit (tombol Cari / Enter)
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman dan pergeseran scroll
            fetchCars(searchInput.value);
        });

        // Event saat tombol Reset diklik
        if (btnReset) {
            btnReset.addEventListener('click', resetSearch);
        }
    }
});