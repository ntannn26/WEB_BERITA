<?php
include '../config/koneksi.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

$query = mysqli_query($conn, "
    SELECT berita.*, kategori.nama_kategori 
    FROM berita 
    JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.judul LIKE '%$cari%' 
       OR kategori.nama_kategori LIKE '%$cari%'
    ORDER BY berita.id DESC
");

$no = 1;

if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?= $no++ ?></td>

    <td>
        <img src="../gambar/<?= htmlspecialchars($row['gambar']) ?>" class="img-thumb">
    </td>

    <td class="text-start fw-semibold">
        <?= htmlspecialchars($row['judul']) ?>
    </td>

    <td>
        <span class="badge bg-success">
            <?= htmlspecialchars($row['nama_kategori']) ?>
        </span>
    </td>

    <td>
        <a href="edit_berita.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-warning btn-sm me-1">
            ✏ Edit
        </a>

        <a href="hapus_berita.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin hapus berita ini?')">
            🗑 Hapus
        </a>
    </td>
</tr>

<?php
    }
} else {
    echo "<tr><td colspan='5' class='text-muted py-4'>Data tidak ditemukan</td></tr>";
}
?>