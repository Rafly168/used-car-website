<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM cars WHERE id = $id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    header("Location: admin.php");
    exit();
}
?>