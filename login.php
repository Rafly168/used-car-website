<?php
session_start();
include 'koneksi.php';

// Jika sudah login, langsung redirect ke admin.php
if (isset($_SESSION['login'])) {
    header("Location: admin.php");
    exit;
}

$login_success = false;

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cek username dan password ke database
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['login'] = true;
        $_SESSION['username'] = $row['username'];
        $login_success = true;
    } else {
        $error_msg = "Username atau password yang Anda masukkan salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - UniCarTrucks</title>
    <!-- Link CSS bawaan index.php -->
    <link rel="stylesheet" href="style.css">
    <!-- Box Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            justify-content: space-between;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px;
            flex: 1;
        }
        .login-card {
            background: #fff;
            padding: 30px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 380px;
        }
        .login-card h2 {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-bottom: 5px;
            text-align: center;
        }
        .login-card p.subtitle {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #ffe6e6;
            color: #d9534f;
            padding: 10px 12px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            font-size: 0.85rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrapper i {
            position: absolute;
            left: 10px;
            font-size: 1.2rem;
            color: #888;
        }
        .input-wrapper input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 0.95rem;
            outline: none;
            transition: 0.3s;
            box-sizing: border-box;
        }
        .input-wrapper input:focus {
            border-color: #e22b2b;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: #e22b2b;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background-color: #c01e1e;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 0.85rem;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            color: #e22b2b;
        }

.navbar a[href="index.php"] {
    color: var(--main-color) !important; 
    border-bottom: none !important;      
    transition: none !important;         
}

.navbar a[href="index.php"]:hover,
.navbar a[href="index.php"]:focus,
.navbar a[href="index.php"]:active {
    color: var(--main-color) !important;
    border-bottom: none !important;
}
    </style>
</head>
<body>

    <!-- NAVBAR HEADER -->
    <header>
        <div class="nav container">
            <a href="index.php" class="logo">UniCar<span>Trucks</span></a>
            <ul class="navbar">
                <li><a href="index.php">Kembali ke Beranda</a></li>
            </ul>
        </div>
    </header>

    <!-- FORM LOGIN -->
    <div class="login-container">
        <div class="login-card">
            <h2>Admin Login</h2>
            <p class="subtitle">Masuk untuk mengelola data kendaraan</p>

            <?php if (isset($error_msg)) : ?>
                <div class="alert-danger">
                    <i class='bx bx-error-circle'></i>
                    <span><?= $error_msg; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class='bx bx-user'></i>
                        <input type="text" name="username" id="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class='bx bx-lock-alt'></i>
                        <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="login" class="btn-login">Masuk</button>
            </form>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT -->
    <div class="copyright">
        <p>&#169; UniCarTrucks All Right Reserved</p>
    </div>

    <?php if ($login_success) : ?>
    <script>
        // Simpan token sesi browser aktif dan berpindah ke admin.php
        sessionStorage.setItem('admin_session', 'active');
        window.location.href = 'admin.php';
    </script>
    <?php endif; ?>

</body>
</html>