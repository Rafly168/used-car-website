<?php
include 'koneksi.php';

// Logika Filter Pencarian
$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($koneksi, trim($_GET['search']));
    $query_cars = "SELECT * FROM cars WHERE title LIKE '%$search%' OR description LIKE '%$search%' ORDER BY id DESC";
} else {
    $query_cars = "SELECT * FROM cars ORDER BY id DESC";
}

$result_cars = mysqli_query($koneksi, $query_cars);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Used Truck Sales Website</title>
    <!-- Link to CSS -->
    <link rel="stylesheet" href="style.css">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head> 
<body>
    <!-- NAVBAR -->
    <header>
        <!-- Nav Container -->
        <div class="nav container">
            <!-- menu icon -->
            <i class='bx bx-menu' id="menu-icon"></i>
            <!-- Logo -->
            <a href="#" class="logo">UniCar<span>Trucks</span></a>
            <!-- Nav List -->
            <ul class="navbar">
                <li><a href="#home">Home</a></li>
                <li><a href="#product">Product</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#parts">Parts</a></li>
                <li><a href="#blog">Blog Kami</a></li>
            </ul>
         
        </div>
    </header>
    <!-- Home -->
    <section class="home" id="home">
        <div class="home-text">
            <h1>We Have Large Selection <br>Of <span>Trucks and Cars</span></h1>
            <p>betul! kami mengumpulkan stock terbaik dari Jepang! <br> Gakbisa nemu kendaraan impian? tenang! <br> kita bisa carikan satu yang sesuai dengan anda!</p>
            <!-- Home Button -->
            <a href="#" class="btn">Discover Now</a>
        </div>
    </section>
    <!-- Kar seksyen -->
    <section class="product" id="product">
        <div class="heading">
            <span>All Product</span>
            <h2>KAMI MEMILIKI BANYAK PILIHAN KENDARAAN</h2>
            <p>Berikut adalah beberapa contoh kendaraan yang sudah kami jual kepada Pelanggan!</p>
        </div>
        <!-- produkt Kontainerz -->
        <div class="product-container container">
            <!-- boks 1 -->
            <div class="box">
                <img src="img/1586408131vWh9j.jpg" alt="">
                <h2>Hino Super Dolphin Profia KL-FN2PWGA</h2>
            </div>
            <!-- boks 2 -->
            <div class="box">
                <img src="img/1586408207ekRT_.jpg" alt="">
                <h2>Hino Profia BDG-FN1EYYA</h2>
            </div>
            <!-- boks 3 -->
            <div class="box">
                <img src="img/1586408293yfi6c.jpg" alt="">
                <h2>Hino Profia PK-FN2PWJA</h2>
            </div>
            <!-- boks 4 -->
            <div class="box">
                <img src="img/1586408345K8vce.jpg" alt="">
                <h2>Hino Profia BDG-FW1EXYG</h2>
            </div>
            <!-- boks 5 -->
            <div class="box">
                <img src="img/1586408530zKN4_.jpg" alt="">
                <h2>Hino Profia KS-FN2PWJA</h2>
            </div>
            <!-- boks 6 -->
            <div class="box">
                <img src="img/1635476360X4htT.jpg" alt="">
                <h2>Fuso Super Great KC-FT517NY</h2>
            </div>
            <!-- boks 7 -->
            <div class="box">
                <img src="img/dyJ2O5ZnVGaLpjCgSr4JtiY28hqUMqvAysgsJZVsYi.jpg" alt="">
                <h2>2022 Honda CR-V EX</h2>
            </div>
            <!-- boks 8 -->
            <div class="box">
                <img src="img/dyJ2O5ZnVGaLpjCgSr4JtiY28hqUmulDbD5kpwuhO1.jpg" alt="">
                <h2>2023 Toyota Prius Hybrid Z</h2>
            </div>
            <!-- boks 9 -->
            <div class="box">
                <img src="img/dyJ2O5ZnVGaLpjCgSr4JtiY28hqUmulDbDaWAqt0xD.jpg" alt="">
                <h2>2019 Toyota Crown S C Pack</h2>
            </div>
        </div>
    </section>
    <!-- tentang -->
    <section class="about container" id="about">
        <div class="about-img">
            <img src="img/tentang.jpg" alt="">
        </div>
        <div class="about-text">
            <span>Tentang Kita / About Us</span>
            <h2>Harga boleh murah <br> kualitas tetap nomor 1</h2>
            <p>pernah gak sih kalian terbesit "bisa gak ya guwe dapet truk bekas atau mobil bekas dengan harga murah tapi kualitas bagus?" well say no more! Kami ada disini untuk anda!</p>
            <!-- tombol aboutnya -->
            <a href="#" class="btn">Learn More</a>
        </div>
    </section>
