<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['submit'])) {
    $title       = mysqli_real_escape_string($koneksi, $_POST['title']);
    $price       = $_POST['price'];
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);

    // Buat folder img jika belum ada
    if (!file_exists('img')) {
        mkdir('img', 0777, true);
    }

    $imageName  = $_FILES['image']['name'];
    $imageTmp   = $_FILES['image']['tmp_name'];
    $imageError = $_FILES['image']['error'];

    if ($imageError === 0) {
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newImageName   = uniqid('car_', true) . '.' . strtolower($imageExtension);
        $targetDir      = 'img/' . $newImageName;

        if (move_uploaded_file($imageTmp, $targetDir)) {
            $query = "INSERT INTO cars (title, price, description, image) VALUES ('$title', '$price', '$description', '$newImageName')";
            
            if (mysqli_query($koneksi, $query)) {
                header("Location: admin.php");
                exit();
            } else {
                $error_msg = "Gagal menyimpan ke database: " . mysqli_error($koneksi);
            }
        } else {
            $error_msg = "Gagal mengunggah gambar ke folder img!";
        }
    } else {
        $error_msg = "Silakan pilih gambar terlebih dahulu!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mobil Baru - UniCarTrucks</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .form-container {
            flex: 1;
            padding-top: 100px;
            padding-bottom: 50px;
            display: flex;
            justify-content: center;
        }
        .form-card {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 550px;
        }
        .form-card h2 {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .form-card p.subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.95rem;
            outline: none;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group textarea:focus {
            border-color: #e22b2b;
        }
        .btn-submit {
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-submit:hover {
            background-color: #218838;
        }
        .btn-cancel {
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            margin-left: 8px;
            display: inline-block;
        }
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        .alert-danger {
            background-color: #ffe6e6;
            color: #d9534f;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header>
        <div class="nav container">
            <a href="index.php" class="logo">UniCar<span>Trucks</span></a>
            <ul class="navbar">
                <li><a href="admin.php">Kembali ke Dashboard</a></li>
            </ul>
        </div>
    </header>

    <!-- FORM TAMBAH MOBIL -->
    <div class="form-container container">
        <div class="form-card">
            <h2><i class='bx bx-plus-circle'></i> Tambah Mobil Baru</h2>
            <p class="subtitle">Isi data kendaraan dan unggah foto produk</p>

            <?php if (isset($error_msg)) : ?>
                <div class="alert-danger"><?= $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Nama Mobil / Truk</label>
                    <input type="text" name="title" id="title" placeholder="Contoh: Hino Profia BDG-FN1EYYA" required>
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" name="price" id="price" placeholder="Contoh: 250000000" required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" placeholder="Tuliskan spesifikasi atau kondisi kendaraan..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Foto Kendaraan</label>
                    <input type="file" name="image" id="image" accept="image/*" required>
                </div>

                <button type="submit" name="submit" class="btn-submit"><i class='bx bx-save'></i> Simpan Data</button>
                <a href="admin.php" class="btn-cancel">Batal</a>
            </form>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT -->
    <div class="copyright">
        <p>&#169; UniCarTrucks All Right Reserved</p>
    </div>

</body>
</html>