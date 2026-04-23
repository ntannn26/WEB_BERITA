<?php
session_start();
include 'config/koneksi.php';

// VALIDASI ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "ID tidak valid!";
    exit;
}

// QUERY DETAIL
$stmt = mysqli_prepare($conn, "
    SELECT berita.*, kategori.nama_kategori 
    FROM berita 
    LEFT JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.id = ?
");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Berita tidak ditemukan!";
    exit;
}

// BERITA TERKAIT
$kategori_id = $data['kategori_id'];
$related = mysqli_query($conn, "
    SELECT * FROM berita 
    WHERE kategori_id = '$kategori_id' 
    AND id != '$id'
    ORDER BY id DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($data['judul']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f1f3f6;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: linear-gradient(135deg, #0f172a, #293446);
        }

        /* CONTENT */
        .container-fluid {
            max-width: 1400px;
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            min-height: 80vh;
        }

        .news-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .kategori {
            font-size: 13px;
            font-weight: bold;
            color: red;
        }

        .judul {
            font-weight: bold;
            line-height: 1.3;
        }

        .isi {
            text-align: justify;
            line-height: 1.8;
        }

        /* SIDEBAR */
        .sidebar {
            position: sticky;
            top: 80px;
        }

        .card {
            border: none;
            border-radius: 10px;
            background: #e5e7eb;
        }

        .card:hover {
            background: #d1d5db;
            cursor: pointer;
        }

        /* FOOTER */
        .footer-custom {
            background: #f4f4f4;
            border-top: 5px solid #111827;
            font-size: 14px;
            margin-top: 50px;
        }

        .brand {
            font-weight: bold;
        }

        .slogan {
            color: red;
            font-size: 13px;
        }

        .desc {
            font-size: 13px;
            color: #555;
        }

        .footer-list {
            list-style: none;
            padding: 0;
        }

        .footer-list li {
            margin-bottom: 6px;
            color: #444;
            cursor: pointer;
        }

        .footer-list li:hover {
            color: red;
        }

        .social-icons span {
            display: inline-block;
            width: 32px;
            height: 32px;
            line-height: 32px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 50%;
            margin-right: 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .social-icons span:hover {
            background: red;
            color: white;
        }

        .footer-bottom {
            background: #111827;
            color: white;
            padding: 15px 0;
            font-size: 13px;
        }

        .footer-bottom a {
            color: #ccc;
            text-decoration: none;
            margin-right: 10px;
        }

        .footer-bottom a:hover {
            color: red;
        }

        .list-group-item {
            border: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }

        .list-group-item:hover {
            background-color: #f1f3f6;
            padding-left: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark shadow">
    <div class="container-fluid px-3">
        <a class="navbar-brand" href="index.php">NewsApp</a>

        <div class="d-flex align-items-center">
            <?php if (isset($_SESSION['login'])) { ?>
                <span class="text-white me-3">
                    <?= htmlspecialchars($_SESSION['username']); ?>
                </span>
            <?php } else { ?>
                <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="auth/register.php" class="btn btn-warning me-2">Daftar</a>
            <?php } ?>

            <button class="btn btn-light" data-bs-toggle="offcanvas" data-bs-target="#menuSamping">
                ☰
            </button>
        </div>
    </div>
</nav>

<!-- OFFCANVAS -->
<div class="offcanvas offcanvas-end" id="menuSamping">
    <div class="offcanvas-header border-bottom">
        <h5 class="fw-bold">Menu</h5>
        <button class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column justify-content-between">

        <div>
            <?php if (isset($_SESSION['login'])) { ?>

                <!-- PROFILE -->
                <div class="text-center mb-4">
                    <img src="https://i.pravatar.cc/100" class="rounded-circle mb-2" width="80">
                    <h6 class="mb-0"><?= $_SESSION['username']; ?></h6>
                    <small class="text-muted"><?= $_SESSION['role'] ?? 'user'; ?></small>
                </div>

                <!-- MENU -->
                <div class="list-group">
                    <a href="index.php" class="list-group-item list-group-item-action">Home</a>
                    <hr class="my-1">

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                        <a href="admin/dashboard.php" class="list-group-item list-group-item-action">Dashboard</a>
                        <hr class="my-1">
                    <?php } ?>

                    <a href="#" class="list-group-item list-group-item-action">Berita</a>
                    <hr class="my-1">

                    <div class="list-group-item"><strong>Kategori</strong></div>

                    <?php
                    $kategori_menu = mysqli_query($conn, "SELECT * FROM kategori");
                    while ($menu = mysqli_fetch_assoc($kategori_menu)) {
                    ?>
                        <a href="#kategori-<?= $menu['id']; ?>" class="list-group-item list-group-item-action">
                            <?= $menu['nama_kategori']; ?>
                        </a>
                    <?php } ?>
                </div>

            <?php } else { ?>

                <div class="text-center">
                    <p>Silakan login dulu</p>
                    <a href="auth/login.php" class="btn btn-dark w-100 mb-2">Login</a>
                    <a href="auth/register.php" class="btn btn-warning w-100">Daftar</a>
                </div>

            <?php } ?>
        </div>

        <?php if (isset($_SESSION['login'])) { ?>
            <div>
                <a href="auth/logout.php" class="btn btn-danger w-100">Logout</a>
            </div>
        <?php } ?>

    </div>
</div>

<!-- CONTENT -->
<div class="container-fluid px-4 mt-4">
    <div class="row g-4">

        <!-- KONTEN UTAMA -->
        <div class="col-lg-9 col-md-8">
            <div class="content shadow">

                <span class="kategori">
                    <?= htmlspecialchars($data['nama_kategori'] ?? 'Umum'); ?>
                </span>

                <h1 class="judul mt-2">
                    <?= htmlspecialchars($data['judul']); ?>
                </h1>

                <hr>

                <?php if (!empty($data['gambar']) && file_exists("gambar/" . $data['gambar'])) { ?>
                    <img src="gambar/<?= htmlspecialchars($data['gambar']); ?>" class="news-img mb-3">
                <?php } ?>

                <div class="isi mt-3">
                    <?= nl2br(htmlspecialchars($data['isi'])); ?>
                </div>

                <a href="index.php" class="btn btn-secondary mt-4">
                    ← Kembali
                </a>

            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-3 col-md-4 sidebar">
            <h5 class="fw-bold mb-3">Berita Terkait</h5>
            <?php while ($r = mysqli_fetch_assoc($related)) { ?>
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold">
                            <?= htmlspecialchars($r['judul']); ?>
                        </h6>

                        <a href="detail.php?id=<?= $r['id']; ?>" class="text-danger text-decoration-none">
                            Baca →
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<!-- FOOTER -->
<footer class="footer-custom">

    <div class="container py-4">
        <div class="row">

            <div class="col-md-6 mb-3">
                <h3 class="brand">NewsApp</h3>
                <p class="slogan">Stay Update, Stay Ahead</p>

                <h6 class="mt-4">About Us</h6>
                <p class="desc">
                    NewsApp adalah aplikasi portal berita yang menyajikan informasi terkini,
                    cepat, dan terpercaya dari berbagai kategori dalam satu platform.
                </p>
            </div>

            <div class="col-md-2 mb-3">
                <h6>Company</h6>
                <ul class="footer-list">
                    <li>Who We Are</li>
                    <li>Our Services</li>
                    <li>Our Clients</li>
                    <li>Pricing</li>
                    <li>Contact Us</li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h6>Contact us</h6>

                <p><strong>Telepon :</strong><br> +62 812-3456-7890</p>
                <p><strong>Email :</strong><br> newsapp@email.com</p>
                <p><strong>Alamat :</strong><br> Indonesia</p>

                <div class="social-icons mt-3">
                    <span>F</span>
                    <span>T</span>
                    <span>I</span>
                    <span>W</span>
                    <span>G</span>
                </div>

                <p class="mt-2 fw-bold">Follow Us</p>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container d-flex justify-content-between flex-wrap">
            <div>
                <a href="#">Privacy Policy</a>
                <a href="#">Our History</a>
                <a href="#">What We Do</a>
            </div>

            <div>
                © 2026 NewsApp — All Rights Reserved
            </div>
        </div>
    </div>

</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>