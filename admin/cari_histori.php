<?php
include '../config/koneksi.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

$query = mysqli_query($conn, "
    SELECT berita.judul, berita.created_at, kategori.nama_kategori
    FROM berita
    LEFT JOIN kategori ON berita.kategori_id = kategori.id
    WHERE berita.judul LIKE '%$cari%'
    ORDER BY berita.created_at DESC
    LIMIT 5
");

$no = 1;

if(mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)){
        echo "<tr>
                <td>".$no++."</td>
                <td>".htmlspecialchars($row['judul'])."</td>
                <td>".htmlspecialchars($row['nama_kategori'])."</td>
                <td>".date('d M Y H:i', strtotime($row['created_at']))."</td>
              </tr>";
    }
}else{
    echo "<tr>
            <td colspan='4' class='text-center text-muted'>Data tidak ditemukan</td>
          </tr>";
}
?>