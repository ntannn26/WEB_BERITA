<?php
include '../config/koneksi.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);

// VALIDASI
if(empty($username) || empty($password)){
    echo "Semua field wajib diisi!";
    exit;
}

// CEK USERNAME
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
if(mysqli_num_rows($cek) > 0){
    echo "Username sudah digunakan!";
    exit;
}

// 🔥 HASH PASSWORD (INI KUNCI UTAMA)
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// SIMPAN
mysqli_query($conn, "INSERT INTO users (username, password, role) 
VALUES ('$username', '$password_hash', 'user')");

echo "Register berhasil! <a href='login.php'>Login</a>";
?>