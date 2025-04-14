<?php
require_once 'config.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header("Location: index.php");
    exit;
}

// Kategori bilgisi
$katSorgu = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$katSorgu->execute([$slug]);
$kategori = $katSorgu->fetch();

if (!$kategori) {
    header("Location: index.php");
    exit;
}

// Yazılar
$yaziSorgu = $pdo->prepare("SELECT * FROM posts WHERE category_id = ? AND status = 'published' ORDER BY created_at DESC");
$yaziSorgu->execute([$kategori['id']]);
$liste = $yaziSorgu->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($kategori['name']) ?> | Ayşenur'un Blogu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">📁 <?= htmlspecialchars($kategori['name']) ?> Kategorisi</h2>

    <?php if (count($liste) === 0): ?>
        <p>Burada henüz yazı yok.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($liste as $yazi): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="p-3 border rounded shadow-sm">
                        <h5><?= htmlspecialchars($yazi['title']) ?></h5>
                        <p><?= htmlspecialchars($yazi['summary']) ?></p>
                        <a href="post.php?slug=<?= $yazi['slug'] ?>" class="btn btn-sm btn-primary">Devamını Oku</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-4">← Ana Sayfa</a>
</div>

<footer class="footer mt-5 py-3 text-center bg-light border-top">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Ayşenur Madan</p>
        <small>Bu blog, kod tutkusuyla geliştirildi</small>
    </div>
</footer>

</body>
</html>

