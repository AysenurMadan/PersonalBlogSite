<?php
require_once 'config.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header("Location: index.php");
    exit;
}

$veri = $pdo->prepare("SELECT posts.*, categories.name AS category_name
                       FROM posts
                       LEFT JOIN categories ON posts.category_id = categories.id
                       WHERE posts.slug = ? AND posts.status = 'published'");
$veri->execute([$slug]);
$yazi = $veri->fetch();

if (!$yazi) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($yazi['title']) ?> | Ayşenur'un Blogu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fefefe; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #6f4e37; }
        .navbar-brand { color: white; font-weight: bold; }
        .container { max-width: 800px; margin-top: 60px; padding: 0 15px; }
        .post-title { color: #6f4e37; font-size: 2rem; margin-bottom: 10px; }
        .post-meta { color: #999; font-size: 0.9rem; margin-bottom: 20px; }
        .post-content { font-size: 1.1rem; line-height: 1.8; color: #333; }
        .kapak { width: 100%; max-height: 400px; object-fit: cover; border-radius: 12px; margin-bottom: 20px; }
        .geri-btn { margin-top: 40px; }
    </style>
</head>
<body>

<nav class="navbar px-3">
    <a class="navbar-brand" href="index.php">☕ Ayşenur'un Blogu</a>
</nav>

<div class="container">
    <h1 class="post-title"><?= htmlspecialchars($yazi['title']) ?></h1>
    <div class="post-meta">
        <?= htmlspecialchars($yazi['category_name']) ?> • <?= date('d.m.Y H:i', strtotime($yazi['created_at'])) ?>
    </div>

    <?php if ($yazi['cover_image']): ?>
        <img src="uploads/<?= $yazi['cover_image'] ?>" alt="Kapak" class="kapak">
    <?php endif; ?>

    <div class="post-content">
        <?= $yazi['content'] ?>
    </div>

    <a href="index.php" class="btn btn-secondary geri-btn">← Ana Sayfa</a>
</div>

<footer class="footer mt-5 py-3 text-center bg-light border-top">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Ayşenur Madan</p>
        <small>Bu blog, kod tutkusuyla geliştirildi</small>
    </div>
</footer>

</body>
</html>


