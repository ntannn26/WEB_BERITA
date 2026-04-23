<?php
session_start();
include 'config/koneksi.php';

/* AMBIL KATEGORI */
$kategori = mysqli_query($conn, "SELECT * FROM kategori");
$kategori_nav = mysqli_query($conn, "SELECT * FROM kategori");

/* SEARCH */
$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

/* HEADLINE */
$headline = null;

if ($cari != '') {
    $headlineQuery = mysqli_query($conn, "
        SELECT * FROM berita
        WHERE judul LIKE '%$cari%'
        ORDER BY id DESC
        LIMIT 1
    ");
} else {
    $headlineQuery = mysqli_query($conn, "
        SELECT * FROM berita
        ORDER BY id DESC
        LIMIT 1
    ");
}

if ($headlineQuery && mysqli_num_rows($headlineQuery) > 0) {
    $headline = mysqli_fetch_assoc($headlineQuery);
}

/* GAMBAR */
$gambarHeadline = "gambar/default.jpg";
if (!empty($headline['gambar']) && file_exists("gambar/" . $headline['gambar'])) {
    $gambarHeadline = "gambar/" . $headline['gambar'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>NewsApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f1f3f6; }
        html { scroll-behavior: smooth; }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 99999;
            background: linear-gradient(135deg, #0f172a, #293446);
        }

        .dropdown-menu { z-index: 999999 !important; }
        .offcanvas { z-index: 999999 !important; }

        /* HERO */
        .hero-headline {
            position: relative;
            min-height: 90vh;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.6);
        }

        .content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero-headline h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        h4 {
            scroll-margin-top: 100px;
        }

        /* CARD */
        .card-news {
            border: none;
            border-radius: 10px;
            transition: 0.3s;
        }

        .card-news:hover {
            transform: scale(1.03);
        }

        .kategori {
            font-size: 12px;
            color: red;
            font-weight: bold;
        }

        img {
            object-fit: cover;
        }

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
<nav class="navbar navbar-dark bg-dark shadow">
    <div class="container-fluid px-3">

        <a class="navbar-brand" href="index.php"> NewsApp</a>

        <div class="d-flex align-items-center">

            <!-- DROPDOWN -->
            <div class="dropdown me-2">
                <button class="btn btn-outline-light dropdown-toggle"
                        data-bs-toggle="dropdown">
                    Kategori
                </button>

                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="index.php">Semua</a></li>

                    <?php while($nav = mysqli_fetch_assoc($kategori_nav)){ ?>
                        <li>
                            <a class="dropdown-item"
                               href="#kategori-<?= $nav['id']; ?>">
                                <?= $nav['nama_kategori']; ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <!-- SEARCH -->
            <form class="d-flex me-2" method="GET">
                <input class="form-control me-2"
                       name="cari"
                       placeholder="Cari berita..."
                       value="<?= htmlspecialchars($cari); ?>">
                <button class="btn btn-danger">Cari</button>
            </form>

            <!-- USER -->
            <?php if(isset($_SESSION['login'])){ ?>
                <span class="text-white me-3">
                     <?= htmlspecialchars($_SESSION['username']); ?>
                </span>
            <?php } else { ?>
                <a href="auth/login.php" class="btn btn-outline-light me-2">Login</a>
                <a href="auth/register.php" class="btn btn-warning me-2">Daftar</a>
            <?php } ?>

            <!-- MENU -->
            <button class="btn btn-light"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#menuSamping">
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

        <!-- ATAS (PROFILE + MENU) -->
        <div>

            <?php if(isset($_SESSION['login'])){ ?>
                <!-- PROFILE -->
                <div class="text-center mb-4">
                    <img src="https://i.pravatar.cc/100"
                         class="rounded-circle mb-2"
                         width="80">

                    <h6 class="mb-0"><?= $_SESSION['username']; ?></h6>
                    <small class="text-muted">
                        <?= $_SESSION['role'] ?? 'user'; ?>
                    </small>
                </div>

                <!-- MENU LIST -->
                <div class="list-group">

                <a href="index.php" class="list-group-item list-group-item-action">
                     Home
                </a>

                <hr class="my-1">

                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'){ ?>
                    <a href="admin/dashboard.php" class="list-group-item list-group-item-action">
                         Dashboard
                    </a>

                    <hr class="my-1">
                <?php } ?>

                <a href="#" class="list-group-item list-group-item-action">
                     Berita
                </a>

                <hr class="my-1">

                <!-- KATEGORI -->
                <div class="list-group-item ">
                     <strong>Kategori</strong>
                </div>

                <?php 
                $kategori_menu = mysqli_query($conn, "SELECT * FROM kategori");
                while($menu = mysqli_fetch_assoc($kategori_menu)){ 
                ?>
                    <a href="#kategori-<?= $menu['id']; ?>" 
                    class="list-group-item list-group-item-action">
                        <?= $menu['nama_kategori']; ?>
                    </a>
                <?php } ?>

            </div>

            <?php } else { ?>
                <!-- BELUM LOGIN -->
                <div class="text-center">
                    <p>Silakan login dulu</p>
                    <a href="auth/login.php" class="btn btn-dark w-100 mb-2">Login</a>
                    <a href="auth/register.php" class="btn btn-warning w-100">Daftar</a>
                </div>
            <?php } ?>

        </div>

        <!-- BAWAH (LOGOUT) -->
        <?php if(isset($_SESSION['login'])){ ?>
        <div>
            <a href="auth/logout.php" class="btn btn-danger w-100">
                 Logout
            </a>
        </div>
        <?php } ?>

    </div>
</div>

<!-- HEADLINE -->
<?php if($headline){ ?>
<div class="hero-headline" style="background-image: url('<?= $gambarHeadline; ?>');">

    <div class="overlay"></div>

    <div class="container content">

        <span class="badge bg-danger mb-3">
            <?= $cari ? '🔍 HASIL PENCARIAN' : '🔥 BERITA TERBARU'; ?>
        </span>

        <h1><?= substr(strip_tags($headline['judul']), 0 , 40); ?></h1>

        <p><?= substr(strip_tags($headline['isi']),0,120); ?>...</p>

        <a href="detail.php?id=<?= $headline['id']; ?>"
           class="btn btn-danger btn-lg">
            Baca Selengkapnya →
        </a>

    </div>
</div>
<?php } ?>

<!-- KONTEN -->
<div class="container mt-4">

<?php while($kat = mysqli_fetch_assoc($kategori)){ ?>

<?php
$idKat = $kat['id'];

$beritaKat = mysqli_query($conn, "
    SELECT * FROM berita
    WHERE kategori_id = '$idKat'
    ORDER BY id DESC
    LIMIT 5
");
?>

<h4 id="kategori-<?= $kat['id']; ?>">
    <?= $kat['nama_kategori']; ?>
</h4>

<div class="row">

    <!-- KIRI -->
    <div class="col-md-8">
        <?php $utama = mysqli_fetch_assoc($beritaKat); ?>
        <?php if($utama){ ?>

        <div class="card mb-3">

            <img src="gambar/<?= $utama['gambar'] ?? 'default.jpg'; ?>" style="height:300px;">

            <div class="p-3">
                <h4><?= $utama['judul']; ?></h4>
                <p><?= substr(strip_tags($utama['isi']),0,150); ?>...</p>
                <a href="detail.php?id=<?= $utama['id']; ?>" class="btn btn-dark btn-sm">Baca</a>
            </div>

        </div>

        <?php } ?>
    </div>

    <!-- KANAN -->
    <div class="col-md-4">
        <?php while($list = mysqli_fetch_assoc($beritaKat)){ ?>
        <div class="d-flex mb-3 bg-white p-2 rounded shadow-sm">

            <img src="gambar/<?= $list['gambar'] ?? 'default.jpg'; ?>"
                 style="width:80px; height:80px;">

            <div class="ms-2">
                <p style="font-size:14px;">
                    <?= substr($list['judul'],0,60); ?>
                </p>
                <a href="detail.php?id=<?= $list['id']; ?>">Baca →</a>
            </div>

        </div>
        <?php } ?>
    </div>

</div>

<?php } ?>

</div>

<footer class="footer-custom">

    <div class="container py-4">
        <div class="row">

            <!-- BRAND -->
            <div class="col-md-6 mb-3">
                <h3 class="brand">NewsApp</h3>
                <p class="slogan">Stay Update, Stay Ahead</p>

                <h6 class="mt-4">About Us</h6>
                <p class="desc">
                    NewsApp adalah aplikasi portal berita yang menyajikan informasi terkini, cepat, 
                    dan terpercaya dari berbagai kategori dalam satu platform.
                </p>
            </div>

            <!-- COMPANY -->
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

            <!-- CONTACT -->
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

    <!-- BOTTOM -->
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