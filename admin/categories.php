<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$mesaj = '';
$hata = '';

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sil = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $sil->execute([$id]);
    $mesaj = "Kategori silindi.";
}

if (!empty($_POST['update_id']) && isset($_POST['update_name'])) {
    $id = $_POST['update_id'];
    $isim = trim($_POST['update_name']);
    $slug = strtolower(str_replace(' ', '-', $isim));

    $guncelle = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
    $guncelle->execute([$isim, $slug, $id]);
    $mesaj = "Güncelleme tamamlandı.";
}

if (isset($_POST['name']) && empty($_POST['update_id'])) {
    $isim = trim($_POST['name']);
    $slug = strtolower(str_replace(' ', '-', $isim));

    if (!empty($isim)) {
        $kontrol = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE slug = ?");
        $kontrol->execute([$slug]);

        if ($kontrol->fetchColumn() > 0) {
            $hata = "Aynı slug zaten var.";
        } else {
            $ekle = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $ekle->execute([$isim, $slug]);
            $mesaj = "Kategori eklendi.";
        }
    } else {
        $hata = "Kategori adı boş olamaz.";
    }
}

$limit = 5;
$sayfa = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$sayfa = max(1, $sayfa);
$baslangic = ($sayfa - 1) * $limit;

$toplam = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$sayfaSayisi = ceil($toplam / $limit);

$cek = $pdo->prepare("SELECT * FROM categories ORDER BY created_at DESC LIMIT :l OFFSET :o");
$cek->bindValue(':l', $limit, PDO::PARAM_INT);
$cek->bindValue(':o', $baslangic, PDO::PARAM_INT);
$cek->execute();
$kategoriler = $cek->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kategoriler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fcf8f3; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 50px; }
        .btn-coffee { background-color: #6f4e37; color: white; }
        .form-control, table { border-radius: 10px; }
        .header { color: #6f4e37; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="header">📁 Kategori Yönetimi</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mesaj) ?></div>
    <?php elseif ($hata): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($hata) ?></div>
    <?php endif; ?>

    <form method="POST" class="mb-4">
        <input type="hidden" name="update_id" value="<?= $_GET['edit'] ?? '' ?>">
        <div class="input-group">
            <input type="text"
                   name="<?= isset($_GET['edit']) ? 'update_name' : 'name' ?>"
                   class="form-control"
                   value="<?= isset($_GET['edit']) ? htmlspecialchars($pdo->query("SELECT name FROM categories WHERE id = " . $_GET['edit'])->fetchColumn()) : '' ?>">
            <button class="btn btn-coffee"><?= isset($_GET['edit']) ? 'Güncelle' : 'Ekle' ?></button>
            <?php if (isset($_GET['edit'])): ?>
                <a href="categories.php" class="btn btn-secondary">İptal</a>
            <?php endif; ?>
        </div>
    </form>

    <table class="table table-bordered bg-white">
        <thead>
        <tr>
            <th>ID</th>
            <th>İsim</th>
            <th>Slug</th>
            <th>İşlem</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($kategoriler as $kategori): ?>
            <tr>
                <td><?= $kategori['id'] ?></td>
                <td><?= htmlspecialchars($kategori['name']) ?></td>
                <td><?= htmlspecialchars($kategori['slug']) ?></td>
                <td>
                    <a href="?edit=<?= $kategori['id'] ?>" class="btn btn-sm btn-warning">Düzenle</a>
                    <a href="?delete=<?= $kategori['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">Sil</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination mt-4">
            <?php for ($i = 1; $i <= $sayfaSayisi; $i++): ?>
                <li class="page-item <?= $i == $sayfa ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <a href="dashboard.php" class="btn btn-secondary mt-3">← Panele Dön</a>
</div>
</body>
</html>





