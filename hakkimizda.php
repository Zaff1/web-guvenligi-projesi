<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Hakkımızda</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section id="menu">
        <div id="logo">ABM</div>
        <nav>
            <a href="index.php">Anasayfa</a>
            <a href="zafiyetli.php">Zafiyetli Uygulamalar</a>
            <a href="guvenli.php">Güvenli Kodlar</a>
            <a href="hakkimizda.php">Hakkımızda</a>
        </nav>
    </section>

    <div class="container">
        <h2>Geliştirici</h2>
        <div class="box">
            <img src="profil_fotografi.jpg" alt="Zafer BÜYÜKYAVUZ" class="profile-img">
            <h3>Zafer BÜYÜKYAVUZ</h3>
            <p>Okul No: 240525001</p>
            <p>Adli Bilişim Mühendisliği 2. Sınıf öğrencisi</p>
        </div>
    </div>
</body>

</html>