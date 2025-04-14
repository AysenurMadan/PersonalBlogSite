<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$yaziSayisi = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$kategoriSayisi = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$mesajSayisi = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Panel | Ayşenur</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #fdfdfd; }
        .navbar { background-color: #6f4e37; }
        .navbar .nav-link, .navbar-brand { color: white; font-weight: 500; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .card h5 { color: #6f4e37; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg px-3">
    <a class="navbar-brand" href="#">☕ Ayşenur Panel</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="../index.php" class="nav-link">Site</a></li>
            <li class="nav-item"><a href="posts.php" class="nav-link">Yazılar</a></li>
            <li class="nav-item"><a href="categories.php" class="nav-link">Kategoriler</a></li>
            <li class="nav-item"><a href="messages.php" class="nav-link">Mesajlar</a></li>
            <li class="nav-item"><a href="logout.php" class="nav-link text-warning">Çıkış</a></li>
        </ul>
    </div>
</nav>

<div class="container my-5">
    <h2 class="mb-4">📊 Genel Bakış</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card p-4">
                <h5>Toplam Yazı</h5>
                <p class="fs-3"><?= $yaziSayisi ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5>Kategori</h5>
                <p class="fs-3"><?= $kategoriSayisi ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4">
                <h5>Mesaj</h5>
                <p class="fs-3"><?= $mesajSayisi ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>



