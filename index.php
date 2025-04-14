<?php
require_once 'config.php';

$kategoriler = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$kategori = $_GET['category'] ?? null;
$arama = $_GET['q'] ?? null;
$sirala = $_GET['sort'] ?? 'date_desc';

$siralama = [
    'date_desc' => 'posts.created_at DESC',
    'date_asc' => 'posts.created_at ASC',
    'title_asc' => 'posts.title ASC',
    'title_desc' => 'posts.title DESC'
];
$orderBy = $siralama[$sirala] ?? $siralama['date_desc'];

$limit = 5;
$sayfa = $_GET['page'] ?? 1;
$sayfa = max((int)$sayfa, 1);
$offset = ($sayfa - 1) * $limit;

$sqlCount = "SELECT COUNT(*) FROM posts WHERE status = 'published'";
$sqlList = "SELECT posts.*, categories.name AS category_name FROM posts LEFT JOIN categories ON posts.category_id = categories.id WHERE posts.status = 'published'";

$paramlar = [];

if ($kategori) {
    $sqlCount .= " AND posts.category_id = :kat";
    $sqlList .= " AND posts.category_id = :kat";
    $paramlar[':kat'] = $kategori;
}

if ($arama) {
    $sqlCount .= " AND (title LIKE :q OR summary LIKE :q OR content LIKE :q)";
    $sqlList .= " AND (title LIKE :q OR summary LIKE :q OR content LIKE :q)";
    $paramlar[':q'] = "%$arama%";
}

$sqlList .= " ORDER BY $orderBy LIMIT :limit OFFSET :offset";

$veri = $pdo->prepare($sqlCount);
foreach ($paramlar as $k => $v) $veri->bindValue($k, $v);
$veri->execute();
$toplam = $veri->fetchColumn();
$sayfaSayisi = ceil($toplam / $limit);

$veri = $pdo->prepare($sqlList);
foreach ($paramlar as $k => $v) $veri->bindValue($k, $v);
$veri->bindValue(':limit', $limit, PDO::PARAM_INT);
$veri->bindValue(':offset', $offset, PDO::PARAM_INT);
$veri->execute();
$yazilar = $veri->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ayşenur'un Blogu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #fff; }
        .navbar { background: #ffffff; border-bottom: 1px solid #eee; }
        .navbar-nav .nav-link { font-weight: 600; color: #333; }
        .hero-wrapper { height: 400px; overflow: hidden; position: relative; }
        .hero-wrapper img { width: 100%; height: 100%; object-fit: cover; filter: brightness(60%); }
        .hero-content { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; color: white; z-index: 2; }
        .hero-content h1 { font-size: 3rem; font-weight: bold; }
        .hero-content p { font-size: 1.2rem; }
        .post-list { padding: 50px 20px; }
        .post-item { border-bottom: 1px solid #ddd; margin-bottom: 30px; padding-bottom: 20px; }
        .post-item h3 { font-weight: bold; }
        .post-item small { color: #999; font-style: italic; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Ayşenur Madan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="#posts" class="nav-link">View Posts</a></li>
                <li class="nav-item"><a href="admin/login.php" class="nav-link">Log In</a></li>
                <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero-wrapper">
    <img src="uploads/hero.jpg" alt="Kapak Görseli">
    <div class="hero-content">
        <h1>Ayşenur'un Blogu</h1>
        
    </div>
</div>

<div class="container my-4">
    <form method="get" class="row g-2 align-items-center mb-4">
        <div class="col-sm-4">
            <input type="text" name="q" class="form-control" placeholder="Ara..." value="<?= htmlspecialchars($arama ?? '') ?>">
        </div>
        <div class="col-sm-3">
            <select name="sort" class="form-select">
                <option value="date_desc" <?= $sirala == 'date_desc' ? 'selected' : '' ?>>Yeni Yazılar</option>
                <option value="date_asc" <?= $sirala == 'date_asc' ? 'selected' : '' ?>>Eski Yazılar</option>
                <option value="title_asc" <?= $sirala == 'title_asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="title_desc" <?= $sirala == 'title_desc' ? 'selected' : '' ?>>Z-A</option>
            </select>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-dark">Filtrele</button>
        </div>
        <?php if ($kategori): ?>
            <input type="hidden" name="category" value="<?= $kategori ?>">
        <?php endif; ?>
    </form>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="index.php" class="btn btn-sm btn-outline-secondary">Tümü</a>
        <?php foreach ($kategoriler as $kat): ?>
            <a href="index.php?category=<?= $kat['id'] ?>" class="btn btn-sm btn-outline-primary <?= $_GET['category'] ?? '' == $kat['id'] ? 'active' : '' ?>">
                <?= htmlspecialchars($kat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="container post-list" id="posts">
    <?php if (!$yazilar): ?>
        <p>Hiç yazı bulunamadı.</p>
    <?php else: ?>
        <?php foreach ($yazilar as $yazi): ?>
            <div class="post-item">
                <h3><a href="post.php?slug=<?= $yazi['slug'] ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($yazi['title']) ?></a></h3>
                <p><?= htmlspecialchars($yazi['summary']) ?></p>
                <small>
                    <?= htmlspecialchars($yazi['category_name']) ?> • <?= date('d.m.Y H:i', strtotime($yazi['created_at'])) ?>
                </small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if ($sayfaSayisi > 1): ?>
    <nav class="mt-4 mb-5">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $sayfaSayisi; $i++): ?>
                <li class="page-item <?= $i == $sayfa ? 'active' : '' ?>">
                    <a class="page-link"
                       href="index.php?page=<?= $i ?><?= $kategori ? '&category=' . $kategori : '' ?><?= $arama ? '&q=' . urlencode($arama) : '' ?><?= $sirala ? '&sort=' . $sirala : '' ?>#posts">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
<?php endif; ?>

<footer class="footer mt-5 py-3 text-center bg-light border-top">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Ayşenur Madan</p>
        <small>Bu blog, kod tutkusuyla geliştirildi</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




