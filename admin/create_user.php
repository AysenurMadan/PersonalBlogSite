<?php
require_once '../config.php';

$kullanici = 'aysenur';
$sifre = '1234';

$hash = password_hash($sifre, PASSWORD_DEFAULT);

$sorgu = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$sorgu->execute([$kullanici, $hash]);

echo "Kullanıcı eklendi.";





