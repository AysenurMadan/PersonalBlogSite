<?php
require_once 'config.php';

$mesaj = '';
$hata = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ad = trim($_POST['name']);
    $eposta = trim($_POST['email']);
    $konu = trim($_POST['subject']);
    $icerik = trim($_POST['message']);

    if ($ad && $eposta && $konu && $icerik) {
        $ekle = $pdo->prepare("INSERT INTO messages (name, email, subject, message, sent_at) VALUES (?, ?, ?, ?, NOW())");
        if ($ekle->execute([$ad, $eposta, $konu, $icerik])) {
            $mesaj = "Mesaj gönderildi.";
        } else {
            $hata = "Bir şey ters gitti.";
        }
    } else {
        $hata = "Lütfen tüm alanları doldurun.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İletişim | Ayşenur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fffdfb; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #ffffff; border-bottom: 1px solid #eee; }
        .navbar-nav .nav-link { font-weight: 600; color: #333; }
        .container { max-width: 600px; margin-top: 60px; }
        h2 { color: #6f4e37; font-weight: bold; }
        .form-label { font-weight: 500; color: #6f4e37; }
        .btn-coffee { background-color: #6f4e37; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Ayşenur Madan</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="index.php#posts" class="nav-link">View Posts</a></li>
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link active">Contact</a></li>
            <li class="nav-item"><a href="admin/login.php" class="nav-link">Log In</a></li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2 class="mt-4 mb-4">📬 Mesaj Bırak</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?= $mesaj ?></div>
    <?php elseif ($hata): ?>
        <div class="alert alert-danger"><?= $hata ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Adınız</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">E-posta</label>
            <input type="email" name="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Konu</label>
            <input type="text" name="subject" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Mesaj</label>
            <textarea name="message" class="form-control" rows="5"></textarea>
        </div>

        <button class="btn btn-coffee">Gönder</button>
        <a href="index.php" class="btn btn-secondary ms-2">← Ana Sayfa</a>
    </form>
</div>

<footer class="footer mt-5 py-3 text-center bg-light border-top">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Ayşenur Madan</p>
        <small>Bu blog, kod tutkusuyla geliştirildi</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




