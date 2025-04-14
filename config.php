<?php
$host = "localhost";
$veritabani = "blogProjesi";
$kullanici = "root";
$sifre = "1234";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$veritabani;charset=utf8mb4", $kullanici, $sifre);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $hata) {
    die("Bağlantı kurulamadı: " . $hata->getMessage());
}

