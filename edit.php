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

// Validasi parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM cars WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    header("Location: admin.php");
    exit;
}

$data = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $title       = mysqli_real_escape_string($koneksi, $_POST['title']);
    $price       = $_POST['price'];
    $description = mysqli_real_escape_string($koneksi, $_POST['description']);
    $newImageName = $data['image']; // Default pakai gambar lama

    // Cek apakah user mengunggah gambar baru
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmp  = $_FILES['image']['tmp_name'];
        
        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $newImageName   = uniqid('car_', true) . '.' . strtolower($imageExtension);
        $targetDir      = 'img/' . $newImageName;

        if (move_uploaded_file($imageTmp, $targetDir)) {
            // Hapus gambar lama jika ada dan bukan default
            if (!empty($data['image']) && file_exists('img/' . $data['image'])) {
                unlink('img/' . $data['image']);
            }
        } else {
            $error_msg = "Gagal mengunggah gambar baru!";
        }
    }

    if (!isset($error_msg)) {
        $updateQuery = "UPDATE cars SET title='$title', price='$price', description='$description', image='$newImageName' WHERE id='$id'";
        
        if (mysqli_query($koneksi, $updateQuery)) {
            header("Location: admin.php");
            exit();
        } else {
            $error_msg = "Gagal memperbarui data: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Mobil - UniCarTrucks</title>
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
        .current-img-preview {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 8px;
            display: block;
        }
        .btn-update {
            padding: 10px 20px;
            background-color: #ffc107;
            color: #000;
            border: none;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-update:hover {
            background-color: #e0a800;
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

    <!-- FORM EDIT MOBIL -->
    <div class="form-container container">
        <div class="form-card">
            <h2><i class='bx bx-edit-alt'></i> Edit Data Mobil</h2>
            <p class="subtitle">Perbarui informasi kendaraan atau ubah foto produk</p>

            <?php if (isset($error_msg)) : ?>
                <div class="alert-danger"><?= $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Nama Mobil / Truk</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($data['title']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp)</label>
                    <input type="number" name="price" id="price" value="<?= $data['price']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($data['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="image">Foto Kendaraan (Biarkan kosong jika tidak ingin mengubah foto)</label>
                    <input type="file" name="image" id="image" accept="image/*">
                    <?php if (!empty($data['image']) && file_exists('img/' . $data['image'])) : ?>
                        <small style="color: #666; display: block; margin-top: 5px;">Foto Saat Ini:</small>
                        <img src="img/<?= $data['image']; ?>" class="current-img-preview" alt="Foto Saat Ini">
                    <?php endif; ?>
                </div>

                <button type="submit" name="update" class="btn-update"><i class='bx bx-save'></i> Perbarui Data</button>
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