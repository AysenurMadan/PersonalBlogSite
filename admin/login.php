<?php
session_start();
require_once '../config.php';

$hata = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici = trim($_POST["username"]);
    $sifre = $_POST["password"];

    $giris = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $giris->execute([$kullanici]);
    $veri = $giris->fetch();

    if ($veri && password_verify($sifre, $veri["password"])) {
        $_SESSION["user_id"] = $veri["id"];
        $_SESSION["username"] = $veri["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $hata = "Kullanıcı adı veya şifre yanlış.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş | Ayşenur'un Blogu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        .giris-kutusu {
            max-width: 400px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 16px;
            backdrop-filter: blur(8px);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
        h2 {
            font-weight: 300;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-coffee {
            background-color: #6f4e37;
            color: white;
        }
    </style>
</head>
<body>
    <div class="giris-kutusu">
        <h2> Ayşenur'un Bloguna Giriş</h2>
        <?php if ($hata): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Kullanıcı Adı</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Şifre</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-coffee w-100">Giriş Yap ☕</button>
        </form>
        <div class="mt-3 text-center" style="font-size: 0.85rem;">
            <em>Bilgisayar Mühendisliği 3. sınıf öğrencisi</em>
        </div>
    </div>
</body>
</html>



