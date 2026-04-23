<?php
include '../config/koneksi.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

$query = mysqli_query($conn, "
    SELECT * FROM kategori
    WHERE nama_kategori LIKE '%$cari%'
    ORDER BY id DESC
");

$no = 1;

if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
?>

<tr>
    <td><?= $no++ ?></td>

    <td class="text-start fw-semibold">
        <?= htmlspecialchars($row['nama_kategori']) ?>
    </td>

    <td>
        <a href="update_kategori.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-warning btn-sm me-1">
            ✏ Edit
        </a>

        <a href="hapus_kategori.php?id=<?= (int)$row['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus kategori ini?')">
            🗑 Hapus
        </a>
    </td>
</tr>

<?php
    }
} else {
    echo "<tr><td colspan='3' class='text-muted py-4'>Kategori tidak ditemukan</td></tr>";
}
?>