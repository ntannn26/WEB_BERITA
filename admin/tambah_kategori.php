<?php
include '../config/koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];

    mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");

    echo "<script>
        alert('Kategori berhasil ditambahkan!');
        window.location='kategori.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Kategori</title>

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

            <h3 class="mb-4 title"> Tambah Kategori</h3>

            <form method="POST">

                <!-- INPUT NAMA -->
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>

                <!-- BUTTON -->
                <button type="submit" name="simpan" class="btn btn-success w-100">
                     Simpan Kategori
                </button>

                <a href="kategori.php" class="btn btn-secondary w-100 mt-2">
                     Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>