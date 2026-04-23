<?php
include '../config/koneksi.php';

$cari = isset($_GET['cari']) ? mysqli_real_escape_string($conn, $_GET['cari']) : '';

$query = mysqli_query($conn, "
    SELECT * FROM users 
    WHERE username LIKE '%$cari%' 
    ORDER BY id DESC
");

if(mysqli_num_rows($query) > 0){
    while($user = mysqli_fetch_assoc($query)){
?>

<div class="col-md-4">
    <div class="user-card shadow-sm">

        <img src="https://i.pravatar.cc/150?u=<?= $user['username']; ?>" class="user-img">

        <h6><?= htmlspecialchars($user['username']); ?></h6>

        <small class="text-muted">
            <?= htmlspecialchars($user['role']); ?>
        </small>

        <div class="mt-1">
            <small class="text-muted">
                <?= !empty($user['last_login']) 
                    ? "Last login: " . date('d M Y H:i', strtotime($user['last_login']))
                    : "Belum login"; ?>
            </small>
        </div>

    </div>
</div>

<?php
    }
} else {
    echo "<p class='text-muted'>User tidak ditemukan</p>";
}
?>