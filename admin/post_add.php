<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$kategoriler = $pdo->query("SELECT * FROM categories")->fetchAll();

$mesaj = '';
$hata = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $baslik = trim($_POST['title']);
    $ozet = $_POST['summary'];
    $icerik = $_POST['content'];
    $kategori = $_POST['category'];
    $durum = $_POST['status'];
    $slug = strtolower(str_replace(' ', '-', $baslik));

    $kapak = '';
    if ($_FILES['cover']['name']) {
        $dosya = time() . "_" . basename($_FILES["cover"]["name"]);
        $yol = "../uploads/" . $dosya;

        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $yol)) {
            $kapak = $dosya;
        } else {
            $hata = "Kapak yüklenemedi.";
        }
    }

    if (!$hata) {
        $kayit = $pdo->prepare("INSERT INTO posts (title, slug, summary, content, cover_image, category_id, status, created_at)
                                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($kayit->execute([$baslik, $slug, $ozet, $icerik, $kapak, $kategori, $durum])) {
            $mesaj = "Yazı eklendi.";
        } else {
            $hata = "Kayıt başarısız.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yeni Yazı Ekle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
    <style>
        body { background-color: #fefefe; font-family: 'Segoe UI', sans-serif; }
        .container { max-width: 800px; margin-top: 40px; }
        .btn-coffee { background-color: #6f4e37; color: white; }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">➕ Yeni Yazı Ekle</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?= $mesaj ?></div>
    <?php elseif ($hata): ?>
        <div class="alert alert-danger"><?= $hata ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Başlık</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label>Özet</label>
            <textarea name="summary" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
            <label>İçerik</label>
            <textarea name="content" class="form-control" rows="8"></textarea>
            <script>CKEDITOR.replace('content');</script>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <select name="category" class="form-control">
                <option value="">Seç</option>
                <?php foreach ($kategoriler as $kat): ?>
                    <option value="<?= $kat['id'] ?>"><?= htmlspecialchars($kat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Kapak Görseli</label>
            <input type="file" name="cover" class="form-control">
        </div>

        <div class="mb-3">
            <label>Durum</label>
            <select name="status" class="form-control">
                <option value="draft">Taslak</option>
                <option value="published">Yayınla</option>
            </select>
        </div>

        <button class="btn btn-coffee">Kaydet</button>
        <a href="posts.php" class="btn btn-secondary">İptal</a>
    </form>
</div>
</body>
</html>


