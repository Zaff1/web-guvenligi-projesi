<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
$kullanici = $_SESSION["user"];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ana Sayfa - Web Güvenliği Projesi</title>
<link rel="stylesheet" href="style.css">
<style>
    .admin-panel { background-color: #f44336; color: white; padding: 15px; text-align: center; font-size: 18px; font-weight: bold; animation: blinker 1s linear infinite; }
    @keyframes blinker { 50% { opacity: 0.5; } }
    .user-panel { background-color: #4CAF50; color: white; padding: 10px; text-align: center; font-size: 16px; font-weight: bold; }
</style>
</head>
<body>
<section id="menu">
<div id="logo">ABM</div>
<nav>
<a href="index.php">Anasayfa</a>
<a href="zafiyetli.php">Zafiyetli Uygulamalar</a>
<a href="guvenli.php">Güvenli Kodlar</a>
<a href="hakkimizda.php">Hakkımızda</a>
<a href="logout.php" style="color: #ff5252; font-weight: bold;">Çıkış Yap</a>
</nav>
</section>

<?php if ($kullanici === "admin"): ?>
    <div class="admin-panel">
        ⚠️ YÖNETİCİ YETKİLERİYLE GİRİŞ YAPILDI - TÜM SİSTEM ERİŞİMİ AÇIK ⚠️
    </div>
<?php else: ?>
    <div class="user-panel">
        Hoşgeldin, <?php echo htmlspecialchars($kullanici); ?>. (Standart Kullanıcı)
    </div>
<?php endif; ?>

<div class="banner">
<img src="profil_fotografi.jpg" alt="Zafer BÜYÜKYAVUZ" class="profile-img">
<h1>Zafer BÜYÜKYAVUZ</h1>
<h3>Okul No: 240525001</h3>
<p>Adli Bilişim Mühendisliği 2. Sınıf öğrencisi</p>
</div>

<div class="container">
<h2>Web Güvenliği ve Güvenli Kodlama Ödevi</h2>
<p>Bu platform, web uygulamalarındaki temel zafiyetleri (HTML Injection, XSS, SQL Injection) göstermek ve bu zafiyetlerin kaynak kod seviyesinde nasıl giderileceğini uygulamalı olarak sunmak amacıyla Zafer BÜYÜKYAVUZ tarafından geliştirilmiştir.</p>
</div>
</body>
</html>