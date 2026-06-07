<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<?php
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$html_isim = isset($_POST['html_isim']) ? htmlspecialchars($_POST['html_isim'], ENT_QUOTES, 'UTF-8') : '';
$html_mesaj = isset($_POST['html_mesaj']) ? htmlspecialchars($_POST['html_mesaj'], ENT_QUOTES, 'UTF-8') : '';


$xss_arama = isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : '';


$xss_yorum = isset($_POST['yorum']) ? $_POST['yorum'] : '';
if ($xss_yorum) {
    $stmt = $conn->prepare("INSERT INTO mesajlar (icerik) VALUES (?)");
    $stmt->bind_param("s", $xss_yorum);
    $stmt->execute();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Güvenli Uygulamalar Labı</title>
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
            color: #2e7d32;
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
            background: #e8f5e9;
            padding: 15px;
            border-left: 5px solid #4CAF50;
            margin-top: 20px;
            color: #1b5e20;
        }

        .scenario {
            font-style: normal;
            color: #444;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.5;
            background: #f9f9f9;
            padding: 10px;
            border-left: 3px solid #1976d2;
        }
    </style>
</head>

<body>

    <section id="menu">
        <h1>Web Güvenliği Test Ortamı (Güvenli Sürüm)</h1>
        <nav>
            <a href="index.php">Anasayfa</a>
            <a href="zafiyetli.php">Zafiyetli Uygulamalar</a>
            <a href="guvenli.php">Güvenli Kodlar</a>
        </nav>
    </section>

    <div class="container">

        <div class="card">
            <h2>1. HTML Injection (Phishing Koruması)</h2>
            <div class="scenario"><strong>Çözüm Yöntemi:</strong> <code>htmlspecialchars()</code> fonksiyonu kullanılarak kullanıcıdan gelen tüm etiketler (örneğin &lt;form&gt; veya &lt;iframe&gt;) zararsız metinlere dönüştürüldü.</div>

            <form method="POST" action="guvenli.php#htmli">
                <input type="text" name="html_isim" class="form-control" placeholder="Adınız Soyadınız">
                <textarea name="html_mesaj" class="form-control" placeholder="Destek almak istediğiniz konuyu yazın..." rows="3"></textarea>
                <button type="submit" class="btn" id="htmli">Bildirim Gönder</button>
            </form>

            <?php if ($html_isim || $html_mesaj): ?>
                <div class="output">
                    <h3>Sistem Yanıtı:</h3>
                    <p><strong>Gönderen:</strong> <?php echo $html_isim; ?></p>
                    <p><strong>Mesajınız:</strong> <?php echo $html_mesaj; ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>2. Reflected XSS (Girdi Temizleme)</h2>
            <div class="scenario"><strong>Çözüm Yöntemi:</strong> URL üzerinden gelen (GET) arama verisi, sayfaya yazdırılmadan önce <code>ENT_QUOTES</code> parametresiyle birlikte maskelendi. Script etiketleri engellendi.</div>

            <form method="GET" action="guvenli.php#rxss">
                <input type="text" name="search" class="form-control" placeholder="Sitede ne aramak istersiniz?">
                <button type="submit" class="btn" id="rxss" style="background: #2e7d32;">Arama Yap</button>
            </form>

            <?php if ($xss_arama): ?>
                <div class="output">
                    <p>Aradığınız kelime: <strong><?php echo $xss_arama; ?></strong></p>
                    <p>Sistemde bu terimle eşleşen bir sonuç bulunamadı.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h2>3. Stored XSS (Prepared Statement & Çıktı Maskeleme)</h2>
            <div class="scenario"><strong>Çözüm Yöntemi:</strong> Veri tabanına kayıt yapılırken <strong>Prepared Statements</strong> kullanıldı. Ayrıca veritabanından çekilen veri ekrana basılırken tekrar <code>htmlspecialchars()</code> işleminden geçirildi.</div>

            <form method="POST" action="guvenli.php#sxss">
                <textarea name="yorum" class="form-control" placeholder="Ziyaretçi defterine kalıcı bir yorum bırakın..." rows="3"></textarea>
                <button type="submit" class="btn" id="sxss" style="background: #c62828;">Yorumu Güvenli Kaydet</button>
            </form>

            <div class="output">
                <h3>Veritabanındaki Son Yorumlar:</h3>
                <?php
                $mesajlar = $conn->query("SELECT * FROM mesajlar ORDER BY id DESC");
                if ($mesajlar && $mesajlar->num_rows > 0) {
                    while ($row = $mesajlar->fetch_assoc()) {
                        echo "<div style='border-bottom: 1px dashed #4CAF50; padding: 10px 0;'><strong>Yorum ID [" . $row['id'] . "]:</strong> <br> " . htmlspecialchars($row['icerik'], ENT_QUOTES, 'UTF-8') . "</div>";
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