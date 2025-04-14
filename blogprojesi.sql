-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 13 Nis 2025, 22:19:55
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `blogprojesi`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(3, 'teknoloji', 'teknoloji', '2025-04-12 16:59:32'),
(4, 'kahve', 'kahve', '2025-04-12 16:59:36'),
(5, 'müzik', 'müzik', '2025-04-12 16:59:39'),
(6, 'ders', 'ders', '2025-04-12 22:04:04'),
(11, 'spor', 'spor', '2025-04-12 22:07:19'),
(12, 'dinlenme', 'dinlenme', '2025-04-12 22:15:46');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `sent_at`) VALUES
(1, 'a', 'a@gmail.com', 'a', 'a', '2025-04-12 17:34:12'),
(2, 'b', 'b@gmail.com', 'b', 'b', '2025-04-12 18:01:53'),
(3, 'c', 'c@gmail.com', 'c', 'c', '2025-04-12 23:21:41'),
(4, 'd', 'd@gmail.com', 'd', 'd', '2025-04-13 22:03:22');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('draft','published') DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `summary`, `content`, `cover_image`, `category_id`, `created_at`, `status`) VALUES
(1, 'Sabah Kahvesi ve Kod Satırları ☕💻', 'sabah-kahvesi-ve-kod-satırları-☕💻', 'Güne bir kahveyle başlayıp kod yazarken fonda hafif bir Lo-fi çalınca… işte en sevdiğim anlardan biri.', '<p>Bilgisayar mühendisliği öğrencisi olmanın en güzel taraflarından biri; kahveni alıp, müzik eşliğinde kod yazmak olabilir. 🎧</p>\r\n\r\n\r\n<p>&lt;p&gt;Benim i&ccedil;in verimli bir sabah ş&ouml;yle başlar:&lt;/p&gt;<br />\r\n&lt;ul&gt;<br />\r\n&nbsp; &lt;li&gt;☕ Sıcak bir kahve&lt;/li&gt;<br />\r\n&nbsp; &lt;li&gt;💻 Visual Studio Code a&ccedil;ık&lt;/li&gt;<br />\r\n&nbsp; &lt;li&gt;🎶 Arka planda soft Lo-fi veya jazz&lt;/li&gt;<br />\r\n&nbsp; &lt;li&gt;📋 Ve yapılacaklar listesinde yeni projeler&lt;/li&gt;<br />\r\n&lt;/ul&gt;</p>\r\n\r\n<p>&lt;p&gt;Senin kod yazma rit&uuml;elin nedir? Yorumlara bekliyorum!&lt;/p&gt;<br />\r\n&nbsp;</p>\r\n', '1744466520_images.jpeg', 3, '2025-04-12 17:02:00', 'published'),
(2, 'Sınav Haftasına 3 Gün Kala', 'sınav-haftasına-3-gün-kala', 'Plan yapıyorum ama her zaman plana sadık kalamıyorum. Yine de denemeye devam...', '<p>Sınavlara 3 gün kaldı ve ben yine hangi derse nasıl çalışacağımı yazdığım bir listeyle başladım güne. Gerçi bu listelerin sonunu nadiren getiriyorum ama yazmak bile bir başlangıç gibi geliyor.</p>\r\n', '', 6, '2025-04-12 22:14:25', 'published'),
(4, 'Biraz Sessizlik İyi Geliyor', 'biraz-sessizlik-İyi-geliyor', 'Bazen hiçbir şey yapmadan durmak da bir ihtiyaçmış, yeni fark ettim.', '<p>Bugün ne kulaklık taktım ne bilgisayarı açtım. Sadece sessizliğe bıraktım kendimi. O kadar alışmışım sürekli bir şey yapmaya ki durmayı unutmuşum. Şaşırtıcı şekilde iyi geldi.</p>\r\n', '', 12, '2025-04-12 22:16:15', 'published'),
(5, 'Yeni Başladığım Bir Alışkanlık', 'yeni-başladığım-bir-alışkanlık', 'Küçük ama etkili bir şey hayatıma dahil ettim: sabahları telefonsuz 30 dakika', '<p>Sabah uyanınca elim telefona gitmeden kahvemi yapıyorum ve pencereden dışarı bakıp bir 10 dakika hiçbir şey yapmadan oturuyorum. Küçük bir şey gibi ama zihinsel olarak çok rahatlatıcı.</p>\r\n', '', 12, '2025-04-12 22:16:59', 'published'),
(6, 'Yanlışlıkla Doğruyu Buldum', 'yanlışlıkla-doğruyu-buldum', 'Bir hata sayesinde işler daha iyi bir hal aldı.', '<p>Proje dosyalarımı düzenlerken yanlışlıkla bir eski versiyonu çalıştırdım ve fark ettim ki daha sade, daha anlaşılır bir kod yapısı kullanmışım. Bazen geri gitmek aslında ileri gitmek olabiliyor.</p>\r\n', '', 3, '2025-04-12 22:17:32', 'published'),
(7, 'Bir Günlük Teknoloji Detoksu', 'bir-günlük-teknoloji-detoksu', 'Telefonu bir günlüğüne kenara bırakmak düşündüğümden daha zor ama iyi bir deneyimdi.', '<p>Dün telefonumu tamamen kapatıp hiçbir sosyal medya uygulamasına girmeden geçirdiğim bir gün oldu. Başta tuhaf hissettirdi ama günün sonunda kafam daha sakin, içim daha dengeliydi. Belki bunu haftada bir rutin haline getirmeliyim.</p>\r\n', '', 12, '2025-04-12 22:23:48', 'published');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'aysenur', '', '$2y$10$NZdxl8w0XqJrgjAXXdFsV.IqEGrUj.MZVaUwW8s.5H8z/l68dKKWq', '2025-04-12 16:46:16'),
(2, 'elif', 'elif@gmail.com', '$2y$10$TyiCh11zI3D0FatU9UfOZuum7I3rFP571AwRwBVadVfdjlT.w3DoS', '2025-04-12 21:47:14');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Tablo için indeksler `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
