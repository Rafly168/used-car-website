<?php
include 'koneksi.php';

// Pastikan ada parameter ID di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM cars WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

// Jika data tidak ditemukan
if (mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit;
}

$car = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail - <?= htmlspecialchars($car['title']); ?></title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Pengaturan Flexbox agar footer selalu di posisi paling bawah layar */
        body {
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .detail-container {
            flex: 1; /* Mendorong footer ke bawah */
            padding-top: 120px;
            padding-bottom: 60px;
            display: flex;
            flex-wrap: wrap;
            gap: 2.5rem;
            align-items: flex-start;
        }

        .detail-img {
            flex: 1 1 22rem;
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .detail-img img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 6px;
        }

        .detail-info {
            flex: 1 1 25rem;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .detail-info h1 {
            font-size: 1.8rem;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .detail-price {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--main-color);
            margin-bottom: 20px;
        }

        .detail-description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 25px;
            white-space: pre-line;
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn-back {
            background: #6c757d;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        /* Styling Footer */
        .copyright {
            background: var(--text-color);
            color: var(--bg-color);
            padding: 20px;
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header class="shadow">
        <div class="nav container">
            <a href="index.php" class="logo">UniCar<span>Trucks</span></a>
            <ul class="navbar">
                <li><a href="index.php#home">Home</a></li>
                <li><a href="index.php#parts">Product</a></li>
            </ul>
        </div>
    </header>

    <!-- DETAIL KENDARAAN -->
    <div class="detail-container container">
        <div class="detail-img">
            <?php if (!empty($car['image']) && file_exists('img/' . $car['image'])) : ?>
                <img src="img/<?= $car['image']; ?>" alt="<?= htmlspecialchars($car['title']); ?>">
            <?php else : ?>
                <img src="img/002-3.jpg" alt="Default Image">
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <h1><?= htmlspecialchars($car['title']); ?></h1>
            <div class="detail-price">Rp <?= number_format($car['price'], 0, ',', '.'); ?></div>
            
            <h3>Deskripsi Kendaraan:</h3>
            <p class="detail-description"><?= htmlspecialchars($car['description']); ?></p>

            <div class="btn-group">
                <a href="index.php#parts" class="btn-back"><i class='bx bx-left-arrow-alt'></i> Kembali ke Katalog</a>
                <a href="https://api.whatsapp.com/send?phone=ganti no wa&text=Halo%20UniCarTrucks,%20saya%20tertarik%20dengan%20<?= urlencode($car['title']); ?>" target="_blank" class="btn"><i class='bx bxl-whatsapp'></i> Hubungi Penjual</a>
            </div>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT -->
    <div class="copyright">
        <p>&#169; UniCarTrucks All Right Reserved</p>
    </div>

</body>
</html>