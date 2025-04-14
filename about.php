<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Hakkımda | Ayşenur'un Blogu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #fdfdfd;
        }
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
        }
        .navbar-nav .nav-link {
            font-weight: 600;
            color: #333;
        }
        .about-section {
            max-width: 800px;
            margin: 60px auto;
            padding: 0 20px;
        }
        .about-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #6f4e37;
        }
        .about-section p {
            font-size: 1.1rem;
            line-height: 1.8;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Ayşenur Madan</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a href="index.php#posts" class="nav-link">View Posts</a></li>
            <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="about.php" class="nav-link active">About</a></li>
            <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="admin/login.php" class="nav-link">Log In</a></li>
        </ul>
    </div>
</nav>

<div class="about-section">
    <h1>Hakkımda</h1>
    <p>Merhaba, ben Ayşenur </p>
    <p>Haliç Üniversitesi'nde Bilgisayar Mühendisliği 3. sınıf öğrencisiyim. İlgi alanlarım müzik dinlemek, spor, kahve ve kod yazmak.</p>
    <p>Bu blogu, hem teknik anlamda öğrendiklerimi paylaşmak hem de staj başvurularımda kendimi gösterecek sade bir vitrin oluşturmak için hazırladım. </p>
    <p>Buradaki içerikler benim tamamen kişisel olarak ilgi duyduğum şeylerle alakalı. </p>
    <p>Okuduğunuz için teşekkür ederim. Umarım staja alınırım :)</p>
</div>

<footer class="footer mt-5 py-3 text-center bg-light border-top">
    <div class="container">
        <p class="mb-1">© <?= date('Y') ?> Ayşenur Madan</p>
        <small>Bu blog, kod tutkusuyla geliştirildi </small>
    </div>
</footer>

</body>
</html>


