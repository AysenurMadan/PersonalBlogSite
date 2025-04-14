<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../config.php';

$limit = 5;
$sayfa = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$sayfa = max(1, $sayfa);
$baslangic = ($sayfa - 1) * $limit;

$toplam = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
$toplamSayfa = ceil($toplam / $limit);

$liste = $pdo->prepare("SELECT * FROM messages ORDER BY sent_at DESC LIMIT :l OFFSET :o");
$liste->bindValue(':l', $limit, PDO::PARAM_INT);
$liste->bindValue(':o', $baslangic, PDO::PARAM_INT);
$liste->execute();
$veriler = $liste->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Gelen Mesajlar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fefefe; font-family: 'Segoe UI', sans-serif; }
        .container { margin-top: 50px; }
    </style>
</head>
<body>

<div class="container">
    <h2>✉️ Gelen Mesajlar</h2>

    <?php if (!$veriler): ?>
        <p>Henüz bir mesaj yok.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Gönderen</th>
                    <th>Email</th>
                    <th>Konu</th>
                    <th>Mesaj</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($veriler as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><?= htmlspecialchars($m['email']) ?></td>
                        <td><?= htmlspecialchars($m['subject']) ?></td>
                        <td><?= nl2br(htmlspecialchars($m['message'])) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($m['sent_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination mt-4">
                <?php for ($i = 1; $i <= $toplamSayfa; $i++): ?>
                    <li class="page-item <?= $i == $sayfa ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>

    <a href="dashboard.php" class="btn btn-secondary mt-3">← Panele Dön</a>
</div>

</body>
</html>




