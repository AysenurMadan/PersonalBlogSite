<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$gelenID = $_GET['id'] ?? null;

if (!$gelenID) {
    header("Location: posts.php");
    exit;
}

$bul = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$bul->execute([$gelenID]);
$yazi = $bul->fetch();

if (!$yazi) {
    die("Yazı bulunamadı.");
}

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

    $kapak = $yazi['cover_image'];
    if (!empty($_FILES['cover']['name'])) {
        $dosya = time() . "_" . basename($_FILES["cover"]["name"]);
        $yol = "../uploads/" . $dosya;
        if (move_uploaded_file($_FILES["cover"]["tmp_name"], $yol)) {
            $kapak = $dosya;
        }
    }

    $guncelle = $pdo->prepare("UPDATE posts SET title=?, slug=?, summary=?, content=?, cover_image=?, category_id=?, status=? WHERE id=?");
    $ok = $guncelle->execute([$baslik, $slug, $ozet, $icerik, $kapak, $kategori, $durum, $gelenID]);

    if ($ok) {
        $mesaj = "Yazı güncellendi.";
        $yenile = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $yenile->execute([$gelenID]);
        $yazi = $yenile->fetch();
    } else {
        $hata = "Bir sorun oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yazıyı Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.25.1-lts/standard/ckeditor.js"></script>
</head>
<body class="p-5">

<h2>✏️ Yazıyı Düzenle</h2>

<?php if ($mesaj): ?>
    <div class="alert alert-success"><?= $mesaj ?></div>
<?php elseif ($hata): ?>
    <div class="alert alert-danger"><?= $hata ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Başlık</label>
        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($yazi['title']) ?>">
    </div>

    <div class="mb-3">
        <label>Özet</label>
        <textarea name="summary" class="form-control" rows="2"><?= htmlspecialchars($yazi['summary']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>İçerik</label>
        <textarea id="content" name="content" class="form-control"><?= htmlspecialchars($yazi['content']) ?></textarea>
        <script>CKEDITOR.replace('content');</script>
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category" class="form-control">
            <?php foreach ($kategoriler as $kat): ?>
                <option value="<?= $kat['id'] ?>" <?= $kat['id'] == $yazi['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Kapak Görseli</label>
        <input type="file" name="cover" class="form-control">
        <?php if ($yazi['cover_image']): ?>
            <p class="mt-2"><img src="../uploads/<?= $yazi['cover_image'] ?>" width="100"></p>
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label>Durum</label>
        <select name="status" class="form-control">
            <option value="draft" <?= $yazi['status'] == 'draft' ? 'selected' : '' ?>>Taslak</option>
            <option value="published" <?= $yazi['status'] == 'published' ? 'selected' : '' ?>>Yayınlandı</option>
        </select>
    </div>

    <button class="btn btn-primary">Kaydet</button>
    <a href="posts.php" class="btn btn-secondary">Geri</a>
</form>

</body>
</html>



