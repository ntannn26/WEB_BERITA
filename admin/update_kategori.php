<?php
include '../config/koneksi.php';

// AMBIL ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID tidak valid");
}

// AMBIL DATA
$result = mysqli_query($conn, "SELECT * FROM kategori WHERE id=$id");
$d = mysqli_fetch_assoc($result);

if (!$d) {
    die("Data tidak ditemukan");
}

// PROSES UPDATE
if (isset($_POST['update'])) {

    $nama = trim($_POST['nama']);
    $nama = mysqli_real_escape_string($conn, $nama);

    if ($nama == "") {
        echo "<script>alert('Nama kategori tidak boleh kosong!');</script>";
    } else {

        $query = mysqli_query($conn, "UPDATE kategori SET 
            nama_kategori='$nama' 
            WHERE id=$id
        ");

        if ($query) {
            echo "<script>
                alert('Kategori berhasil diupdate!');
                window.location='kategori.php';
            </script>";
        } else {
            echo "<script>alert('Gagal update kategori!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Kategori</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card-box {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .title {
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="col-md-5 mx-auto">

        <div class="card-box">

            <h3 class="mb-4 title">✏️ Update Kategori</h3>

            <form method="POST">

                <!-- NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($d['nama_kategori']) ?>" required>
                </div>

                <!-- BUTTON -->
                <button type="submit" name="update" class="btn btn-primary w-100">
                    💾 Update
                </button>

                <a href="kategori.php" class="btn btn-secondary w-100 mt-2">
                    ↩ Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>