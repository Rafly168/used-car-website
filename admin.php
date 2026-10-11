<?php
// 1. Pengaturan Cookie Session
session_set_cookie_params([
    'lifetime' => 0, // Cookie akan terhapus saat browser/tab ditutup
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// 2. Header Anti-Cache
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

// 3. Cek Status Login Dulu
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// 4. Batasi Inaktivitas (15 Menit) Tanpa Logout Saat Refresh
$timeout = 900; // 15 menit
if (isset($_SESSION['last_activity'])) {
    $duration = time() - $_SESSION['last_activity'];
    if ($duration > $timeout) {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
$_SESSION['last_activity'] = time(); // Perbarui waktu aktivitas terakhir

include 'koneksi.php';
$query = "SELECT * FROM cars ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UniCarTrucks</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .admin-container {
            flex: 1;
            padding-top: 100px;
            padding-bottom: 50px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .admin-title h2 {
            font-size: 1.8rem;
            color: #1a1a1a;
        }
        .admin-title p {
            color: #666;
            font-size: 0.95rem;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-custom {
            padding: 10px 18px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.3s;
        }
        .btn-add {
            background-color: #28a745;
            color: #fff;
        }
        .btn-add:hover {
            background-color: #218838;
        }
        .btn-logout {
            background-color: #e22b2b;
            color: #fff;
        }
        .btn-logout:hover {
            background-color: #c01e1e;
        }
        .table-responsive {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        .custom-table th {
            background-color: #1a1a1a;
            color: #fff;
            padding: 14px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .custom-table td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            color: #333;
            vertical-align: middle;
        }
        .custom-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        .car-img-thumb {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .price-badge {
            font-weight: 700;
            color: #e22b2b;
        }
        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: 0.2s;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }
        .btn-edit:hover {
            background-color: #e0a800;
        }
        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header>
        <div class="nav container">
            <a href="index.php" class="logo">UniCar<span>Trucks</span></a>
            <ul class="navbar">
                <li><a href="admin.php" class="active">Dashboard Admin</a></li>
            </ul>
        </div>
    </header>

    <!-- KONTEN ADMIN -->
    <div class="admin-container container">
        <div class="admin-header">
            <div class="admin-title">
                <h2>Kelola Data Mobil Bekas</h2>
                <p>Halo, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong>! Tambah, edit, atau hapus katalog kendaraan di sini.</p>
            </div>
            <div class="action-buttons">
                <a href="tambah.php" class="btn-custom btn-add"><i class='bx bx-plus-circle'></i> Tambah Mobil</a>
                <a href="logout.php" class="btn-custom btn-logout" onclick="sessionStorage.removeItem('admin_session');"><i class='bx bx-log-out'></i> Logout</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="12%">Foto</th>
                        <th width="23%">Nama Mobil</th>
                        <th width="18%">Harga</th>
                        <th width="27%">Deskripsi</th>
                        <th width="15%" style="text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td>#<?= $row['id']; ?></td>
                            <td>
                                <?php if (!empty($row['image']) && file_exists('img/' . $row['image'])) : ?>
                                    <img src="img/<?= $row['image']; ?>" class="car-img-thumb" alt="Foto Mobil">
                                <?php else : ?>
                                    <img src="img/002-3.jpg" class="car-img-thumb" alt="Default">
                                <?php endif; ?>
                            </td>
                            <td><strong><?= htmlspecialchars($row['title']); ?></strong></td>
                            <td><span class="price-badge">Rp <?= number_format($row['price'], 0, ',', '.'); ?></span></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td style="text-align: center;">
                                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-action btn-edit"><i class='bx bx-edit-alt'></i> Edit</a>
                                <a href="hapus.php?id=<?= $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class='bx bx-trash'></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #888;">
                                Belum ada data mobil. Klik tombol <strong>+ Tambah Mobil</strong> untuk memasukkan data baru.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT -->
    <div class="copyright">
        <p>&#169; UniCarTrucks All Right Reserved</p>
    </div>

    <script>
        // 1. Cek token sessionStorage. Jika tab baru dibuka (sessionStorage kosong), paksa logout.
        if (!sessionStorage.getItem('admin_session')) {
            window.location.href = 'logout.php';
        }

        // 2. Paksa reload jika dibuka lewat tombol Back/Forward browser
        window.addEventListener('pageshow', function (event) {
            var historyTraversal = event.persisted || 
                (typeof window.performance != "undefined" && window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>