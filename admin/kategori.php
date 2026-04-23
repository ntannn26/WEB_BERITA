<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit;
}

include '../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Data Kategori</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f5f7fb;
    font-family: 'Segoe UI', sans-serif;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    position: fixed;
    background: white;
    padding: 20px;
    border-right: 1px solid #eee;
}

.sidebar a {
    display: block;
    padding: 10px;
    margin: 8px 0;
    color: #555;
    text-decoration: none;
    border-radius: 8px;
}

.sidebar a:hover,
.sidebar a.active {
    background: #0d6efd;
    color: white;
}

/* MAIN */
.main {
    margin-left: 250px;
    padding: 20px;
}

/* TOPBAR */
.topbar {
    background: white;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

/* CARD */
.card-box {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

/* TABLE */
.table thead {
    background: #0d6efd;
    color: white;
}

.table td, .table th {
    vertical-align: middle;
}

/* BUTTON */
.btn {
    border-radius: 10px;
}

/* TITLE */
.title {
    font-weight: 700;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h5>⚙ Admin</h5>
    <a href="dashboard.php">Dashboard</a>
    <a href="berita.php">Berita</a>
    <a href="kategori.php" class="active">Kategori</a>
    <a href="histori.php">Histori</a>
    <hr>
    <a href="../auth/logout.php" class="text-danger">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar d-flex justify-content-between align-items-center">
        <input type="text" id="searchKategori" class="form-control w-50" placeholder="Cari kategori...">
        <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>
    </div>

    <!-- CARD -->
    <div class="card-box">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="title mb-0"> Data Kategori</h3>
                <small class="text-muted">Kelola semua kategori berita</small>
            </div>

            <a href="tambah_kategori.php" class="btn btn-success btn-sm px-3">
                 Tambah Kategori
            </a>

        </div>

        <!-- TABLE -->
        <div class="table-responsive">

            <table class="table table-hover align-middle text-center">

                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th class="text-start">Nama Kategori</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tableKategori">

                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id DESC");

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                ?>

                    <tr>
                        <td><?= $no++ ?></td>

                        <td class="text-start fw-semibold">
                            <?= htmlspecialchars($row['nama_kategori']) ?>
                        </td>

                        <td>
                            <a href="update_kategori.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-warning btn-sm me-1">
                                 Edit
                            </a>

                            <a href="hapus_kategori.php?id=<?= (int)$row['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin ingin menghapus kategori ini?')">
                                 Hapus
                            </a>
                        </td>
                    </tr>

                <?php
                    }
                } else {
                    echo "
                    <tr>
                        <td colspan='3' class='text-muted py-4'>
                            Belum ada data kategori
                        </td>
                    </tr>";
                }
                ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<script>
document.getElementById("searchKategori").addEventListener("keyup", function () {

    let keyword = this.value;

    fetch("cari_kategori.php?cari=" + encodeURIComponent(keyword))
        .then(res => res.text())
        .then(data => {
            document.getElementById("tableKategori").innerHTML = data;
        })
        .catch(err => console.log(err));

});
</script>
</body>
</html>