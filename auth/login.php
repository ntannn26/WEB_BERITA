<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sederhana</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            width: 320px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-box {
            margin-bottom: 15px;
        }

        .input-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #333;
        }

        .register {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .register a {
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Box Login -->
    <div class="login-box">
        <form method="POST" action="proses_login.php">
            <h2>Login</h2>

            <div class="input-box">
                <input type="text" name="username" placeholder="Masukkan username" required>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="register">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>

</body>
</html>