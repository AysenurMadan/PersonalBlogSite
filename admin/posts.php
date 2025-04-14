<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$arama = $_GET['search'] ?? '';
$sirala = $_GET['sort'] ?? 'date_desc';

$siralama = [
    'date_desc' => 'created_at DESC',
    'date_asc' => 'created_at ASC',
    'title_asc' => 'title ASC',
    'title_desc' => 'title DESC'
];
$orderby = $siralama[$sirala] ?? $siralama['date_desc'];

$limit = 5;
$sayfa = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$sayfa = max($sayfa, 1);
$baslangic = ($sayfa - 1) * $limit;

if ($arama) {
    $toplam = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE title LIKE ?");
    $toplam->execute(["%$arama%"]);
    $adet = $toplam->fetchColumn();

    $veri = $pdo->prepare("SELECT posts.*, categories.name AS category_name 
                           FROM posts 
                           LEFT JOIN categories ON posts.category_id = categories.id
                           WHERE posts.title LIKE ?
                           ORDER BY $orderby
                           LIMIT $limit OFFSET $baslangic");
    $veri->execute(["%$arama%"]);
} else {
    $adet = $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();

    $veri = $pdo->prepare("SELECT posts.*, categories.name AS category_name 
                           FROM posts 
                           LEFT JOIN categories ON posts.category_id = categories.id
                           ORDER BY $orderby
                           LIMIT $limit OFFSET $baslangic");
    $veri->execute();
}

$toplamSayfa = ceil($adet / $limit);
$liste = $veri->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yazılar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fdfcfb; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 50px; }
        .btn-coffee { background-color: #6f4e37; color: white; }
        .pagination a {
            margin: 0 4px;
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid #ddd;
            color: #6f4e37;
            border-radius: 5px;
        }
        .pagination .active {
            background-color: #6f4e37;
            color: white;
            border: 1px solid #6f4e37;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Yazılar</h2>

    <form method="GET" class="row g-2 align-items-center mb-3">
        <div class="col-sm-4">
            <input type="text" name="search" value="<?= htmlspecialchars($arama) ?>" class="form-control" placeholder="Başlık ara...">
        </div>
        <div class="col-sm-3">
            <select name="sort" class="form-select">
                <option value="date_desc" <?= $sirala == 'date_desc' ? 'selected' : '' ?>>Tarihe göre (Yeni)</option>
                <option value="date_asc" <?= $sirala == 'date_asc' ? 'selected' : '' ?>>Tarihe göre (Eski)</option>
                <option value="title_asc" <?= $sirala == 'title_asc' ? 'selected' : '' ?>>A-Z</option>
                <option value="title_desc" <?= $sirala == 'title_desc' ? 'selected' : '' ?>>Z-A</option>
            </select>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-coffee w-100">Filtrele</button>
        </div>
        <div class="col-sm-2">
            <a href="posts.php" class="btn btn-secondary w-100">Temizle</a>
        </div>
    </form>

    <a href="post_add.php" class="btn btn-coffee mb-3">+ Yeni</a>

    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <th>ID</th>
            <th>Başlık</th>
            <th>Kategori</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($liste as $yazi): ?>
            <tr>
                <td><?= $yazi['id'] ?></td>
                <td><?= htmlspecialchars($yazi['title']) ?></td>
                <td><?= htmlspecialchars($yazi['category_name']) ?></td>
                <td><?= $yazi['status'] ?></td>
                <td><?= $yazi['created_at'] ?></td>
                <td>
                    <a href="post_edit.php?id=<?= $yazi['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                    <a href="post_delete.php?id=<?= $yazi['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination mt-4">
        <?php for ($i = 1; $i <= $toplamSayfa; $i++): ?>
            <a class="<?= ($i == $sayfa) ? 'active' : '' ?>" href="?page=<?= $i ?><?= $arama ? '&search=' . urlencode($arama) : '' ?><?= $sirala ? '&sort=' . $sirala : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <a href="dashboard.php" class="btn btn-secondary mt-3">← Panele Dön</a>
</div>
</body>
</html>




