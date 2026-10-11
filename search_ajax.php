<?php
include 'koneksi.php';

$search = '';
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = mysqli_real_escape_string($koneksi, trim($_GET['search']));
    $query_cars = "SELECT * FROM cars WHERE title LIKE '%$search%' OR description LIKE '%$search%' ORDER BY id DESC";
} else {
    $query_cars = "SELECT * FROM cars ORDER BY id DESC";
}

$result_cars = mysqli_query($koneksi, $query_cars);

if (mysqli_num_rows($result_cars) > 0) :
    while ($car = mysqli_fetch_assoc($result_cars)) : ?>
        <div class="box">
            <?php if (!empty($car['image']) && file_exists('img/' . $car['image'])) : ?>
                <img src="img/<?= $car['image']; ?>" alt="<?= htmlspecialchars($car['title']); ?>">
            <?php else : ?>
                <img src="img/002-3.jpg" alt="Default Image">
            <?php endif; ?>

            <h3><?= htmlspecialchars($car['title']); ?></h3>
            <span>Rp. <?= number_format($car['price'], 0, ',', '.'); ?></span>
            <i class='bx bxs-star'>(0 Reviews)</i>
            <a href="detail.php?id=<?= $car['id']; ?>" class="btn">Buy now</a>
            <a href="detail.php?id=<?= $car['id']; ?>" class="details">Lihat Detail</a>
        </div>
    <?php endwhile;
else : ?>
    <div class="no-data-alert">
        <i class='bx bx-search-alt-2'></i>
        <p>Kendaraan dengan kata kunci "<strong><?= htmlspecialchars($search); ?></strong>" tidak ditemukan.</p>
        <button type="button" id="btn-show-all" class="btn">Tampilkan Semua Kendaraan</button>
    </div>
<?php endif; ?>