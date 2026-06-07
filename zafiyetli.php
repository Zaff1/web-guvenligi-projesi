<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<?php

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$html_isim = isset($_POST['html_isim']) ? $_POST['html_isim'] : '';
$html_mesaj = isset($_POST['html_mesaj']) ? $_POST['html_mesaj'] : '';

$xss_arama = isset($_GET['search']) ? $_GET['search'] : '';

$xss_yorum = isset($_POST['yorum']) ? $_POST['yorum'] : '';
if ($xss_yorum) {
    $conn->query("INSERT INTO mesajlar (icerik) VALUES ('$xss_yorum')");
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Zafiyetli Uygulamalar Labı</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            margin: 0;
            color: #333;
        }

        #menu {
            background: #222;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        nav a {
            color: #4CAF50;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
        }

        .card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        .card h2 {
            color: #d32f2f;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-top: 0;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            background: #1976d2;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn:hover {
            background: #1565c0;
        }

        .output {
            background: #fff3cd;
            padding: 15px;
            border-left: 5px solid #ffc107;
            margin-top: 20px;
            color: #856404;
        }

        .scenario {
            font-style: italic;
            color: #666;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <section id="menu">
        <h1>Web Güvenliği Test Ortamı</h1>
        <nav>
            <a href="index.php">Anasayfa</a>
            <a href="zafiyetli.php">Zafiyetli Uygulamalar</a>
            <a href="guvenli.php">Güvenli Kodlar</a>
        </nav>
    </section>

    <div class="container">

        <div class="card">
            <h2>1. HTML Injection (Phishing Senaryosu)</h2>
            <p class="scenario"><strong>Senaryo:</strong> Kullanıcıların teknik destek için bildirim bıraktığı bu alanda hiçbir filtreleme yoktur. Saldırgan bu alana sahte bir giriş formu (phishing) veya sayfayı bozan iframe etiketleri yerleştirebilir.</p>

            <form method="POST" action="zafiyetli.php#htmli">
                <input type="text" name="html_isim" class="form-control" placeholder="Adınız Soyadınız">
                <textarea name="html_mesaj" class="form-control" placeholder="Destek almak istediğiniz konuyu yazın..." rows="3"></textarea>
                <button type="submit" class="btn" id="htmli">Bildirim Gönder</button>
            </form>

            <?php if ($html_isim || $html_mesaj): ?>
                <div class="output">
                    <h3>Sistem Yanıtı:</h3>
                    <p><strong>Gönderen:</strong> <?php echo $html_isim; ?></p>
                    <p><strong>Mesajınız:</strong> <?php echo $html_mesaj; ?></p> <!-- ZAFİYET: Filtresiz çıktı -->
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>2. Reflected XSS (Anlık Yansıma)</h2>
            <p class="scenario"><strong>Senaryo:</strong> Sitedeki ürün/içerik arama motoru. Kullanıcının URL'deki parametreye (GET) yazdığı değer anında sayfaya basılır. Kurban bu URL'e tıkladığında zararlı JavaScript kodu kendi tarayıcısında çalışır.</p>

            <form method="GET" action="zafiyetli.php#rxss">
                <input type="text" name="search" class="form-control" placeholder="Sitede ne aramak istersiniz?">
                <button type="submit" class="btn" id="rxss" style="background: #2e7d32;">Arama Yap</button>
            </form>

            <?php if ($xss_arama): ?>
                <div class="output" style="background: #e2e3e5; border-left-color: #383d41; color: #383d41;">
                    <p>Aradığınız kelime: <strong><?php echo $xss_arama; ?></strong></p>
                    <p>Sistemde bu terimle eşleşen bir sonuç bulunamadı.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>3. Stored XSS (Kalıcı Tehdit)</h2>
            <p class="scenario"><strong>Senaryo:</strong> Ziyaretçi Defteri. Buraya yazılan yorumlar doğrudan veritabanına (mesajlar tablosuna) kaydedilir. Sayfaya giren <strong>herkes</strong> bu zararlı kodu otomatik olarak çalıştırır ve session/cookie bilgilerini çaldırabilir.</p>

            <form method="POST" action="zafiyetli.php#sxss">
                <textarea name="yorum" class="form-control" placeholder="Ziyaretçi defterine kalıcı bir yorum bırakın..." rows="3"></textarea>
                <button type="submit" class="btn" id="sxss" style="background: #c62828;">Yorumu Veritabanına Kaydet</button>
            </form>

            <div class="output" style="background: #f8d7da; border-left-color: #dc3545; color: #721c24;">
                <h3>Veritabanındaki Son Yorumlar:</h3>
                <?php
                $mesajlar = $conn->query("SELECT * FROM mesajlar ORDER BY id DESC");
                if ($mesajlar && $mesajlar->num_rows > 0) {
                    while ($row = $mesajlar->fetch_assoc()) {
                        echo "<div style='border-bottom: 1px dashed #dc3545; padding: 10px 0;'><strong>Yorum ID [" . $row['id'] . "]:</strong> <br> " . $row['icerik'] . "</div>";
                    }
                } else {
                    echo "<p>Henüz kayıtlı yorum bulunmamaktadır.</p>";
                }
                ?>
            </div>
        </div>

    </div>
</body>

</html>