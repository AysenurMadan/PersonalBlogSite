<?php
require_once 'config.php';

$mesaj = '';
$hata = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kullanici = trim($_POST['username']);
    $mail = trim($_POST['email']);
    $sifre = $_POST['password'];

    if ($kullanici && $mail && $sifre) {
        $kontrol = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $kontrol->execute([$kullanici, $mail]);

        if ($kontrol->fetch()) {
            $hata = "Zaten kayıtlı gibi görünüyorsun.";
        } else {
            $sifreli = password_hash($sifre, PASSWORD_DEFAULT);
            $ekle = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($ekle->execute([$kullanici, $mail, $sifreli])) {
                $mesaj = "Başarıyla kayıt oldun. Birazdan giriş sayfasına yönlendirileceksin...";
                header("Refresh:3; url=admin/login.php");
            } else {
                $hata = "Kayıt olurken bir sıkıntı çıktı.";
            }
        }
    } else {
        $hata = "Boş bırakma lütfen.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol | Ayşenur'un Blogu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fdfdfd; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 500px; margin-top: 80px; }
        h2 { color: #6f4e37; font-weight: bold; }
        .form-label { font-weight: 500; color: #6f4e37; }
        .btn-coffee { background-color: #6f4e37; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">📝 Yeni Hesap Oluştur</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?= $mesaj ?></div>
    <?php elseif ($hata): ?>
        <div class="alert alert-danger"><?= $hata ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Kullanıcı Adı</label>
            <input type="text" name="username" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">E-posta</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Şifre</label>
            <input type="password" name="password" class="form-control">
        </div>

        <button class="btn btn-coffee">Kayıt Ol</button>
        <a href="index.php" class="btn btn-secondary ms-2">← Ana Sayfa</a>
    </form>
</div>

</body>
</html>


