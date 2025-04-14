<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$yaziID = $_GET['id'] ?? null;

if ($yaziID) {
    $sil = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $sil->execute([$yaziID]);
}

header("Location: posts.php");
exit;

