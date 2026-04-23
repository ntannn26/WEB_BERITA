<?php
include '../config/koneksi.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// AMBIL DATA BERITA
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM berita WHERE id=$id"));

if (!$data) {
    die("Data tidak ditemukan");
}

// PROSES UPDATE
if (isset($_POST['update'])) {

    $judul = $_POST['judul'];
    $isi = $_POST['isi'];
    $kategori = $_POST['kategori'];

    // GAMBAR
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp = $_FILES['gambar']['tmp_name'] ?? '';

    if (!empty($gambar)) {

        // rename biar aman
        $newName = time() . "_" . $gambar;
        move_uploaded_file($tmp, "../gambar/" . $newName);

        mysqli_query($conn, "UPDATE berita SET 
            judul='$judul',
            isi='$isi',
            kategori_id='$kategori',
            gambar='$newName'
            WHERE id=$id
        ");

    } else {

        mysqli_query($conn, "UPDATE berita SET 
            judul='$judul',
            isi='$isi',
            kategori_id='$kategori'
            WHERE id=$id
        ");
    }

    echo "<script>
        alert('Berita berhasil diupdate!');
        window.location='berita.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Berita</title>
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

        .preview-img {
            width: 140px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        textarea {
            resize: none;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="col-md-7 mx-auto">

        <div class="card-box">

            <h3 class="mb-4 title">✏️ Edit Berita</h3>

            <form method="POST" enctype="multipart/form-data">

                <!-- JUDUL -->
                <div class="mb-3">
                    <label class="form-label">Judul Berita</label>
                    <input type="text" name="judul" class="form-control"
                           value="<?= htmlspecialchars($data['judul']) ?>" required>
                </div>

                <!-- ISI -->
                <div class="mb-3">
                    <label class="form-label">Isi Berita</label>
                    <textarea name="isi" rows="6" class="form-control" required><?= htmlspecialchars($data['isi']) ?></textarea>
                </div>

                <!-- KATEGORI -->
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        $kat = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while ($k = mysqli_fetch_assoc($kat)) {
                            $selected = ($k['id'] == $data['kategori_id']) ? "selected" : "";
                            echo "<option value='{$k['id']}' $selected>{$k['nama_kategori']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- GAMBAR LAMA -->
                <div class="mb-2">
                    <label class="form-label">Gambar Saat Ini</label><br>
                    <img src="../gambar/<?= $data['gambar'] ?>" class="preview-img">
                </div>

                <!-- UPLOAD GAMBAR BARU -->
                <div class="mb-3">
                    <label class="form-label">Ganti Gambar (opsional)</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <!-- BUTTON -->
                <button type="submit" name="update" class="btn btn-primary w-100">
                    💾 Update Berita
                </button>

                <a href="berita.php" class="btn btn-secondary w-100 mt-2">
                    ↩ Kembali
                </a>

            </form>

        </div>

    </div>

</div>

</body>
</html>