<!-- spareparts section -->
<section class="parts" id="parts">
    <div class="heading">
        <span>Apa Yang Kami Tawarkan Untuk Anda</span>
        <h2>Kami Selalu Menawarkan Parts Dan Kendaraan Dengan Kondisi AAA</h2>
        <p>butuh sparepart atau kendaraan CBU utuh? gak usah khawatir! kami ada disini untuk memberikan stock dengan kondisi AAA!</p>
    </div>

    <!-- FORM PENCARIAN AJAX -->
    <div class="catalog-search-container">
        <form id="ajax-search-form" class="catalog-search-form">
            <i class='bx bx-search search-icon-input'></i>
            <input type="text" id="search-input" name="search" placeholder="Cari nama mobil/truk atau spesifikasi..." autocomplete="off">
            <button type="button" id="btn-reset-search" class="btn-reset-search" style="display: none;">
                <i class='bx bx-x'></i> Reset
            </button>
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>

    <!-- Parts Container (Isi card akan di-update oleh AJAX) -->
    <div class="parts-container container" id="parts-container">
        <?php include 'search_ajax.php'; ?>
    </div>
</section>
    <!-- Blog Containers -->
    <section class="blog" id="blog">
        <div class="heading">
            <span>Our Blog / Blog Kami</span>
            <h2>Our Blog Content</h2>
            <p>ini adalah konten dari blog kami</p>
        </div>
        <div class="blog-container container">
            <!-- Box 1 -->
            <div class="box">
                <img src="img/img220228_45fa76.jpg" alt="">
                <span>Januari 08 2024</span>
                <h3>Pengiriman Fuso The Great U-FV416JD kepada pelanggan</h3>
                <p>Pada Tanggal 7 Januari kemarin, Fuso The Great FV416JD dump telah mendarat sempurna di pelabuhan Indonesia Kendaraan Terminal. <br> unit ini segera kami serahkan kepada pelanggan dan pelanggan pun bahagia serta menyetir truk ini <br> ke rumah barunya yaitu Mandailing Natal, Sumatera Utara.</p>
                <a href="#" class="blog-btn">Read More<i class='bx bx-right-arrow-alt'></i></a>
            </div>
            <!-- Box 2 -->
            <div class="box">
                <img src="img/img220214_67a266.jpg" alt="">
                <span>Januari 07 2024</span>
                <h3>Pengiriman Fuso The Great KC-FU410U kepada pelanggan</h3>
                <p>Pada Tanggal 12 Desember 2023 kemarin, Fuso The Great FU410U dropside body telah mendarat sempurna di terminal kedatangan kendaraan pelabuhan Patimban, Jawa Barat. <br> unit ini langsung diambil oleh pelanggan dan dibawa jalan menuju rumah terbarunya yaitu Ungaran, Kab. Semarang.</p>
                <a href="#" class="blog-btn">Read More<i class='bx bx-right-arrow-alt'></i></a>
            </div>
            <!-- Box 3 -->
            <div class="box">
                <img src="img/part6.png" alt="">
                <span>Januari 03 2024</span>
                <h3>Apa itu ban salju?</h3>
                <p>Ban salju adalah ban yang dirancang khusus untuk medan jalanan yang sering dihujani hujan salju <br> lantas apa saja manfaatnya selain untuk salju? simak artikel berikut! </p>
                <a href="#" class="blog-btn">Read More<i class='bx bx-right-arrow-alt'></i></a>
            </div>
        </div>
    </section>
   <!-- Footer Section -->
    <section class="footer" id="footer">
        <div class="footer-container container">
            <div class="footer-box">
                <a href="#" class="logo">UniCar<span>Trucks</span></a>
                <div class="social">
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-youtube'></i></a>
                </div>
            </div>

            <div class="footer-box">
                <h3>Page</h3>
                <a href="#home">Home</a>
                <a href="#product">Product</a>
                <a href="#parts">Parts</a>
                <a href="#blog">Sales</a>
            </div>

            <div class="footer-box">
                <h3>Legal</h3>
                <a href="#">Privacy</a>
                <a href="#">Refund Policy</a>
                <a href="#">Cookie Policy</a>
            </div>

            <div class="footer-box">
                <h3>Contact</h3>
                <p>Indonesia</p>
                <p>Malaysia</p>
                <p>Japan</p>
            </div>
        </div>

        <div class="copyright">
            <p>&#169; UniCarTrucks All Right Reserved</p>
        </div>
    </section>
    <!-- Link To Js -->
    <script src="main.js"></script>
</body>   
</html>