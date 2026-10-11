<?php
session_start();

// Kosongkan semua variabel session
$_SESSION = array();

// Hapus cookie session dari browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logging out...</title>
</head>
<body>
    <script>
        // Hapus token sessionStorage agar saat tab baru dibuka tidak bisa ditembus
        sessionStorage.removeItem('admin_session');
        window.location.href = 'login.php';
    </script>
</body>
</html>