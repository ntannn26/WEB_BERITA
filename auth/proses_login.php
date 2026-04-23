<?php
session_start();
include '../config/koneksi.php';

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
$data = mysqli_fetch_assoc($query);

if ($data && password_verify($password, $data['password'])) {

    if (!empty($data['banned_until']) && strtotime($data['banned_until']) > time()) {

        $sisa = date('d M Y H:i', strtotime($data['banned_until']));

        echo "<script>
                alert('Akun Anda diblokir sampai $sisa');
                window.location='../index.php';
              </script>";
        exit;
    }

    $_SESSION['login'] = true;
    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = $data['role'];

    mysqli_query($conn, "
        UPDATE users 
        SET last_login = NOW() 
        WHERE id = ".$data['id']
    );

    header("Location: ../admin/dashboard.php");
    exit;

} else {
    echo "<script>
            alert('Username atau password salah!');
            window.location='../index.php';
          </script>";
    exit;
}
?>